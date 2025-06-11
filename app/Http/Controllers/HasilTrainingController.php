<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\DataAnak;
use App\Models\Preprocessing;
use Illuminate\Http\Request;

class HasilTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Hasil Training';
        $trainings = Classification::all();
        return view('dashboard.pages.hasil-training', compact('page', 'trainings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->input('hasil') as $hasil) {
            $nama = $hasil['nama'];

            // Cari id_data_anak berdasarkan nama (pastikan nama unik)
            $anak = DataAnak::where('nama', $nama)->first();

            if ($anak) {
                Classification::create([
                    'id_data_anak' => $anak->id,
                    'klasifikasi' => $hasil['prediksi'],
                    'f1_score' => $request->input('evaluasi')['weighted avg']['f1-score'],
                    'accuracy' => $request->input('evaluasi')['accuracy'],
                    'probabilitas' => $hasil['probabilitas'], // karena SVM tidak menghitung probabilitas langsung
                ]);
            }
        }

        return response()->json(['message' => 'Data klasifikasi berhasil disimpan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getData()
    {
        $data = Preprocessing::select(
            'nama',
            'jenis_kelamin',
            'usia_bulan',
            'berat',
            'tinggi',
            'zs_bb_u',
            'zs_tb_u',
            'zs_bb_tb',
            'label_gizi'
        )->get();

        return response()->json($data);
    }
}
