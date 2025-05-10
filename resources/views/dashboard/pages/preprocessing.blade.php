@extends('dashboard.layouts.main')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="row pt-3 pe-3">
            <div class="col">
                <!-- Tombol Tambah -->
                <a href="{{ route('preprocessing.create') }}" class="btn btn-primary float-end">
                    <i class="fa fa-plus me-2"></i>Preprocessing
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Usia (bulan)</th>
                        <th>Berat (kg)</th>
                        <th>Tinggi (cm)</th>
                        <th>Z-Score BB/U</th>
                        <th>Z-Score TB/U</th>
                        <th>Z-Score BB/TB</th>
                        <th>Status Gizi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preprocessings as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $item->usia_bulan }}</td>
                            <td>{{ $item->berat }}</td>
                            <td>{{ $item->tinggi }}</td>
                            <td>{{ $item->zs_bb_u }}</td>
                            <td>{{ $item->zs_tb_u }}</td>
                            <td>{{ $item->zs_bb_tb }}</td>
                            <td>{{ $item->label_gizi == 0 ? 'Gizi Baik' : 'Kurang Gizi' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari...",
                    "decimal": ",",
                    "thousands": "."
                },
                "scrollX": true,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
