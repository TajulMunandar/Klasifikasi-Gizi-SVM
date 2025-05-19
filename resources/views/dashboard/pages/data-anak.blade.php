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
                <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fa fa-plus me-2"></i>Tambah
                </button>
                <button class="btn btn-success float-end me-2" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="fa fa-download me-2"></i>Import
                </button>
            </div>
        </div>


        <div class="card-body">
            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Nama Orang Tua</th>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Puskesmas</th>
                        <th>Posyandu</th>
                        <th>Alamat</th>
                        <th>Usia Ukur</th>
                        <th>Tanggal Pengukuran</th>
                        <th>Berat Badan</th>
                        <th>Cara Ukur</th>
                        <th>LILA</th>
                        <th>BB/U</th>
                        <th>TB/U</th>
                        <th>BB/TB</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formEdit" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Anak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6">
                        <input name="nama" class="form-control mb-2" placeholder="Nama" required>
                        <input name="nik" class="form-control mb-2" placeholder="NIK" required>
                        <select name="jk" class="form-control mb-2">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <input type="date" name="tanggal_lahir" class="form-control mb-2" required>
                        <input name="nama_ortu" class="form-control mb-2" placeholder="Nama Orang Tua" required>
                        <input name="prov" class="form-control mb-2" placeholder="Provinsi" required>
                        <input name="kab" class="form-control mb-2" placeholder="Kabupaten" required>
                        <input name="kec" class="form-control mb-2" placeholder="Kecamatan" required>
                    </div>
                    <div class="col-md-6">
                        <input name="desa" class="form-control mb-2" placeholder="Desa" required>
                        <input name="puskesmas" class="form-control mb-2" placeholder="Puskesmas" required>
                        <input name="posyandu" class="form-control mb-2" placeholder="Posyandu" required>
                        <input name="alamat" class="form-control mb-2" placeholder="Alamat" required>
                        <input type="text" name="usia_ukur" class="form-control mb-2" placeholder="Usia Ukur" required>
                        <input type="date" name="tgl_pengukuran" class="form-control mb-2" required>
                        <input name="berat" class="form-control mb-2" placeholder="Berat Badan" required>
                        <input name="cara_ukur" class="form-control mb-2" placeholder="Cara Ukur" required>
                        <input name="lila" class="form-control mb-2" placeholder="LILA" required>
                        <input name="bb_u" class="form-control mb-2" placeholder="BB/U" required>
                        <input name="tb_u" class="form-control mb-2" placeholder="TB/U" required>
                        <input name="bb_tb" class="form-control mb-2" placeholder="BB/TB" required>
                        <select name="label_gizi" class="form-control mb-2" required>
                            <option value="1">Gizi Kurang</option>
                            <option value="0">Gizi Buruk</option>
                            <option value="2">Gizi Baik</option>
                            <option value="3">Risiko Gizi Lebih</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Hapus -->
    <div class="modal fade" id="modalDelete" tabindex="-1">
        <div class="modal-dialog">
            <form id="formDelete" method="POST" class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Anak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus data anak atas nama <strong id="deleteNama"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('data-anak.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Anak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input name="nama" class="form-control mb-2" placeholder="Nama Anak" required>
                    <input name="nik" class="form-control mb-2" placeholder="NIK" required>
                    <select name="jk" class="form-control mb-2" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <input type="date" name="tanggal_lahir" class="form-control mb-2" required>
                    <input name="nama_ortu" class="form-control mb-2" placeholder="Nama Orang Tua" required>
                    <input name="prov" class="form-control mb-2" placeholder="Provinsi" required>
                    <input name="kab" class="form-control mb-2" placeholder="Kabupaten" required>
                    <input name="kec" class="form-control mb-2" placeholder="Kecamatan" required>
                    <input name="desa" class="form-control mb-2" placeholder="Desa" required>
                    <input name="puskesmas" class="form-control mb-2" placeholder="Puskesmas" required>
                    <input name="posyandu" class="form-control mb-2" placeholder="Posyandu" required>
                    <input name="alamat" class="form-control mb-2" placeholder="Alamat" required>
                    <input type="number" name="usia_ukur" class="form-control mb-2" placeholder="Usia Ukur (bulan)"
                        required>
                    <input type="date" name="tgl_pengukuran" class="form-control mb-2" required>
                    <input type="number" step="0.01" name="berat" class="form-control mb-2"
                        placeholder="Berat Badan (kg)" required>
                    <input name="cara_ukur" class="form-control mb-2" placeholder="Cara Ukur" required>
                    <input type="number" step="0.01" name="lila" class="form-control mb-2"
                        placeholder="LILA (cm)" required>
                    <input name="bb_u" class="form-control mb-2" placeholder="BB/U" required>
                    <input name="tb_u" class="form-control mb-2" placeholder="TB/U" required>
                    <input name="bb_tb" class="form-control mb-2" placeholder="BB/TB" required>
                    <select name="label_gizi" class="form-control mb-2" required>
                        <option value="1">Gizi Kurang</option>
                        <option value="0">Gizi Buruk</option>
                        <option value="2">Gizi Baik</option>
                        <option value="3">Risiko Gizi Lebih</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportLabel">Import File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data-anak.index') }}",
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    decimal: ",",
                    thousands: ".",
                },
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
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'jk',
                        name: 'jk'
                    },
                    {
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir'
                    },
                    {
                        data: 'nama_ortu',
                        name: 'nama_ortu'
                    },
                    {
                        data: 'prov',
                        name: 'prov'
                    },
                    {
                        data: 'kab',
                        name: 'kab'
                    },
                    {
                        data: 'kec',
                        name: 'kec'
                    },
                    {
                        data: 'desa',
                        name: 'desa'
                    },
                    {
                        data: 'puskesmas',
                        name: 'puskesmas'
                    },
                    {
                        data: 'posyandu',
                        name: 'posyandu'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'usia_ukur',
                        name: 'usia_ukur'
                    },
                    {
                        data: 'tgl_pengukuran',
                        name: 'tgl_pengukuran'
                    },
                    {
                        data: 'berat',
                        name: 'berat'
                    },
                    {
                        data: 'cara_ukur',
                        name: 'cara_ukur'
                    },
                    {
                        data: 'lila',
                        name: 'lila'
                    },
                    {
                        data: 'bb_u',
                        name: 'bb_u'
                    },
                    {
                        data: 'tb_u',
                        name: 'tb_u'
                    },
                    {
                        data: 'bb_tb',
                        name: 'bb_tb'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        let currentEditData = null;

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-edit')) {
                currentEditData = e.target.dataset;
                const modal = document.getElementById('modalEdit');
                const form = modal.querySelector('form');
                // Update action URL segera
                form.action = `/dashboard/data-anak/${currentEditData.id}`;
                // Jangan isi form di sini
            }
        });

        // Isi form setelah modal muncul
        const modalEdit = document.getElementById('modalEdit');
        modalEdit.addEventListener('shown.bs.modal', function() {
            if (!currentEditData) return;
            const form = modalEdit.querySelector('form');
            const fields = form.querySelectorAll('[name]');

            fields.forEach(field => {
                if (field.name in currentEditData) {
                    if (field.tagName === 'SELECT') {
                        field.value = String(currentEditData[field.name]);
                    } else {
                        field.value = currentEditData[field.name];
                    }
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete')) {
                const data = e.target.dataset;
                const form = document.getElementById('formDelete');
                const text = document.getElementById('deleteNama');

                form.action = `/dashboard/data-anak/${data.id}`;
                text.textContent = data.nama;
            }
        });
    </script>
@endpush
