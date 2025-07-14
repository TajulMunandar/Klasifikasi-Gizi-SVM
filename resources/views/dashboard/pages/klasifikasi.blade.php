@extends('dashboard.layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card mb-2">
            <div class="card-body">

                <form method="POST" id="formKlasifikasi">
                    @csrf

                    <div class="row mb-3">
                        <label for="nama_anak" class="col-sm-12 col-form-label">Nama Anak</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nama_anak" name="nama_anak" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="nama_ortu" class="col-sm-12 col-form-label">Nama Orang Tua</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kampung" class="col-sm-12 col-form-label">Kampung</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="kampung" name="kampung" required>
                        </div>
                    </div>
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
                            <th>Nama Anak</th>
                            <th>Nama Orang Tua</th>
                            <th>Kampung</th>
                            <th>Jenis Kelamin</th>
                            <th>Klasifikasi</th>
                            <th>Probabilitas</th>
                        </tr>
                    </thead>
                    <tbody>
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

            $('#formKlasifikasi').on('submit', function(e) {
                e.preventDefault(); // Cegah reload

                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).text('Memproses...');

                const data = {
                    jenis_kelamin: $('#jenis_kelamin').val() === 'L' ? 1 : 0,
                    usia_bulan: parseInt($('#usia_bulan').val()),
                    berat: parseFloat($('#berat').val()),
                    tinggi: parseFloat($('#tinggi').val()),
                    zs_bb_u: parseFloat($('#zs_bbu').val()),
                    zs_tb_u: parseFloat($('#zs_tbu').val()),
                    zs_bb_tb: parseFloat($('#zs_bb_tb').val()),
                };

                fetch('http://localhost:5000/predict', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(response => {
                        btn.prop('disabled', false).text('Klasifikasi');
                        if (response.error) {
                            alert("Gagal klasifikasi: " + response.error);
                            return;
                        }

                        // Masukkan ke tabel hasil
                        const newRow = `
               <tr>
    <td>${$('#nama_anak').val()}</td>
    <td>${$('#nama_ortu').val()}</td>
    <td>${$('#kampung').val()}</td>
    <td>${$('#jenis_kelamin').val() === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
    <td><strong>${response.prediksi}</strong></td>
    <td>${response.probabilitas}</td>
</tr>
            `;
                        $('#myTable tbody').append(newRow);
                        $('html, body').animate({
                            scrollTop: $('#myTable').offset().top
                        }, 600);
                    })
                    .catch(error => {
                        btn.prop('disabled', false).text('Klasifikasi');
                        alert("Terjadi kesalahan: " + error);
                    });
            });
        });
    </script>
@endpush
