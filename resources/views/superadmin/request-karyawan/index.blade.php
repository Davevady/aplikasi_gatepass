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
                                <h2 class="text-white pb-2 fw-bold">Request Karyawan</h2>
                                <h5 class="text-white op-7 mb-2">Daftar Permohonan Izin Keluar Karyawan</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <a href="{{ route('request-karyawan.create') }}" class="btn btn-light btn-border btn-round mr-2">Permohonan Karyawan</a>
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
                                    <div class="card-title">Daftar Permohonan Izin Keluar</div>
                                    <div class="d-flex">
                                        <select class="form-control mr-2" id="filterMonthKaryawan" style="width: 150px;">
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
                                        <select class="form-control mr-2" id="filterYearKaryawan" style="width: 100px;">
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
                                                        <input class="form-check-input" type="radio" name="previewTypeKaryawan" id="previewFilteredKaryawan" value="filtered" checked>
                                                        <label class="form-check-label" for="previewFilteredKaryawan">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="previewTypeKaryawan" id="previewAllKaryawan" value="all">
                                                        <label class="form-check-label" for="previewAllKaryawan">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-info btn-sm btn-block" onclick="previewPDFKaryawan()">
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
                                                        <input class="form-check-input" type="radio" name="pdfTypeKaryawan" id="pdfFilteredKaryawan" value="filtered" checked>
                                                        <label class="form-check-label" for="pdfFilteredKaryawan">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="pdfTypeKaryawan" id="pdfAllKaryawan" value="all">
                                                        <label class="form-check-label" for="pdfAllKaryawan">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="exportDataKaryawan('pdf')">
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
                                                        <input class="form-check-input" type="radio" name="excelTypeKaryawan" id="excelFilteredKaryawan" value="filtered" checked>
                                                        <label class="form-check-label" for="excelFilteredKaryawan">
                                                            Data yang Ditampilkan
                                                        </label>
                                                    </div>
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input" type="radio" name="excelTypeKaryawan" id="excelAllKaryawan" value="all">
                                                        <label class="form-check-label" for="excelAllKaryawan">
                                                            Semua Data
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">
                                                    <button type="button" class="btn btn-success btn-sm btn-block" onclick="exportDataKaryawan('excel')">
                                                        <i class="fas fa-file-excel"></i> Export Excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-container">
                                        <div class="table-responsive">
                                            <table id="requestTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>No. Surat</th>
                                                        <th>Departemen</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>No. Telp</th>
                                                        <th>Tanggal</th>
                                                        <th>Jam Keluar</th>
                                                        <th>Jam Kembali</th>
                                                        <th>Keperluan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($requestKaryawans) && count($requestKaryawans) > 0)
                                                        @php
                                                            $counter = 1;
                                                        @endphp
                                                        @foreach($requestKaryawans as $request)
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>
                                                                <td>{{ $request->no_surat }}</td>
                                                                <td>{{ $request->departemen->name }}</td>
                                                                <td>{{ $request->nama }}</td>
                                                                <td>{{ $request->no_telp }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                                                <td>{{ $request->jam_out }}</td>
                                                                <td>{{ $request->jam_in }}</td>
                                                                <td>{{ $request->keperluan }}</td>
                                                                <td>
                                                                    @php
                                                                        $status = 'warning'; // default menunggu
                                                                        $text = 'Menunggu';
                                                                        
                                                                        // Cek jika ada yang menolak
                                                                        if($request->acc_lead == 3) {
                                                                            $status = 'danger';
                                                                            $text = 'Ditolak Lead';
                                                                        } 
                                                                        elseif($request->acc_hr_ga == 3) {
                                                                            $status = 'danger';
                                                                            $text = 'Ditolak HR/GA';
                                                                        }
                                                                        // Cek urutan persetujuan sesuai alur
                                                                        elseif($request->acc_lead == 1) {
                                                                            $status = 'warning';
                                                                            $text = 'Menunggu Lead';
                                                                        }
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 1) {
                                                                            $status = 'warning';
                                                                            $text = 'Menunggu HR/GA';
                                                                        }
                                                                        // Jika sudah disetujui Lead dan HR/GA
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 2) {
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
                                                                    <button type="button" class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#detailModal"
                                                                            data-id="{{ $request->id }}"
                                                                            data-no-surat="{{ $request->no_surat }}"
                                                                            data-nama="{{ $request->nama }}"
                                                                            data-no-telp="{{ $request->no_telp }}"
                                                                            data-departemen="{{ $request->departemen->name }}"
                                                                            data-tanggal="{{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}"
                                                                            data-jam-keluar="{{ $request->jam_out }}"
                                                                            data-jam-kembali="{{ $request->jam_in }}"
                                                                            data-keperluan="{{ $request->keperluan }}"
                                                                            data-acc-lead="{{ $request->acc_lead }}"
                                                                            data-acc-hr-ga="{{ $request->acc_hr_ga }}"
                                                                            data-acc-security-out="{{ $request->acc_security_out }}"
                                                                            data-acc-security-in="{{ $request->acc_security_in }}"
                                                                            data-user-role-id="{{ auth()->user()->role_id }}"
                                                                            data-user-role-title="{{ auth()->user()->role->title }}">
                                                                        <i class="fas fa-eye"></i> Detail
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target="#editModal"
                                                                            data-id="{{ $request->id }}"
                                                                            data-no-surat="{{ $request->no_surat }}"
                                                                            data-nama="{{ $request->nama }}"
                                                                            data-no-telp="{{ $request->no_telp }}"
                                                                            data-departemen-id="{{ $request->departemen_id }}"
                                                                            data-jam-keluar="{{ $request->jam_out }}"
                                                                            data-jam-kembali="{{ $request->jam_in }}"
                                                                            data-keperluan="{{ $request->keperluan }}">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>
                                                                    <a href="{{ route('request-karyawan.exportSinglePDF', $request->id) }}" target="_blank" class="btn btn-sm btn-danger ml-1">
                                                                        <i class="fas fa-file-pdf"></i> PDF
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="11" class="text-center">Tidak ada data permohonan izin</td>
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
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permohonan Izin Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>No. Surat:</strong></p>
                            <p><strong>Nama Karyawan:</strong></p>
                            <p><strong>No. Telp:</strong></p>
                            <p><strong>Departemen:</strong></p>
                            <p><strong>Tanggal:</strong></p>
                            <p><strong>Jam Keluar:</strong></p>
                            <p><strong>Jam Kembali:</strong></p>
                            <p><strong>Keperluan:</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p id="modal-no-surat"></p>
                            <p id="modal-nama"></p>
                            <p id="modal-no-telp"></p>
                            <p id="modal-departemen"></p>
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
                                <label class="font-weight-bold">Lead:</label>
                                <div id="lead-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="lead" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="lead" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="lead" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-lead" class="mt-2"></div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">HR/GA:</label>
                                <div id="hr-ga-actions" class="d-flex flex-wrap mb-2">
                                    <span class="status-action btn btn-success btn-sm mr-2 mb-1" data-role="hr-ga" data-status="2">Setujui</span>
                                    <span class="status-action btn btn-danger btn-sm mr-2 mb-1" data-role="hr-ga" data-status="3">Tolak</span>
                                    <span class="status-action btn btn-warning btn-sm mb-1" data-role="hr-ga" data-status="1">Menunggu</span>
                                </div>
                                <div id="modal-acc-hr-ga" class="mt-2"></div>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Permohonan Izin Karyawan</h5>
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
                                    <label for="edit_no_surat">No. Surat</label>
                                    <input type="text" class="form-control" id="edit_no_surat" name="no_surat" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_nama">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_no_telp">No. Telp</label>
                                    <input type="text" class="form-control" id="edit_no_telp" name="no_telp" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_departemen_id">Departemen</label>
                                    <select class="form-control" id="edit_departemen_id" name="departemen_id" required>
                                        @foreach($departemens as $departemen)
                                            <option value="{{ $departemen->id }}">{{ $departemen->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
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

    @include('layout.superadmin.script')

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

        #requestTable th,
        #requestTable td {
            white-space: nowrap !important;
            min-width: 150px; /* Sesuaikan jika perlu */
        }

        #requestTable th:nth-child(1),
        #requestTable td:nth-child(1) {
            min-width: 50px; /* Lebar lebih kecil untuk kolom No. */
        }

        #requestTable th:last-child,
        #requestTable td:last-child {
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
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        var tableKaryawan; // Deklarasikan variabel tableKaryawan secara global

        // Definisi bahasa Indonesia untuk DataTable
        const indonesianLanguage = {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        };

        // Fungsi untuk memuat data di tabel request karyawan
        function loadDataKaryawan() {
            const month = $('#filterMonthKaryawan').val();
            const year = $('#filterYearKaryawan').val();
            
            $.get(`/request-karyawan/latest-requests?month=${month}&year=${year}`, function(response) {
                // Clear dan reload data
                tableKaryawan.clear().rows.add(response.data).draw();
            });
        }

        function previewPDFKaryawan() {
            const month = $('#filterMonthKaryawan').val();
            const year = $('#filterYearKaryawan').val();
            const exportType = $('input[name="previewTypeKaryawan"]:checked').val();
            
            const url = `/request-karyawan/export/preview/${month}/${year}?type=${exportType}`;
            window.open(url, '_blank');
        }

        function exportDataKaryawan(format) {
            const month = $('#filterMonthKaryawan').val();
            const year = $('#filterYearKaryawan').val();
            const exportType = $(`input[name="${format}TypeKaryawan"]:checked`).val();
            
            const url = `/request-karyawan/export/${format}/${month}/${year}?type=${exportType}`;
            window.location.href = url;
        }

        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.floating-alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);

            // Add animation when alert appears
            $('.floating-alert').hide().fadeIn('slow');

            // Inisialisasi DataTable
            tableKaryawan = $('#requestTable').DataTable({
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
                    { "orderable": false, "targets": [0, 9, 10] }, // Nonaktifkan pengurutan untuk kolom No., Status, dan Aksi
                    { "searchable": false, "targets": [0, 9, 10] } // Nonaktifkan pencarian untuk kolom No., Status, dan Aksi
                ],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, // Kolom No.
                    { data: 'no_surat' }, // Kolom No. Surat
                    { data: 'departemen' }, // Kolom Departemen (sesuai JSON baru)
                    { data: 'nama' }, // Kolom Nama Karyawan
                    { data: 'no_telp' }, // Kolom No. Telp
                    {
                        data: 'tanggal',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return moment(data, 'YYYY-MM-DD').format('DD/MM/YYYY');
                            }
                            return data;
                        }
                    }, // Kolom Tanggal
                    { data: 'jam_out' }, // Kolom Jam Keluar
                    { data: 'jam_in' }, // Kolom Jam Kembali
                    { data: 'keperluan' }, // Kolom Keperluan
                    {
                        data: 'status_text', // Menggunakan 'status_text' dari JSON
                        render: function(data, type, row) {
                            return `<span class="badge badge-${row.status_badge}">${row.status_text}</span>`;
                        }
                    }, // Kolom Status
                    {
                        data: null,
                        render: function(data, type, row) {
                            let buttons = '';

                            // Detail button
                            buttons += `<button type="button" class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#detailModal"
                                            data-id="${row.id}"
                                            data-no-surat="${row.no_surat}"
                                            data-nama="${row.nama}"
                                            data-no-telp="${row.no_telp}"
                                            data-departemen="${row.departemen}"
                                            data-tanggal="${row.tanggal}"
                                            data-jam-keluar="${row.jam_out}"
                                            data-jam-kembali="${row.jam_in}"
                                            data-keperluan="${row.keperluan}"
                                            data-acc-lead="${row.acc_lead}"
                                            data-acc-hr-ga="${row.acc_hr_ga}"
                                            data-acc-security-out="${row.acc_security_out}"
                                            data-acc-security-in="${row.acc_security_in}"
                                            data-user-role-id="${row.user_role_id}"
                                            data-user-role-title="${row.user_role_title}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>`;

                            // Edit button
                            buttons += `<button type="button" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target="#editModal"
                                            data-id="${row.id}"
                                            data-no-surat="${row.no_surat}"
                                            data-nama="${row.nama}"
                                            data-no-telp="${row.no_telp}"
                                            data-departemen-id="${row.departemen_id}"
                                            data-jam-keluar="${row.jam_out}"
                                            data-jam-kembali="${row.jam_in}"
                                            data-keperluan="${row.keperluan}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>`;

                            // PDF Export button
                            buttons += `<a href="/request-karyawan/export/single-pdf/${row.id}" target="_blank" class="btn btn-sm btn-danger ml-1">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>`;

                            return buttons;
                        }
                    } // Kolom Aksi
                ]
            });

            // Set bulan dan tahun saat ini sebagai default saat halaman dimuat
            const currentDateKaryawan = new Date();
            $('#filterMonthKaryawan').val(currentDateKaryawan.getMonth() + 1);
            $('#filterYearKaryawan').val(currentDateKaryawan.getFullYear());
            loadDataKaryawan(); // Muat data awal

            // Event change untuk filter bulan dan tahun
            $('#filterMonthKaryawan, #filterYearKaryawan').change(function() {
                loadDataKaryawan();
            });

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

            // Handle save status button click
            $('#saveStatusBtn').click(function() {
                const requestId = $('#modal-request-id').val();
                const statuses = $('#detailModal').data('currentStatuses'); // Use currentStatuses
                
                // Ensure statuses object is not empty or undefined
                if (!statuses || Object.keys(statuses).length === 0) {
                    alert('Tidak ada perubahan status yang dipilih.');
                    return;
                }

                // Send AJAX request to update statuses
                $.ajax({
                    url: `/request-karyawan/${requestId}/update-status`,
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
                        } else if (xhr.responseText) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseText;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Update modal data and set initial active state for status actions when modal is shown
            $('#detailModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                const requestId = button.data('id');
                const userRoleId = button.data('user-role-id');
                const userRoleTitle = button.data('user-role-title');
                console.log('Detail Modal opened for Request ID (Karyawan): ', requestId);
                console.log('User Role ID: ', userRoleId);
                console.log('User Role Title: ', userRoleTitle);
                
                // Store request ID in hidden input
                $('#modal-request-id').val(requestId);
                
                // Remove active class from all status actions
                $('.status-action').removeClass('active');
                
                // Update modal content
                $('#modal-no-surat').text(button.data('no-surat'));
                $('#modal-nama').text(button.data('nama'));
                $('#modal-no-telp').text(button.data('no-telp'));
                $('#modal-departemen').text(button.data('departemen'));
                $('#modal-tanggal').text(button.data('tanggal'));
                $('#modal-jam-keluar').text(button.data('jam-keluar'));
                $('#modal-jam-kembali').text(button.data('jam-kembali'));
                $('#modal-keperluan').text(button.data('keperluan'));
                
                // Update status badges and set initial active state for status actions
                const accLead = button.data('acc-lead');
                const accHrGa = button.data('acc-hr-ga');
                const accSecurityOut = button.data('acc-security-out');
                const accSecurityIn = button.data('acc-security-in');

                console.log('Initial Statuses (Karyawan):',
                    'Lead:', accLead,
                    'HR/GA:', accHrGa,
                    'Security Out:', accSecurityOut,
                    'Security In:', accSecurityIn
                );
                
                updateStatusBadge('lead', accLead);
                if (accLead === 2) $('.status-action[data-role="lead"][data-status="2"]').addClass('active');
                else if (accLead === 3) $('.status-action[data-role="lead"][data-status="3"]').addClass('active');
                else if (accLead === 1) $('.status-action[data-role="lead"][data-status="1"]').addClass('active');

                updateStatusBadge('hr-ga', accHrGa);
                if (accHrGa === 2) $('.status-action[data-role="hr-ga"][data-status="2"]').addClass('active');
                else if (accHrGa === 3) $('.status-action[data-role="hr-ga"][data-status="3"]').addClass('active');
                else if (accHrGa === 1) $('.status-action[data-role="hr-ga"][data-status="1"]').addClass('active');
                
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
                    lead: accLead,
                    'hr-ga': accHrGa,
                    'security-out': accSecurityOut,
                    'security-in': accSecurityIn
                };
                $(this).data('initialStatuses', initialStatuses);

                // Reset currentStatuses when modal is opened to avoid carrying over old state
                $(this).data('currentStatuses', {});

                // Hide all action button containers initially using the strong hidden-by-js class
                $('#lead-actions').addClass('hidden-by-js');
                $('#hr-ga-actions').addClass('hidden-by-js');
                $('#security-out-actions').addClass('hidden-by-js');
                $('#security-in-actions').addClass('hidden-by-js');

                // Now show based on user role and current approval flow status
                if (userRoleId == 1) { // Admin (ID 1) can see all action buttons
                    $('#lead-actions').removeClass('hidden-by-js');
                    $('#hr-ga-actions').removeClass('hidden-by-js');
                    $('#security-out-actions').removeClass('hidden-by-js');
                    $('#security-in-actions').removeClass('hidden-by-js');
                } else if (userRoleId == 2) { // Lead (ID 2) - analogous to Admin (ID 4) for Driver
                    // Lead can see their action buttons ONLY if HR/GA has NOT yet approved
                    if (accHrGa !== 2) {
                        $('#lead-actions').removeClass('hidden-by-js');
                    }
                } else if (userRoleId == 3) { // HR/GA (ID 3) - analogous to Head Unit (ID 5) for Driver
                    // HR/GA can see their action buttons if Lead has approved
                    if (accLead === 2) {
                        $('#hr-ga-actions').removeClass('hidden-by-js');
                    }
                } else if (userRoleId == 6) { // Security (ID 6) - analogous to Security (ID 6) for Driver
                    // Security can see their Security Out action buttons if Lead and HR/GA have approved
                    if (accLead === 2 && accHrGa === 2) {
                        $('#security-out-actions').removeClass('hidden-by-js');
                        // Security can see their Security In action buttons if Security Out has also approved
                        if (accSecurityOut === 2) {
                            $('#security-in-actions').removeClass('hidden-by-js');
                        }
                    }
                }

                // Debugging: Log visibility state of action buttons
                console.log('Action Button Visibility (Karyawan):');
                console.log('Lead Actions Visible:', $('#lead-actions').is(':visible'));
                console.log('HR/GA Actions Visible:', $('#hr-ga-actions').is(':visible'));
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
                    url: `/request-karyawan/${requestId}/acc/${roleId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
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
                        } else if (xhr.responseText) {
                            errorMessage = 'Terjadi kesalahan: ' + xhr.responseText;
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
                $('#editForm').attr('action', `/request-karyawan/${requestId}`);
                
                // Populate form fields
                $('#edit_no_surat').val(button.data('no-surat'));
                $('#edit_nama').val(button.data('nama'));
                $('#edit_no_telp').val(button.data('no-telp'));
                $('#edit_departemen_id').val(button.data('departemen-id')); // Menggunakan data-departemen-id
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
        });
    </script>
</body>
</html>