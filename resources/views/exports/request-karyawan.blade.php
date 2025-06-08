<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permohonan Karyawan</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Permohonan Izin Keluar Karyawan</h1>
        <p>Periode: {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</p>
    </div>

    <div class="info-box">
        <p>Total Data: {{ count($data) }}</p>
        <p>Tipe Data: {{ $type === 'all' ? 'Semua' : $type }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Surat</th>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Departemen</th>
                <th>Keperluan</th>
                <th>Tanggal</th>
                <th>Jam Keluar</th>
                <th>Jam Kembali</th>
                <th>Status</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($data as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['no_surat'] ?? '-' }}</td>
                <td>{{ $item['nama'] ?? '-' }}</td>
                <td>{{ $item['no_telp'] ?? '-' }}</td>
                <td>{{ $item['departemen'] ?? '-' }}</td>
                <td>{{ $item['keperluan'] ?? '-' }}</td>
                <td>{{ $item['tanggal'] ?? '-' }}</td>
                <td>{{ $item['jam_out'] ?? '-' }}</td>
                <td>{{ $item['jam_in'] ?? '-' }}</td>
                <td>
                    <span class="status-badge status-{{ $item['status'] ?? 'warning' }}">
                        {{ $item['text'] ?? 'Menunggu' }}
                    </span>
                </td>
                <td>{{ $item['tipe'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>&copy; {{ date('Y') }} SISIK - Sistem Surat Izin Keluar</p>
    </div>
</body>
</html> 