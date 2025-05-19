<?php

namespace App\Http\Controllers;

use App\Models\DataAnak;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
                'desa',
                'puskesmas',
                'posyandu',
                'alamat',
                'usia_ukur',
                'tgl_pengukuran',
                'berat',
                'cara_ukur',
                'lila',
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
            data-desa="' . e($row->desa) . '"
            data-puskesmas="' . e($row->puskesmas) . '"
            data-posyandu="' . e($row->posyandu) . '"
            data-alamat="' . e($row->alamat) . '"
            data-usia_ukur="' . e($row->usia_ukur) . '"
            data-tgl_pengukuran="' . e($row->tgl_pengukuran) . '"
            data-berat="' . e($row->berat) . '"
            data-cara_ukur="' . e($row->cara_ukur) . '"
            data-lila="' . e($row->lila) . '"
            data-bb_u="' . e($row->bb_u) . '"
            data-tb_u="' . e($row->tb_u) . '"
            data-bb_tb="' . e($row->bb_tb) . '"
            data-label_gizi="' . (isset($row->label_gizi) ? e($row->label_gizi) : '') . '">
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data); // Buang header

        foreach ($data as $row) {
            if (count($row) < 32) continue; // Validasi jumlah kolom minimal

            // Parsing tanggal lahir dan tanggal pengukuran
            $tanggalLahir = Carbon::createFromFormat('n/j/Y', trim($row[4]))->format('Y-m-d');
            $tanggalUkur  = Carbon::createFromFormat('n/j/Y', trim($row[18]))->format('Y-m-d');

            // Mapping ke database
            DataAnak::create([
                'nik'            => trim($row[1]),
                'nama'           => trim($row[2]),
                'jk'             => trim($row[3]),
                'tanggal_lahir'  => $tanggalLahir,
                'nama_ortu'      => trim($row[7]),
                'prov'           => trim($row[8]),
                'kab'            => trim($row[9]),
                'kec'            => trim($row[10]),
                'puskesmas'      => trim($row[11]),
                'desa'           => trim($row[12]),
                'posyandu'       => trim($row[13]),
                'alamat'         => trim($row[16]),
                'usia_ukur'      => trim($row[17]),
                'tgl_pengukuran' => $tanggalUkur,
                'berat'          => floatval($row[19]),
                'cara_ukur'      => trim($row[21]),
                'lila'           => floatval($row[22]),
                'bb_u'           => trim($row[23]),
                'tb_u'           => trim($row[25]),
                'bb_tb'          => trim($row[27]),
                'label_gizi'     => $this->klasifikasiGizi($row[27]),
            ]);
        }

        return back()->with('success', 'File berhasil diimpor.');
    }

    private function klasifikasiGizi($kategori)
    {
        return match (strtolower(trim($kategori))) {
            'gizi buruk' => 0,
            'gizi kurang' => 1,
            'gizi baik' => 2,
            'Risiko Gizi Lebih', 'gizi lebih' => 3,
            'obesitas' => 4,
            default => 2, // default: gizi baik
        };
    }
}
