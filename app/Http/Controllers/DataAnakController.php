<?php

namespace App\Http\Controllers;

use App\Imports\DataAnakImport;
use App\Models\DataAnak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DataAnakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Data Anak';

        if ($request->ajax()) {
            $data = DataAnak::select([
                'id',
                'nama',
                'nik',
                'jk',
                'tanggal_lahir',
                'nama_ortu',
                'prov',
                'kab',
                'kec',
                'tinggi',
                'desa',
                'puskesmas',
                'posyandu',
                'alamat',
                'usia_ukur',
                'tgl_pengukuran',
                'berat',
                'cara_ukur',
                'lila',
                'zs_bb_u',
                'zs_tb_u',
                'zs_bb_tb',
                'bb_u',
                'tb_u',
                'bb_tb'
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                 <button class="btn btn-warning btn-sm btn-edit"
            data-bs-toggle="modal"
            data-bs-target="#modalEdit"
            data-id="' . $row->id . '"
            data-nama="' . e($row->nama) . '"
            data-nik="' . e($row->nik) . '"
            data-jk="' . e($row->jk) . '"
            data-tanggal_lahir="' . e($row->tanggal_lahir) . '"
            data-nama_ortu="' . e($row->nama_ortu) . '"
            data-prov="' . e($row->prov) . '"
            data-kab="' . e($row->kab) . '"
            data-kec="' . e($row->kec) . '"
            data-tinggi="' . e($row->tinggi) . '"
            data-desa="' . e($row->desa) . '"
            data-puskesmas="' . e($row->puskesmas) . '"
            data-posyandu="' . e($row->posyandu) . '"
            data-alamat="' . e($row->alamat) . '"
            data-usia_ukur="' . e($row->usia_ukur) . '"
            data-tgl_pengukuran="' . e($row->tgl_pengukuran) . '"
            data-berat="' . e($row->berat) . '"
            data-cara_ukur="' . e($row->cara_ukur) . '"
            data-lila="' . e($row->lila) . '"
            data-tinggi="' . e($row->tinggi) . '"
            data-zs_bb_u="' . e($row->zs_bb_u) . '"
            data-zs_tb_u="' . e($row->zs_tb_u) . '"
            data-zs_bb_tb="' . e($row->zs_bb_tb) . '"
            data-bb_u="' . e($row->bb_u) . '"
            data-tb_u="' . e($row->tb_u) . '"
            data-bb_tb="' . e($row->bb_tb) . '"
            data-label_gizi="' . (isset($row->label_gizi) ? e($row->label_gizi) : '') . '"  >
            Edit
        </button>

        <button class="btn btn-danger btn-sm btn-delete"
            data-bs-toggle="modal"
            data-bs-target="#modalDelete"
            data-id="' . $row->id . '"
            data-nama="' . e($row->nama) . '">
            Hapus
        </button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }



        return view('dashboard.pages.data-anak', compact('page'));
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
                'lila' => 'required|numeric',
                'tinggi' => 'required|numeric',
                'zs_bb_u' => 'required|numeric',
                'bb_u' => 'required|string',
                'zs_tb_u' => 'required|numeric',
                'tb_u' => 'required|string',
                'zs_bb_tb' => 'required|numeric',
                'bb_tb' => 'required|string',

                'label_gizi' => 'required|integer|in:0,1,2,3',
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
                'lila' => 'required|numeric',
                'tinggi' => 'required|numeric',
                'zs_bb_u' => 'required|numeric',
                'bb_u' => 'required|string',
                'zs_tb_u' => 'required|numeric',
                'tb_u' => 'required|string',
                'zs_bb_tb' => 'required|numeric',
                'bb_tb' => 'required|string',
                'label_gizi' => 'required|integer|in:0,1,2,3',
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new DataAnakImport, $request->file('file'));

        return back()->with('success', 'File berhasil diimpor.');
    }


    public function getData()
    {
        $data = DataAnak::select(
            'nama as Nama',
            'jk as JK',
            'usia_ukur as Usia Saat Ukur',
            'berat as Berat',
            'tinggi as Tinggi',
            'zs_bb_u as ZS BB/U',
            'zs_tb_u as ZS TB/U',
            'zs_bb_tb as ZS BB/TB',
            'bb_tb as BB/TB'
        )
            ->whereNotNull('bb_tb') // hanya ambil data yang sudah ada label klasifikasi
            ->get();

        return response()->json($data);
    }
}
