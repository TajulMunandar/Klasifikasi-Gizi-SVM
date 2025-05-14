@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-12">
            <div class="card">
                <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-users text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                1600
                            </h5>
                            <span class="text-white text-sm">Users</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12 mt-4 mt-md-0">
            <div class="card">
                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-arrow-up text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                357
                            </h5>
                            <span class="text-white text-sm">Gizi Baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12 mt-4 mt-md-0">
            <div class="card">
                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-arrow-down text-dark text-gradient text-lg opacity-10"
                                    aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                357
                            </h5>
                            <span class="text-white text-sm">Gizi Kurang Baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('dashboard') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="desa" class="form-label">Pilih Desa</label>
                <select name="desa" id="desa" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Desa --</option>
                    @foreach ($daftarDesa as $desa)
                        <option value="{{ $desa }}" {{ request('desa') == $desa ? 'selected' : '' }}>
                            {{ $desa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Hasil Klasifikasi</h5>
            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                    <tr>
                        <th>Nama Anak</th>
                        <th>Klasifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilKlasifikasi as $item)
                        <tr>
                            <td>{{ $item->dataAnak->nama }}</td>
                            <td><strong>{{ $item->klasifikasi }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
