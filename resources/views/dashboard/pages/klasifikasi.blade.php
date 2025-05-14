@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card mb-2">
            <div class="card-body">

                <form method="POST">
                    @csrf
                    <div class="row mb-3">
                        <label for="usia_bulan" class="col-sm-2 col-form-label">Usia (Bulan)</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="usia_bulan" name="usia_bulan" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="berat" class="col-sm-12 col-form-label">Berat (kg)</label>
                        <div class="col-sm-12">
                            <input type="number" step="0.01" class="form-control" id="berat" name="berat"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tinggi" class="col-sm-12 col-form-label">Tinggi (cm)</label>
                        <div class="col-sm-12">
                            <input type="number" step="0.01" class="form-control" id="tinggi" name="tinggi"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="zs_bbu" class="col-sm-12 col-form-label">ZS BB/U</label>
                        <div class="col-sm-12">
                            <input type="number" step="0.01" class="form-control" id="zs_bbu" name="zs_bbu"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="zs_tbu" class="col-sm-12 col-form-label">ZS TB/U</label>
                        <div class="col-sm-12">
                            <input type="number" step="0.01" class="form-control" id="zs_tbu" name="zs_tbu"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="zs_bb_tb" class="col-sm-12 col-form-label">ZS BB/TB</label>
                        <div class="col-sm-12">
                            <input type="number" step="0.01" class="form-control" id="zs_bb_tb" name="zs_bb_tb"
                                required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="jenis_kelamin" class="col-sm-12 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-12">
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary">Klasifikasi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel hasil klasifikasi --}}
        <div class="card">
            <div class="card-body">
                <h5>Hasil Klasifikasi</h5>
                <table class="table table-bordered table-striped" id="myTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Klasifikasi</th>
                            <th>Probabilitas</th>
                            <th>F1 Score</th>
                            <th>Akurasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($hasilKlasifikasi as $item)
                            <tr>
                                <td>{{ $item->dataAnak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td><strong>{{ $item->klasifikasi }}</strong></td>
                                <td>{{ $item->probabilitas }}</td>
                                <td>{{ $item->f1_score }}</td>
                                <td>{{ $item->accuracy }}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection


@push('js')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
                "scrollX": false,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
