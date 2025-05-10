<?php

namespace App\Http\Controllers;

use App\Models\DataAnak;
use Illuminate\Http\Request;

class DataAnakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Data Anak';
        $anaks = DataAnak::all();
        return view('dashboard.pages.data-anak', compact('page', 'anaks'));
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
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|max:20|unique:data_anaks,nik',
                'jk' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'nama_ortu' => 'required|string|max:255',
                'prov' => 'required|string',
                'kab' => 'required|string',
                'kec' => 'required|string',
                'desa' => 'required|string',
                'puskesmas' => 'required|string',
                'posyandu' => 'required|string',
                'alamat' => 'required|string',
                'usia_ukur' => 'required|integer',
                'tgl_pengukuran' => 'required|date',
                'berat' => 'required|numeric',
                'cara_ukur' => 'required|string',
                'lila' => 'nullable|numeric',
                'bb_u' => 'nullable|string',
                'tb_u' => 'nullable|string',
                'bb_tb' => 'nullable|string',
                'label_gizi' => 'nullable',
            ]);

            DataAnak::create($validated);

            return redirect()->back()->with('success', 'Data anak berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
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
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|max:20|unique:data_anaks,nik,' . $id,
                'jk' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'nama_ortu' => 'required|string|max:255',
                'prov' => 'required|string',
                'kab' => 'required|string',
                'kec' => 'required|string',
                'desa' => 'required|string',
                'puskesmas' => 'required|string',
                'posyandu' => 'required|string',
                'alamat' => 'required|string',
                'usia_ukur' => 'required|integer',
                'tgl_pengukuran' => 'required|date',
                'berat' => 'required|numeric',
                'cara_ukur' => 'required|string',
                'lila' => 'nullable|numeric',
                'bb_u' => 'nullable|string',
                'tb_u' => 'nullable|string',
                'bb_tb' => 'nullable|string',
                'label_gizi' => 'nullable',
            ]);

            $anak = DataAnak::findOrFail($id);
            $anak->update($validated);

            return redirect()->back()->with('success', 'Data anak berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $anak = DataAnak::findOrFail($id);
            $anak->delete();

            return redirect()->back()->with('success', 'Data anak berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
