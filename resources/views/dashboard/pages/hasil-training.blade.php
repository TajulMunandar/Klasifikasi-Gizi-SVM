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
                <a href="{{ route('hasil-training.create') }}" class="btn btn-primary float-end">
                    <i class="fa fa-plus me-2"></i>Klasifikasi Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anak</th>
                        <th>Klasifikasi</th>
                        <th>F1 Score</th>
                        <th>Accuracy</th>
                        <th>Probabilitas</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->dataAnak->nama ?? '-' }}</td>
                            <td>{{ $item->klasifikasi }}</td>
                            <td>{{ $item->f1_score }}</td>
                            <td>{{ $item->accuracy }}</td>
                            <td>{{ $item->probabilitas }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
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

            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
