@extends('dashboard.layouts.main')

@section('content')
    <div class="card">
        <div class="row pt-3 pe-3">
            <div class="col">
                <!-- Tombol Tambah -->
                <button id="run-training-btn" class="btn btn-primary float-end">
                    <i class="fa fa-plus me-2"></i>Klasifikasi Baru
                </button>
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

            // Handler tombol Klasifikasi Baru
            $('#run-training-btn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Proses...');

                fetch('http://localhost:5000/run-training')
                    .then(response => {
                        return response.json().then(data => {
                            if (response.ok) {
                                showAlert('success', data.message ||
                                    'Training berhasil dijalankan.');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showAlert('danger', data.message ||
                                    'Terjadi kesalahan saat training.');
                            }
                            console.log('Parsed response:', data);
                        }).catch(e => {
                            console.error('Gagal parsing JSON:', e);
                            showAlert('danger',
                            'Respons dari server tidak valid (bukan JSON).');
                        });
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        showAlert('danger', 'Tidak dapat terhubung ke server Flask.');
                    })
                    .finally(() => {
                        btn.prop('disabled', false).html(
                            '<i class="fa fa-plus me-2"></i>Klasifikasi Baru');
                    });
            });


            // Fungsi untuk menampilkan alert dinamis
            function showAlert(type, message) {
                const alert = `
                <div class="alert alert-${type} alert-dismissible fade show mt-2" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('.card').before(alert);
            }
        });
    </script>
@endpush
