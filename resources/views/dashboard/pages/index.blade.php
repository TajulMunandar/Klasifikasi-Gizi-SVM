@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-12">
            <div class="card">

                <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-users text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                {{ $hasilKlasifikasi->count() }}
                            </h5>
                            <span class="text-white text-sm">Anak</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
            <div class="card">
                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-arrow-up text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                {{ $jumlahGiziBaik }}
                            </h5>
                            <span class="text-white text-sm">Gizi Baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
            <div class="card">
                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa fa-arrow-up text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                {{ $RisikoGiziLebih }}
                            </h5>
                            <span class="text-white text-sm">Resiko Gizi Lebih</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
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
                                {{ $jumlahGiziKurangBaik }}
                            </h5>
                            <span class="text-white text-sm">Gizi Kurang Baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-3">
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
                                {{ $jumlahGiziBuruk }}
                            </h5>
                            <span class="text-white text-sm">Gizi Buruk</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('dashboard') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
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
            <div class="row">
                <div class="col">
                    <h5>Hasil Klasifikasi</h5>
                </div>
                <div class="col">
                    <a href="{{ route('laporan') }}" class="btn btn-primary"> Laporan</a>
                </div>
            </div>
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

    <div class="card mt-3">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <label for="anak" class="form-label">Pilih Anak:</label>
                <select name="anak" id="anak" onchange="this.form.submit()" class="form-select">
                    <option value="">-- Pilih Anak --</option>
                    @foreach ($daftarAnak as $anak)
                        <option value="{{ $anak->id }}" {{ request('anak') == $anak->id ? 'selected' : '' }}>
                            {{ $anak->nama }} - {{ $anak->nik }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if (!$dataGrafik->isEmpty())
                <canvas id="klasifikasiChart"></canvas>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script>
        const ctx = document.getElementById('klasifikasiChart').getContext('2d');

        const rawData = {!! json_encode($dataGrafik) !!};

        // Mapping teks ke angka untuk ditampilkan di chart
        const klasifikasiMap = {
            'Gizi Buruk': 1,
            'Gizi Kurang': 2,
            'Gizi Baik': 3,
            'Risiko Gizi Lebih': 4
        };

        const labels = rawData.map((_, index) => `Kunjungan ke-${index + 1}`);
        const data = rawData.map(item => klasifikasiMap[item.klasifikasi] ?? null);

        const klasifikasiChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Riwayat Klasifikasi Gizi',
                    data: data,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'blue',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = Object.keys(klasifikasiMap).find(key => klasifikasiMap[key] ===
                                    context.raw);
                                return label ?? context.raw;
                            }
                        }
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 1,
                        max: 4,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Object.keys(klasifikasiMap).find(key => klasifikasiMap[key] === value) ??
                                    value;
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });
    </script>
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
