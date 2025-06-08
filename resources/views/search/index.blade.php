<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.auth.head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        input[type="time"]::-webkit-datetime-edit-ampm-field {
            display: none;
        }
        .form-control {
            font-size: 14px;
            border-color: #ebedf2;
            padding: .6rem 1rem;
            height: inherit !important;
        }
        .form-control:focus {
            border-color: #3e93ff;
        }
        .text-muted {
            font-size: 13px;
            color: #6c757d !important;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: .5rem;
        }
        .invalid-feedback {
            font-size: 80%;
            color: #F25961;
        }
        /* Style untuk floating alert */
        .floating-alert {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 350px;
            max-width: 500px;
        }
        .floating-alert .alert {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            animation: slideIn 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }
        .floating-alert .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        .floating-alert .alert-success {
            background-color: #f0fdf4;
            color: #166534;
        }
        .floating-alert .alert-success::before {
            background-color: #22c55e;
        }
        .floating-alert .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }
        .floating-alert .alert-danger::before {
            background-color: #ef4444;
        }
        .floating-alert .alert i {
            font-size: 1.25rem;
        }
        .floating-alert .alert-success i {
            color: #22c55e;
        }
        .floating-alert .alert-danger i {
            color: #ef4444;
        }
        .floating-alert .alert .btn-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        .floating-alert .alert .btn-close:hover {
            opacity: 1;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .floating-alert .alert.fade {
            animation: fadeOut 0.3s ease-out;
        }
        .table {
            font-size: 14px;
        }
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-info {
            background-color: #0dcaf0;
            color: #212529;
        }
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .badge-success {
            background-color: #198754;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('layout.auth.navbar')

    <!-- Alert Section -->
    @include('layout.superadmin.alert')

    <!-- Search Section -->
    <section class="min-vh-100 d-flex align-items-center bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 text-primary mb-2">
                                    <i class="bi bi-search"></i> Cari Surat Izin
                                </h1>
                                <p class="text-muted">Masukkan nomor surat untuk mencari data surat izin</p>
                            </div>

                            <form method="GET" action="{{ route('search') }}" class="mb-4">
                                <div class="input-group">
                                    <input type="text" name="no_surat" class="form-control" 
                                           placeholder="Masukkan nomor surat..." value="{{ $noSurat ?? '' }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Cari
                                    </button>
                                </div>
                            </form>

                            @if(isset($noSurat))
                                <h4 class="mb-4">Hasil Pencarian untuk: {{ $noSurat }}</h4>

                                @if($driverRequests->count() > 0)
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="bi bi-truck"></i> Surat Izin Driver</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>No Surat</th>
                                                            <th>Nama Driver</th>
                                                            <th>No HP</th>
                                                            <th>Keperluan</th>
                                                            <th>Jam Out</th>
                                                            <th>Jam In</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($driverRequests as $request)
                                                            <tr>
                                                                <td>{{ $request['no_surat'] }}</td>
                                                                <td>{{ $request['nama_driver'] }}</td>
                                                                <td>{{ $request['no_hp_driver'] }}</td>
                                                                <td>{{ $request['keperluan'] }}</td>
                                                                <td>{{ $request['jam_out'] }}</td>
                                                                <td>{{ $request['jam_in'] }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{ $request['status_badge'] }}">{{ $request['status_text'] }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($karyawanRequests->count() > 0)
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="bi bi-person"></i> Surat Izin Karyawan</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>No Surat</th>
                                                            <th>Nama</th>
                                                            <th>No Telp</th>
                                                            <th>Keperluan</th>
                                                            <th>Jam Out</th>
                                                            <th>Jam In</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($karyawanRequests as $request)
                                                            <tr>
                                                                <td>{{ $request['no_surat'] }}</td>
                                                                <td>{{ $request['nama'] }}</td>
                                                                <td>{{ $request['no_telp'] }}</td>
                                                                <td>{{ $request['keperluan'] }}</td>
                                                                <td>{{ $request['jam_out'] }}</td>
                                                                <td>{{ $request['jam_in'] }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{ $request['status_badge'] }}">{{ $request['status_text'] }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($driverRequests->count() == 0 && $karyawanRequests->count() == 0)
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Tidak ditemukan data surat izin dengan nomor surat tersebut.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle JS -->
    @include('layout.auth.script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 