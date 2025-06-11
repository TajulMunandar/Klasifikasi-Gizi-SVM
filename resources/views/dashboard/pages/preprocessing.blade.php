@extends('dashboard.layouts.main')

@section('content')
    <div class="card">
        <div class="row pt-3 pe-3">
            <div class="col">
                <!-- Tombol Tambah -->
                <a id="btn-preprocessing" class="btn btn-primary float-end">
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

                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('preprocessing.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'usia_bulan',
                        name: 'usia_bulan'
                    },
                    {
                        data: 'berat',
                        name: 'berat'
                    },
                    {
                        data: 'tinggi',
                        name: 'tinggi'
                    },
                    {
                        data: 'zs_bb_u',
                        name: 'zs_bb_u'
                    },
                    {
                        data: 'zs_tb_u',
                        name: 'zs_tb_u'
                    },
                    {
                        data: 'zs_bb_tb',
                        name: 'zs_bb_tb'
                    },
                    {
                        data: 'label_gizi',
                        name: 'label_gizi'
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    decimal: ",",
                    thousands: "."
                },
                scrollX: true,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
            $('#btn-preprocessing').on('click', function() {
                $(this).prop('disabled', true).text('Memproses...');

                fetch('http://localhost:5000/run-preprocessing')
                    .then(async response => {
                        const text = await response.text();

                        try {
                            const data = JSON.parse(text);

                            if (response.ok) {
                                showAlert('success', data.message ||
                                    'Preprocessing berhasil dijalankan.');
                            } else {
                                showAlert('danger', data.message ||
                                    'Terjadi kesalahan saat preprocessing.');
                            }

                            console.log('Parsed response:', data);
                        } catch (e) {
                            console.error('Gagal parsing JSON:', e);
                            console.error('Teks asli respons:', text);
                            showAlert('danger', 'Respons dari server tidak valid (bukan JSON).');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        showAlert('danger', 'Tidak dapat terhubung ke server Flask.');
                    })
                    .finally(() => {
                        $('#btn-preprocessing').prop('disabled', false).html(
                            '<i class="fa fa-plus me-2"></i>Preprocessing');
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
