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
                <button class="btn btn-success float-end me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
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
                    @foreach ($anaks as $anak)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $anak->nama }}</td>
                            <td>{{ $anak->nik }}</td>
                            <td>{{ $anak->jk }}</td>
                            <td>{{ $anak->tanggal_lahir }}</td>
                            <td>{{ $anak->nama_ortu }}</td>
                            <td>{{ $anak->prov }}</td>
                            <td>{{ $anak->kab }}</td>
                            <td>{{ $anak->kec }}</td>
                            <td>{{ $anak->desa }}</td>
                            <td>{{ $anak->puskesmas }}</td>
                            <td>{{ $anak->posyandu }}</td>
                            <td>{{ $anak->alamat }}</td>
                            <td>{{ $anak->usia_ukur }}</td>
                            <td>{{ $anak->tgl_pengukuran }}</td>
                            <td>{{ $anak->berat }}</td>
                            <td>{{ $anak->cara_ukur }}</td>
                            <td>{{ $anak->lila }}</td>
                            <td>{{ $anak->bb_u }}</td>
                            <td>{{ $anak->tb_u }}</td>
                            <td>{{ $anak->bb_tb }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $anak->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $anak->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    @foreach ($anaks as $anak)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit{{ $anak->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('data-anak.update', $anak->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Anak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <input name="nama" value="{{ $anak->nama }}" class="form-control mb-2" placeholder="Nama"
                                required>
                            <input name="nik" value="{{ $anak->nik }}" class="form-control mb-2" placeholder="NIK"
                                required>
                            <select name="jk" class="form-control mb-2">
                                <option value="L" {{ $anak->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $anak->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <input type="date" name="tanggal_lahir" value="{{ $anak->tanggal_lahir }}"
                                class="form-control mb-2" required>
                            <input name="nama_ortu" value="{{ $anak->nama_ortu }}" class="form-control mb-2"
                                placeholder="Nama Orang Tua" required>
                            <input name="prov" value="{{ $anak->prov }}" class="form-control mb-2"
                                placeholder="Provinsi" required>
                            <input name="kab" value="{{ $anak->kab }}" class="form-control mb-2"
                                placeholder="Kabupaten" required>
                            <input name="kec" value="{{ $anak->kec }}" class="form-control mb-2"
                                placeholder="Kecamatan" required>
                        </div>
                        <div class="col-md-6">
                            <input name="desa" value="{{ $anak->desa }}" class="form-control mb-2" placeholder="Desa"
                                required>
                            <input name="puskesmas" value="{{ $anak->puskesmas }}" class="form-control mb-2"
                                placeholder="Puskesmas" required>
                            <input name="posyandu" value="{{ $anak->posyandu }}" class="form-control mb-2"
                                placeholder="Posyandu" required>
                            <input name="alamat" value="{{ $anak->alamat }}" class="form-control mb-2"
                                placeholder="Alamat" required>
                            <input type="number" name="usia_ukur" value="{{ $anak->usia_ukur }}" class="form-control mb-2"
                                placeholder="Usia Ukur" required>
                            <input type="date" name="tgl_pengukuran" value="{{ $anak->tgl_pengukuran }}"
                                class="form-control mb-2" required>
                            <input name="berat" value="{{ $anak->berat }}" class="form-control mb-2"
                                placeholder="Berat Badan" required>
                            <input name="cara_ukur" value="{{ $anak->cara_ukur }}" class="form-control mb-2"
                                placeholder="Cara Ukur" required>
                            <input name="lila" value="{{ $anak->lila }}" class="form-control mb-2"
                                placeholder="LILA" required>
                            <input name="bb_u" value="{{ $anak->bb_u }}" class="form-control mb-2"
                                placeholder="BB/U" required>
                            <input name="tb_u" value="{{ $anak->tb_u }}" class="form-control mb-2"
                                placeholder="TB/U" required>
                            <input name="bb_tb" value="{{ $anak->bb_tb }}" class="form-control mb-2"
                                placeholder="BB/TB" required>
                            <select name="label_gizi" class="form-control mb-2" required>
                                <option value="1" {{ $anak->label_gizi == 1 ? 'selected' : '' }}>Gizi Baik</option>
                                <option value="0" {{ $anak->label_gizi == 0 ? 'selected' : '' }}>Gizi Buruk</option>
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
        <div class="modal fade" id="modalDelete{{ $anak->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('data-anak.destroy', $anak->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Data Anak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus data anak atas nama <strong>{{ $anak->nama }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach


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
                        <option value="1">Gizi Baik</option>
                        <option value="0">Gizi Buruk</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
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
                "scrollX": true,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
