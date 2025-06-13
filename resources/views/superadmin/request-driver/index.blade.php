<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.superadmin.head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<body>
    <div class="wrapper">
        @include('layout.superadmin.header')
        @include('layout.superadmin.alert')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Sidebar -->
        @include('layout.superadmin.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Request Driver</h2>
                                <h5 class="text-white op-7 mb-2">Daftar Permohonan Izin Keluar Driver</h5>
                            </div>
							<div class="ml-md-auto py-2 py-md-0">
								<a href="{{ route('request-driver.create') }}" class="btn btn-light btn-border btn-round">Permohonan Driver</a>
							</div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Summary Cards -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-clock text-warning"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Menunggu</p>
                                                        <h4 class="card-title">{{ $totalMenunggu }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Disetujui</p>
                                                        <h4 class="card-title">{{ $totalDisetujui }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Ditolak</p>
                                                        <h4 class="card-title">{{ $totalDitolak }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-user-clock text-info"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total Request</p>
                                                        <h4 class="card-title">{{ $totalRequest }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Summary Cards -->

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Daftar Permohonan Izin Keluar Driver</div>
                                    <div class="d-flex">
                                        <select class="form-control mr-2" id="filterMonthDriver" style="width: 150px;">
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                        <select class="form-control mr-2" id="filterYearDriver" style="width: 100px;">
                                            @foreach($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                        <div class="btn-group ml-2">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-eye"></i> Preview
                                                </button>
                                                <div class="dropdown-menu p-2" style="width: 200px;">
                                                    <div class="form-check mb-2 ml-2">
                                                        <input class="form-check-input" type="radio" name="previewTypeDriver" id="previewFilteredDriver" value="filtered" checked>
                                                        <label class="form-check-label" for="previewFilteredDriver">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="previewTypeDriver" id="previewAllDriver" value="all">
                                                        <label class="form-check-label" for="previewAllDriver">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-info btn-sm btn-block" onclick="previewPDFDriver()">
                                                        <i class="fas fa-eye"></i> Preview
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </button>
                                                <div class="dropdown-menu p-2" style="width: 200px;">
                                                    <div class="form-check mb-2 ml-2">
                                                        <input class="form-check-input" type="radio" name="pdfTypeDriver" id="pdfFilteredDriver" value="filtered" checked>
                                                        <label class="form-check-label" for="pdfFilteredDriver">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="pdfTypeDriver" id="pdfAllDriver" value="all">
                                                        <label class="form-check-label" for="pdfAllDriver">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="exportDataDriver('pdf')">
                                                        <i class="fas fa-file-pdf"></i> Export PDF
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-file-excel"></i> Excel
                                                </button>
                                                <div class="dropdown-menu p-2" style="width: 200px;">
                                                    <div class="form-check mb-2 ml-2">
                                                        <input class="form-check-input" type="radio" name="excelTypeDriver" id="excelFilteredDriver" value="filtered" checked>
                                                        <label class="form-check-label" for="excelFilteredDriver">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="excelTypeDriver" id="excelAllDriver" value="all">
                                                        <label class="form-check-label" for="excelAllDriver">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-success btn-sm btn-block" onclick="exportDataDriver('excel')">
                                                        <i class="fas fa-file-excel"></i> Export Excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="requestDriverTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Ekspedisi</th>
                                                    <th>No. Polisi</th>
                                                    <th>Nama Driver</th>
                                                    <th>No. HP Driver</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Jam Kembali</th>
                                                    <th>Keperluan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($requestDrivers) && count($requestDrivers) > 0)
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach($requestDrivers as $request)
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ $request->ekspedisi->nama_ekspedisi }}</td>
                                                            <td>{{ $request->nopol_kendaraan }}</td>
                                                            <td>{{ $request->nama_driver }}</td>
                                                            <td>{{ $request->no_hp_driver }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                                            <td>{{ $request->jam_out }}</td>
                                                            <td>{{ $request->jam_in }}</td>
                                                            <td>{{ $request->keperluan }}</td>
                                                            <td>
                                                                @php
                                                                    $status = 'warning'; // default menunggu
                                                                    $text = 'Menunggu';
                                                                    
                                                                    // Cek jika ada yang menolak
                                                                    if($request->acc_admin == 3) {
                                                                        $status = 'danger';
                                                                        $text = 'Ditolak Admin';
                                                                    } 
                                                                    elseif($request->acc_head_unit == 3) {
                                                                        $status = 'danger';
                                                                        $text = 'Ditolak Head Unit';
                                                                    }
                                                                    // Cek urutan persetujuan sesuai alur
                                                                    elseif($request->acc_admin == 1) {
                                                                        $status = 'warning';
                                                                        $text = 'Menunggu Admin/Checker';
                                                                    }
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 1) {
                                                                        $status = 'warning';
                                                                        $text = 'Menunggu Head Unit';
                                                                    }
                                                                    // Jika sudah disetujui Admin dan Head Unit
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2) {
                                                                        // Cek status security
                                                                        if($request->acc_security_out == 1) {
                                                                             // Cek status hangus (jam in sudah lewat tapi belum keluar)
                                                                            if (\Carbon\Carbon::parse($request->jam_in)->isPast()) {
                                                                                $status = 'danger';
                                                                                $text = 'Hangus';
                                                                            } else {
                                                                                $status = 'info';
                                                                                $text = 'Disetujui (Belum Keluar)';
                                                                            }
                                                                        } elseif ($request->acc_security_out == 2) {
                                                                            // Cek status security in
                                                                            if ($request->acc_security_in == 1) {
                                                                                // Cek status terlambat (sudah keluar tapi belum kembali)
                                                                                if (\Carbon\Carbon::parse($request->jam_in)->isPast()) {
                                                                                    $status = 'warning';
                                                                                    $text = 'Terlambat';
                                                                                } else {
                                                                                    $status = 'info';
                                                                                    $text = 'Sudah Keluar (Belum Kembali)';
                                                                                }
                                                                            } elseif ($request->acc_security_in == 2) {
                                                                                $status = 'success';
                                                                                $text = 'Sudah Kembali';
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <span class="badge badge-{{ $status }}">{{ $text }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal" 
                                                                        data-nama-ekpedisi="{{ $request->nama_ekspedisi }}"
                                                                        data-nopol="{{ $request->nopol_kendaraan }}"
                                                                        data-nama-driver="{{ $request->nama_driver }}"
                                                                        data-no-hp-driver="{{ $request->no_hp_driver }}"
                                                                        data-nama-kernet="{{ $request->nama_kernet }}"
                                                                        data-no-hp-kernet="{{ $request->no_hp_kernet }}"
                                                                        data-tanggal="{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}"
                                                                        data-jam-keluar="{{ $request->jam_out }}"
                                                                        data-jam-kembali="{{ $request->jam_in }}"
                                                                        data-keperluan="{{ $request->keperluan }}"
                                                                        data-acc-admin="{{ $request->acc_admin }}"
                                                                        data-acc-head-unit="{{ $request->acc_head_unit }}"
                                                                        data-acc-security-out="{{ $request->acc_security_out }}"
                                                                        data-acc-security-in="{{ $request->acc_security_in }}"
                                                                        data-id="{{ $request->id }}"
                                                                        data-user-role-id="{{ auth()->user()->role_id }}"
                                                                        data-user-role-title="{{ auth()->user()->role->title }}">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal"
                                                                        data-id="{{ $request->id }}"
                                                                        data-ekspedisi-id="{{ $request->ekspedisi_id }}"
                                                                        data-nama-ekpedisi="{{ $request->nama_ekspedisi }}"
                                                                        data-nopol="{{ $request->nopol_kendaraan }}"
                                                                        data-nama-driver="{{ $request->nama_driver }}"
                                                                        data-no-hp-driver="{{ $request->no_hp_driver }}"
                                                                        data-nama-kernet="{{ $request->nama_kernet }}"
                                                                        data-no-hp-kernet="{{ $request->no_hp_kernet }}"
                                                                        data-jam-keluar="{{ $request->jam_out }}"
                                                                        data-jam-kembali="{{ $request->jam_in }}"
                                                                        data-keperluan="{{ $request->keperluan }}">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                                <a href="{{ route('request-driver.exportSinglePDF', $request->id) }}" target="_blank" class="btn btn-sm btn-danger ml-1">
                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="13" class="text-center">Tidak ada data permohonan izin</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.superadmin.script')
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permohonan Izin Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Ekspedisi:</strong></p>
                            <p><strong>Nomor Polisi:</strong></p>
                            <p><strong>Nama Driver:</strong></p>
                            <p><strong>No. HP Driver:</strong></p>
                            <p><strong>Nama Kernet:</strong></p>
                            <p><strong>No. HP Kernet:</strong></p>
                            <p><strong>Tanggal:</strong></p>
                            <p><strong>Jam Keluar:</strong></p>
                            <p><strong>Jam Kembali:</strong></p>
                            <p><strong>Keperluan:</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p id="modal-nama-ekpedisi"></p>
                            <p id="modal-nopol"></p>
                            <p id="modal-nama-driver"></p>
                            <p id="modal-no-hp-driver"></p>
                            <p id="modal-nama-kernet"></p>
                            <p id="modal-no-hp-kernet"></p>
                            <p id="modal-tanggal"></p>
                            <p id="modal-jam-keluar"></p>
                            <p id="modal-jam-kembali"></p>
                            <p id="modal-keperluan"></p>
                        </div>
                    </div>
                    <input type="hidden" id="modal-request-id" name="request_id">
                    <hr>
                    <h5 class="mb-3">Status Persetujuan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Admin:</label>
                                <div id="admin-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="admin" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="admin" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="admin" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-admin" class="mt-2"></div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Head Unit:</label>
                                <div id="head-unit-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="head-unit" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="head-unit" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="head-unit" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-head-unit" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Security (Keluar):</label>
                                <div id="security-out-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="security-out" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="security-out" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="security-out" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-security-out" class="mt-2"></div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Security (Masuk):</label>
                                <div id="security-in-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="security-in" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="security-in" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="security-in" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-security-in" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveStatusBtn">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi ACC -->
    <div class="modal fade" id="accConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="accConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accConfirmationModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyetujui permohonan izin ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmAccBtn">Ya, Setujui</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Permohonan Izin Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_ekspedisi_id">Nama Ekspedisi</label>
                                    <select class="form-control" id="edit_ekspedisi_id" name="ekspedisi_id" required>
                                        @foreach($ekspedisis as $ekspedisi)
                                            <option value="{{ $ekspedisi->id }}">{{ $ekspedisi->nama_ekspedisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_nopol_kendaraan">Nomor Polisi</label>
                                    <input type="text" class="form-control" id="edit_nopol_kendaraan" name="nopol_kendaraan" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_nama_driver">Nama Driver</label>
                                    <input type="text" class="form-control" id="edit_nama_driver" name="nama_driver" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_no_hp_driver">No. HP Driver</label>
                                    <input type="text" class="form-control" id="edit_no_hp_driver" name="no_hp_driver" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_nama_kernet">Nama Kernet</label>
                                    <input type="text" class="form-control" id="edit_nama_kernet" name="nama_kernet">
                                </div>
                                <div class="form-group">
                                    <label for="edit_no_hp_kernet">No. HP Kernet</label>
                                    <input type="text" class="form-control" id="edit_no_hp_kernet" name="no_hp_kernet">
                                </div>
                                <div class="form-group">
                                    <label for="edit_jam_out">Jam Keluar</label>
                                    <input type="time" class="form-control" id="edit_jam_out" name="jam_out" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_jam_in">Jam Kembali</label>
                                    <input type="time" class="form-control" id="edit_jam_in" name="jam_in" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_keperluan">Keperluan</label>
                            <textarea class="form-control" id="edit_keperluan" name="keperluan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .table-container {
            position: relative;
            margin-top: 20px;
        }

        .table-container .dataTables_filter {
            position: absolute;
            top: -40px;
            right: 0;
            z-index: 1;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table table td {
            white-space: nowrap;
        }

        #requestDriverTable th,
        #requestDriverTable td {
            white-space: nowrap !important;
            min-width: 150px; /* Sesuaikan jika perlu */
        }

        #requestDriverTable th:nth-child(1),
        #requestDriverTable td:nth-child(1) {
            min-width: 50px; /* Lebar lebih kecil untuk kolom No. */
        }

        #requestDriverTable th:last-child,
        #requestDriverTable td:last-child {
            min-width: 180px; /* Lebar lebih besar untuk kolom Aksi */
        }

        .status-action.active {
            filter: brightness(85%); /* Make active button slightly darker */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Add a subtle shadow */
        }

        .hidden-by-js {
            display: none !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Konfigurasi bahasa Indonesia untuk DataTables
            const indonesianLanguage = {
                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "lengthMenu": "Tampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            };

            // Inisialisasi DataTable dengan bahasa Indonesia
            var requestDriverTable = $('#requestDriverTable').DataTable({
                processing: true,
                serverSide: false,
                pageLength: 10,
                language: indonesianLanguage,
                "order": [[0, "asc"]], // Urutkan berdasarkan kolom nomor urut secara ascending
                "responsive": true,
                "scrollX": true,
                "autoWidth": false,
                "dom": '<"top"f>rt<"bottom"lp><"clear">',
                "columnDefs": [
                    { "orderable": false, "searchable": false, "targets": [0, 9, 10] } // Nonaktifkan pengurutan dan pencarian untuk kolom No., Status, dan Aksi
                ],
                "columns": [
                    { 
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    }, // Kolom No
                    { "data": "nama_ekspedisi" }, // Kolom Nama Ekspedisi
                    { "data": "nopol_kendaraan" }, // Kolom No. Polisi
                    { "data": "nama_driver" }, // Kolom Nama Driver
                    { "data": "no_hp_driver" }, // Kolom No. HP Driver
                    { 
                        "data": "tanggal",
                        "render": function(data, type, row) {
                            return moment(data, 'YYYY-MM-DD').format('DD/MM/YYYY');
                        }
                    }, // Kolom Tanggal
                    { "data": "jam_out" }, // Kolom Jam Keluar
                    { "data": "jam_in" }, // Kolom Jam Kembali
                    { "data": "keperluan" }, // Kolom Keperluan
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            // Langsung gunakan status_badge dan status_text yang sudah dihitung dari server
                            return `<span class="badge badge-${row.status_badge}">${row.status_text}</span>`;
                        }
                    }, // Kolom Status
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            let buttons = '';
                            console.log(row);

                            // Detail button
                            buttons += `<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal" 
                                            data-nama-ekpedisi="${row.nama_ekspedisi}"
                                            data-nopol="${row.nopol_kendaraan}"
                                            data-nama-driver="${row.nama_driver}"
                                            data-no-hp-driver="${row.no_hp_driver}"
                                            data-nama-kernet="${row.nama_kernet || '-'}"
                                            data-no-hp-kernet="${row.no_hp_kernet || '-'}"
                                            data-tanggal="${row.tanggal}"
                                            data-jam-keluar="${row.jam_out}"
                                            data-jam-kembali="${row.jam_in}"
                                            data-keperluan="${row.keperluan}"
                                            data-acc-admin="${row.acc_admin}"
                                            data-acc-head-unit="${row.acc_head_unit}"
                                            data-acc-security-out="${row.acc_security_out}"
                                            data-acc-security-in="${row.acc_security_in}"
                                            data-id="${row.id}"
                                            data-user-role-id="${row.user_role_id}"
                                            data-user-role-title="${row.user_role_title}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>`;

                            // Edit button
                            buttons += `<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal"
                                            data-id="${row.id}"
                                            data-ekspedisi-id="${row.ekspedisi_id}"
                                            data-nama-ekpedisi="${row.nama_ekspedisi}"
                                            data-nopol="${row.nopol_kendaraan}"
                                            data-nama-driver="${row.nama_driver}"
                                            data-no-hp-driver="${row.no_hp_driver}"
                                            data-nama-kernet="${row.nama_kernet || ''}"
                                            data-no-hp-kernet="${row.no_hp_kernet || ''}"
                                            data-jam-keluar="${row.jam_out}"
                                            data-jam-kembali="${row.jam_in}"
                                            data-keperluan="${row.keperluan}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>`;

                            // PDF Export button
                            buttons += `<a href="/request-driver/export/single-pdf/${row.id}" target="_blank" class="btn btn-sm btn-danger ml-1">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>`;

                            return buttons;
                        }
                    } // Kolom Aksi
                ]
            });

            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.floating-alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);

            // Add animation when alert appears
            $('.floating-alert').hide().fadeIn('slow');

            // Handle modal data
            $('#detailModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                const requestId = button.data('id');
                const userRoleId = button.data('user-role-id');
                const userRoleTitle = button.data('user-role-title');
                console.log('Detail Modal opened for Request ID (Driver): ', requestId);
                console.log('User Role ID: ', userRoleId);
                console.log('User Role Title: ', userRoleTitle);
                
                // Store request ID in hidden input
                $('#modal-request-id').val(requestId);
                
                // Remove active class from all status actions
                $('.status-action').removeClass('active');
                
                // Update modal content
                $('#modal-nama-ekpedisi').text(button.data('nama-ekpedisi'));
                $('#modal-nopol').text(button.data('nopol'));
                $('#modal-nama-driver').text(button.data('nama-driver'));
                $('#modal-no-hp-driver').text(button.data('no-hp-driver'));
                $('#modal-nama-kernet').text(button.data('nama-kernet') || '-');
                $('#modal-no-hp-kernet').text(button.data('no-hp-kernet') || '-');
                $('#modal-tanggal').text(button.data('tanggal'));
                $('#modal-jam-keluar').text(button.data('jam-keluar'));
                $('#modal-jam-kembali').text(button.data('jam-kembali'));
                $('#modal-keperluan').text(button.data('keperluan'));
                
                // Update status badges and set initial active state for status actions
                const accAdmin = button.data('acc-admin');
                const accHeadUnit = button.data('acc-head-unit');
                const accSecurityOut = button.data('acc-security-out');
                const accSecurityIn = button.data('acc-security-in');

                console.log('Initial Statuses (Driver):',
                    'Admin:', accAdmin,
                    'Head Unit:', accHeadUnit,
                    'Security Out:', accSecurityOut,
                    'Security In:', accSecurityIn
                );
                
                updateStatusBadge('admin', accAdmin);
                if (accAdmin === 2) $('.status-action[data-role="admin"][data-status="2"]').addClass('active');
                else if (accAdmin === 3) $('.status-action[data-role="admin"][data-status="3"]').addClass('active');
                else if (accAdmin === 1) $('.status-action[data-role="admin"][data-status="1"]').addClass('active');
                
                updateStatusBadge('head-unit', accHeadUnit);
                if (accHeadUnit === 2) $('.status-action[data-role="head-unit"][data-status="2"]').addClass('active');
                else if (accHeadUnit === 3) $('.status-action[data-role="head-unit"][data-status="3"]').addClass('active');
                else if (accHeadUnit === 1) $('.status-action[data-role="head-unit"][data-status="1"]').addClass('active');
                
                updateStatusBadge('security-out', accSecurityOut);
                if (accSecurityOut === 2) $('.status-action[data-role="security-out"][data-status="2"]').addClass('active');
                else if (accSecurityOut === 3) $('.status-action[data-role="security-out"][data-status="3"]').addClass('active');
                else if (accSecurityOut === 1) $('.status-action[data-role="security-out"][data-status="1"]').addClass('active');
                
                updateStatusBadge('security-in', accSecurityIn);
                if (accSecurityIn === 2) $('.status-action[data-role="security-in"][data-status="2"]').addClass('active');
                else if (accSecurityIn === 3) $('.status-action[data-role="security-in"][data-status="3"]').addClass('active');
                else if (accSecurityIn === 1) $('.status-action[data-role="security-in"][data-status="1"]').addClass('active');

                // Store initial statuses
                let initialStatuses = {
                    admin: accAdmin,
                    'head-unit': accHeadUnit,
                    'security-out': accSecurityOut,
                    'security-in': accSecurityIn
                };
                $(this).data('initialStatuses', initialStatuses);

                // Reset currentStatuses when modal is opened to avoid carrying over old state
                $(this).data('currentStatuses', {});

                // Hide all action button containers initially using the strong hidden-by-js class
                $('#admin-actions').addClass('hidden-by-js');
                $('#head-unit-actions').addClass('hidden-by-js');
                $('#security-out-actions').addClass('hidden-by-js');
                $('#security-in-actions').addClass('hidden-by-js');

                // Now show based on user role and current approval flow status
                if (userRoleId == 1) { // Admin (ID 1) can see all action buttons
                    $('#admin-actions').removeClass('hidden-by-js');
                    $('#head-unit-actions').removeClass('hidden-by-js');
                    $('#security-out-actions').removeClass('hidden-by-js');
                    $('#security-in-actions').removeClass('hidden-by-js');
                } else if (userRoleId == 4) { // Checker (ID 4)
                    // Checker can see their action buttons ONLY if Head Unit has NOT yet approved
                    if (accHeadUnit !== 2) {
                        $('#admin-actions').removeClass('hidden-by-js');
                    }
                } else if (userRoleId == 5) { // Head Unit (ID 5)
                    // Head Unit can always see their action buttons if they are the current user
                    $('#head-unit-actions').removeClass('hidden-by-js');
                } else if (userRoleId == 6) { // Security (ID 6)
                    // Security can always see their Security Out action buttons if they are the current user
                    $('#security-out-actions').removeClass('hidden-by-js');
                    // Security can see their Security In action buttons if Security Out has also approved
                    if (accSecurityOut == 2) {
                        $('#security-in-actions').removeClass('hidden-by-js');
                    }
                }

                // Debugging: Log visibility state of action buttons
                console.log('Action Button Visibility (Driver):');
                console.log('Admin Actions Visible:', $('#admin-actions').is(':visible'));
                console.log('Head Unit Actions Visible:', $('#head-unit-actions').is(':visible'));
                console.log('Security Out Actions Visible:', $('#security-out-actions').is(':visible'));
                console.log('Security In Actions Visible:', $('#security-in-actions').is(':visible'));
                
                // Hide save button initially, it will be shown if a change is made
                $('#saveStatusBtn').hide();
            });

            // Handle ACC button click
            $('.acc-btn').click(function() {
                const requestId = $(this).data('id');
                const roleId = $(this).data('roleId');
                console.log('ACC Button clicked, Request ID:', requestId);
                // Store request ID in the modal for later use
                $('#accConfirmationModal').data('requestId', requestId);
                $('#accConfirmationModal').data('roleId', roleId);
                // Show the confirmation modal
                $('#accConfirmationModal').modal('show');
            });

            // Handle click on the confirmation button inside the modal
            $('#confirmAccBtn').click(function() {
                const requestId = $('#accConfirmationModal').data('requestId');
                const roleId = $('#accConfirmationModal').data('roleId');
                
                // Close the modal
                $('#accConfirmationModal').modal('hide');

                // Proceed with the AJAX request
                $.ajax({
                    url: '/request-driver/' + requestId + '/acc/' + roleId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseText;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Function to check if save button should be visible
            function checkAndToggleSaveButton(modalElement) {
                const initialStatuses = $(modalElement).data('initialStatuses');
                const currentStatuses = $(modalElement).data('currentStatuses');
                let changesMade = false;

                for (const role in initialStatuses) {
                    // Check if current status is different from initial status
                    // And ensure currentStatuses has a value for this role
                    if (currentStatuses && currentStatuses[role] !== undefined && initialStatuses[role] !== currentStatuses[role]) {
                        changesMade = true;
                        break;
                    }
                }
                
                if (changesMade) {
                    $('#saveStatusBtn').show();
                } else {
                    $('#saveStatusBtn').hide();
                }
            }

            // Handle status checkbox changes
            $('.status-action').click(function() {
                const role = $(this).data('role');
                const status = $(this).data('status');
                const requestId = $('#modal-request-id').val();
                const modalElement = $(this).closest('.modal');
                
                // Remove active class from all actions in the same group
                $(`.status-action[data-role="${role}"]`).removeClass('active');
                
                // Add active class to the clicked action
                $(this).addClass('active');
                
                // Update the status badge
                updateStatusBadge(role, status);

                // Update the current statuses object in modal data
                let currentStatuses = $(modalElement).data('currentStatuses') || {};
                currentStatuses[role] = status;
                $(modalElement).data('currentStatuses', currentStatuses);

                // Check and toggle save button visibility
                checkAndToggleSaveButton(modalElement);
            });

            // Function to update status badge
            function updateStatusBadge(role, status) {
                let badge = '';
                if (status === 2) {
                    badge = '<span class="badge badge-success">Disetujui</span>';
                } else if (status === 3) {
                    badge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    badge = '<span class="badge badge-warning">Menunggu</span>';
                }
                $(`#modal-acc-${role}`).html(badge);
            }

            // Handle save status button click
            $('#saveStatusBtn').click(function() {
                const requestId = $('#modal-request-id').val();
                const statuses = $(this).closest('.modal').data('currentStatuses'); // Use currentStatuses
                
                // Ensure statuses object is not empty or undefined
                if (!statuses || Object.keys(statuses).length === 0) {
                    alert('Tidak ada perubahan status yang dipilih.');
                    return;
                }

                // Send AJAX request to update statuses
                $.ajax({
                    url: `/request-driver/${requestId}/update-status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        statuses: statuses
                    },
                    success: function(response) {
                        if(response.success) {
                            alert('Status berhasil diperbarui');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Handle edit modal
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                const requestId = button.data('id');
                
                // Set form action URL
                $('#editForm').attr('action', `/request-driver/${requestId}`);
                
                // Populate form fields
                $('#edit_ekspedisi_id').val(button.data('ekspedisi-id'));
                $('#edit_nopol_kendaraan').val(button.data('nopol'));
                $('#edit_nama_driver').val(button.data('nama-driver'));
                $('#edit_no_hp_driver').val(button.data('no-hp-driver'));
                $('#edit_nama_kernet').val(button.data('nama-kernet'));
                $('#edit_no_hp_kernet').val(button.data('no-hp-kernet'));
                $('#edit_jam_out').val(button.data('jam-keluar'));
                $('#edit_jam_in').val(button.data('jam-kembali'));
                $('#edit_keperluan').val(button.data('keperluan'));
            });

            // Handle form submission
            $('#editForm').submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.success) {
                            alert('Data berhasil diperbarui');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Fungsi untuk memuat data
            function loadDataDriver() {
                const month = $('#filterMonthDriver').val();
                const year = $('#filterYearDriver').val();
                
                $.get(`/request-driver/latest-requests?month=${month}&year=${year}`, function(data) {
                    // Clear dan reload data
                    requestDriverTable.clear();
                    requestDriverTable.rows.add(data).draw();
                });
            }

            // Set bulan dan tahun saat ini sebagai default
            const currentDateDriver = new Date();
            $('#filterMonthDriver').val(currentDateDriver.getMonth() + 1);
            $('#filterYearDriver').val(currentDateDriver.getFullYear());

            // Load data awal
            loadDataDriver();

            // Event change untuk filter
            $('#filterMonthDriver, #filterYearDriver').change(function() {
                loadDataDriver();
            });
        });

        function previewPDFDriver() {
            const month = $('#filterMonthDriver').val();
            const year = $('#filterYearDriver').val();
            const exportType = $('input[name="previewTypeDriver"]:checked').val();
            
            const url = `/request-driver/export/preview/${month}/${year}?type=${exportType}`;
            window.open(url, '_blank');
        }

        function exportDataDriver(format) {
            const month = $('#filterMonthDriver').val();
            const year = $('#filterYearDriver').val();
            const exportType = $(`input[name="${format}TypeDriver"]:checked`).val();
            
            const url = `/request-driver/export/${format}/${month}/${year}?type=${exportType}`;
            window.location.href = url;
        }
    </script>
</body>
</html>