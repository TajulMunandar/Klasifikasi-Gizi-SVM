@extends('dashboard.layouts.main')

@section('content')
    <h2>Laporan Klasifikasi Gizi Per Desa</h2>

    <table id="myTable" class="table table-striped">
        <thead>
            <tr>
                <th>Desa</th>
                <th>Total</th>
                <th>Gizi Baik</th>
                <th>Gizi Kurang</th>
                <th>Gizi Buruk</th>
                <th>Risiko Gizi Lebih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['desa'] }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td>{{ $row['Gizi Baik'] }}</td>
                    <td>{{ $row['Gizi Kurang'] }}</td>
                    <td>{{ $row['Gizi Buruk'] }}</td>
                    <td>{{ $row['Risiko Gizi Lebih'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('js')
    <script>
        // Otomatis cetak saat halaman dibuka
        window.onload = function() {
            window.print();
        };

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
