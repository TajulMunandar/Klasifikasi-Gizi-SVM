<?php

namespace App\Http\Controllers;

use App\Models\Classification;
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

        $hasilKlasifikasi = $query->latest()->get();

        // Ambil daftar desa unik dari data anak
        $daftarDesa = \App\Models\DataAnak::select('desa')->distinct()->pluck('desa');

        $jumlahGiziBaik = $hasilKlasifikasi->where('klasifikasi', 'Gizi Baik')->count();
        $jumlahGiziKurangBaik = $hasilKlasifikasi->where('klasifikasi', 'Gizi Kurang')->count();
        $jumlahGiziBuruk = $hasilKlasifikasi->where('klasifikasi', 'Gizi Buruk')->count();
        $RisikoGiziLebih = $hasilKlasifikasi->where('klasifikasi', 'Risiko Gizi Lebih')->count();

        $daftarDesa = \App\Models\DataAnak::select('desa')->distinct()->pluck('desa');

        $page = 'Dashboard';
        return view('dashboard.pages.index', compact('page', 'hasilKlasifikasi', 'daftarDesa', 'jumlahGiziBaik', 'jumlahGiziKurangBaik', 'RisikoGiziLebih', 'jumlahGiziBuruk'));
    }
}
