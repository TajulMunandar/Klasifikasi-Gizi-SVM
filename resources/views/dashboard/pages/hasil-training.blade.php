@extends('dashboard.layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <h6>Akurasi Keseluruhan: <span id="overall-accuracy" class="badge bg-success">-</span></h6>
            </div>
            <div class="row">
                <div class="col col-6">
                    <div id="classification-report-container" class="mt-4" style="display:none;">
                        <h5>Classification Report</h5>
                        <div class="table-responsive">
                            <table id="classification-report" class="table table-bordered table-sm">
                                <!-- Akan diisi dengan JS -->
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col col-6">
                    <div id="confusion-matrix-container" class="mt-4" style="display:none;">
                        <h5>Confusion Matrix</h5>
                        <div class="table-responsive">
                            <table id="confusion-matrix" class="table table-bordered table-sm">
                                <!-- Will be populated via JavaScript -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
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

            function renderClassificationReport(report) {
                const labels = ["Gizi Baik", "Gizi Kurang", "Gizi Buruk", "Risiko Gizi Lebih"];
                const tbody = labels.map(label => {
                    const row = report[label];
                    return `
            <tr>
                <td>${label}</td>
                <td>${(row.precision * 100).toFixed(2)}%</td>
                <td>${(row.recall * 100).toFixed(2)}%</td>
                <td>${(row["f1-score"] * 100).toFixed(2)}%</td>
            </tr>
        `;
                }).join("");

                const html = `
        <thead>
            <tr>
                <th>Label</th>
                <th>Precision</th>
                <th>Recall</th>
                <th>F1-Score</th>

            </tr>
        </thead>
        <tbody>
            ${tbody}
        </tbody>
    `;
                $('#classification-report').html(html);
                $('#classification-report-container').show();
            }


            function renderConfusionMatrix(matrix) {
                const labels = ["Gizi Baik", "Gizi Kurang", "Gizi Buruk", "Risiko Gizi Lebih"];
                const container = $('#confusion-matrix');
                container.empty();

                const headers = labels.map(label => `<th>${label}</th>`).join('');
                container.append(`<thead><tr><th></th>${headers}</tr></thead>`);

                const rows = matrix.map((row, i) => {
                    const cols = row.map(val => `<td>${val}</td>`).join('');
                    return `<tr><th>${labels[i]}</th>${cols}</tr>`;
                });

                container.append(`<tbody>${rows.join('')}</tbody>`);
                $('#confusion-matrix-container').show();
            }


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

                                console.log(data.confusion_matrix);
                                if (data.confusion_matrix) {
                                    renderConfusionMatrix(data.confusion_matrix);
                                }
                                if (data.evaluasi) {
                                    renderClassificationReport(data.evaluasi);
                                }
                                if (data.accuracy !== undefined) {
                                    const acc = (data.accuracy * 100).toFixed(2) + "%";
                                    $("#overall-accuracy").text(acc);
                                }
                                // setTimeout(() => location.reload(), 3000);
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
