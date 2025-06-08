<!DOCTYPE html>
<html>
<head>
    <title>Laporan Permohonan Driver</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Permohonan Izin Keluar Driver</h1>
        @if($type === 'filtered')
            <p>Bulan: {{ DateTime::createFromFormat('!m', $month)->format('F') }} Tahun: {{ $year }}</p>
        @else
            <p>Semua Data</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Surat</th>
                <th>Nama Ekspedisi</th>
                <th>No. Polisi</th>
                <th>Nama Driver</th>
                <th>No. HP Driver</th>
                <th>Tanggal</th>
                <th>Jam Keluar</th>
                <th>Jam Kembali</th>
                <th>Keperluan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['no_surat'] }}</td>
                <td>{{ $item['nama_ekspedisi'] }}</td>
                <td>{{ $item['nopol_kendaraan'] }}</td>
                <td>{{ $item['nama_driver'] }}</td>
                <td>{{ $item['no_hp_driver'] }}</td>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['jam_out'] }}</td>
                <td>{{ $item['jam_in'] }}</td>
                <td>{{ $item['keperluan'] }}</td>
                <td>{{ $item['status_text'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 