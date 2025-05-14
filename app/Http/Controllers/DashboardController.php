<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Classification::with('dataAnak');

        if ($request->filled('desa')) {
            $query->whereHas('dataAnak', function ($q) use ($request) {
                $q->where('desa', $request->desa);
            });
        }

        $hasilKlasifikasi = $query->latest()->get();

        // Ambil daftar desa unik dari data anak
        $daftarDesa = \App\Models\DataAnak::select('desa')->distinct()->pluck('desa');

        $page = 'Dashboard';
        return view('dashboard.pages.index', compact('page', 'hasilKlasifikasi', 'daftarDesa'));
    }
}
