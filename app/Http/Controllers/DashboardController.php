<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\DataAnak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Classification::with('dataAnak')
            ->select('classifications.*')
            ->join(DB::raw('(SELECT MAX(id) as latest_id FROM classifications GROUP BY id_data_anak) as latest'), function ($join) {
                $join->on('classifications.id', '=', 'latest.latest_id');
            });

        if ($request->filled('desa')) {
            $query->whereHas('dataAnak', function ($q) use ($request) {
                $q->where('desa', $request->desa);
            });
        }

        $daftarAnak = DataAnak::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('data_anaks')
                ->groupBy('nik');
        })->get();

        $hasilKlasifikasi = $query->latest()->get();

        // Ambil daftar desa unik dari data anak
        $daftarDesa = DataAnak::select('desa')->distinct()->pluck('desa');

        $jumlahGiziBaik = $hasilKlasifikasi->where('klasifikasi', 'Gizi Baik')->count();
        $jumlahGiziKurangBaik = $hasilKlasifikasi->where('klasifikasi', 'Gizi Kurang')->count();
        $jumlahGiziBuruk = $hasilKlasifikasi->where('klasifikasi', 'Gizi Buruk')->count();
        $RisikoGiziLebih = $hasilKlasifikasi->where('klasifikasi', 'Risiko Gizi Lebih')->count();

        $dataGrafik = collect();

        if ($request->filled('anak')) {
            $dataGrafik = DB::table('classifications')
                ->join('data_anaks', 'classifications.id_data_anak', '=', 'data_anaks.id')
                ->where('data_anaks.id', $request->anak) // filter berdasarkan anak yang dipilih
                ->orderBy('data_anaks.tgl_pengukuran')
                ->get([
                    'data_anaks.tgl_pengukuran as tanggal',
                    'classifications.klasifikasi'
                ]);
        }


        $page = 'Dashboard';
        return view('dashboard.pages.index', compact('page', 'hasilKlasifikasi', 'daftarDesa', 'jumlahGiziBaik', 'jumlahGiziKurangBaik', 'RisikoGiziLebih', 'jumlahGiziBuruk', 'daftarAnak', 'dataGrafik'));
    }

    public function statistikKlasifikasiPerDesa()
    {
        $page = 'Laporan';
        $latest = Classification::select(DB::raw('MAX(id) as id'))
            ->groupBy('id_data_anak');

        $classifications = Classification::with('dataAnak')
            ->whereIn('id', $latest->pluck('id'))
            ->get();

        $grouped = $classifications->groupBy(function ($item) {
            return $item->dataAnak->desa ?? 'Tidak Diketahui';
        });

        $data = [];

        foreach ($grouped as $desa => $items) {
            $data[] = [
                'desa' => $desa,
                'total' => $items->count(),
                'Gizi Baik' => $items->where('klasifikasi', 'Gizi Baik')->count(),
                'Gizi Kurang' => $items->where('klasifikasi', 'Gizi Kurang')->count(),
                'Gizi Buruk' => $items->where('klasifikasi', 'Gizi Buruk')->count(),
                'Risiko Gizi Lebih' => $items->where('klasifikasi', 'Risiko Gizi Lebih')->count(),
            ];
        }

        // Tampilkan ke view
        return view('dashboard.pages.laporan', compact('page', 'data'));
    }
}
