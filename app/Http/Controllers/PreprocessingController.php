<?php

namespace App\Http\Controllers;

use App\Models\Preprocessing;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PreprocessingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Preprocessing';

        if ($request->ajax()) {
            $data = Preprocessing::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', fn($row) => $row->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan')
                ->editColumn('label_gizi', function ($row) {
                    return match ($row->label_gizi) {
                        0 => 'Gizi Baik',
                        1 => 'Gizi Kurang',
                        2 => 'Gizi Buruk',
                        3 => 'Risiko Gizi Lebih',
                        default => 'Tidak Diketahui',
                    };
                })
                ->make(true);
        }

        return view('dashboard.pages.preprocessing', compact('page'));
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
        foreach ($request->all() as $item) {
            Preprocessing::create($item);
        }

        return response()->json(['message' => 'Data preprocessing berhasil disimpan'], 201);
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
}
