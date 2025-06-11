<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $listKampung = DB::table('data_anaks')
            ->join('classifications', 'data_anaks.id', '=', 'classifications.id_data_anak')
            ->select('data_anaks.desa')
            ->distinct()
            ->pluck('desa')
            ->toArray();

        $kampungTerbanyak = DB::table('data_anaks')
            ->join('classifications', 'data_anaks.id', '=', 'classifications.id_data_anak')
            ->select('data_anaks.desa', DB::raw('count(*) as jumlah'))
            ->groupBy('data_anaks.desa')
            ->orderByDesc('jumlah')
            ->first();

        $totalKampung = count($listKampung);

        return view('welcome', compact('listKampung', 'kampungTerbanyak', 'totalKampung'));
    }
}
