<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin Keluar Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 12px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .field {
            display: flex;
            margin-bottom: 5px;
        }
        .field label {
            width: 120px; /* Lebar label */
            flex-shrink: 0;
        }
        .field .separator {
            width: 10px;
            text-align: center;
        }
        .field .value {
            flex-grow: 1;
            border-bottom: 1px solid black;
            padding-bottom: 2px;
        }
        .signature-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 50px;
            width: 250px;
            text-align: center;
        }
        .signature-text {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 20px;">Detail Permohonan Izin Keluar Karyawan</h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 30%; padding: 5px;"><strong>No. Surat</strong></td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%; padding: 5px;">{{ $data['no_surat'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Nama Karyawan</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['nama'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>No. Telp</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['no_telp'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Departemen</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['departemen'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Keperluan</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['keperluan'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Tanggal Pengajuan</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Jam Keluar</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['jam_out'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Jam Kembali</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['jam_in'] ?? '-' }}</td>
            </tr>
        </table>

        <h3 style="margin-top: 30px; margin-bottom: 15px; text-align: center;">Status Persetujuan</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 30%; padding: 5px;"><strong>Persetujuan Lead</strong></td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%; padding: 5px;">{{ $data['status_lead'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Persetujuan HR/GA</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['status_hr_ga'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Status Keluar</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['status_security_out'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Status Kembali</strong></td>
                <td style="width: 5%;">:</td>
                <td style="padding: 5px;">{{ $data['status_security_in'] ?? '-' }}</td>
            </tr>
        </table>
    </div>
</body>
</html> 