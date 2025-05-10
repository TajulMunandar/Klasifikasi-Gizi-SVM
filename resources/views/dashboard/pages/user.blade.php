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
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @if ($user->is_admin == 1)
                                    Admin
                                @else
                                    User
                                @endif
                            </td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $user->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalDelete{{ $user->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($users as $user)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('user.update', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input name="name" value="{{ $user->name }}" class="form-control mb-2" required>
                        <input name="username" value="{{ $user->username }}" class="form-control mb-2" required>
                        <select name="is_admin" class="form-control">
                            <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Admin
                            </option>
                            <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>User
                            </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="modalDelete{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus <strong>{{ $user->name }}</strong>?</p>
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
            <form action="{{ route('user.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input name="name" class="form-control mb-2" placeholder="Nama" required>
                    <input name="username" class="form-control mb-2" placeholder="username" required>
                    <input name="password" class="form-control mb-2" type="password" placeholder="Password" required>
                    <select name="is_admin" class="form-control">
                        <option value="1">Admin</option>
                        <option value="0">User</option>
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
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
