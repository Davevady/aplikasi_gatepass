<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.superadmin.head')
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
                                                            <td>{{ $request->nama_ekspedisi }}</td>
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
                                                                        $text = 'Menunggu Admin';
                                                                    }
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 1) {
                                                                        $status = 'warning';
                                                                        $text = 'Menunggu Head Unit';
                                                                    }
                                                                    // Cek status hangus (jam in sudah lewat tapi belum keluar)
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 1 && \Carbon\Carbon::parse($request->jam_in)->isPast()) {
                                                                        $status = 'danger';
                                                                        $text = 'Hangus';
                                                                    }
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 1) {
                                                                        $status = 'info';
                                                                        $text = 'Disetujui (Belum Keluar)';
                                                                    }
                                                                    // Cek status terlambat (sudah keluar tapi belum kembali)
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 1 && \Carbon\Carbon::parse($request->jam_in)->isPast()) {
                                                                        $status = 'warning';
                                                                        $text = 'Terlambat';
                                                                    }
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 1) {
                                                                        $status = 'info';
                                                                        $text = 'Sudah Keluar (Belum Kembali)';
                                                                    }
                                                                    elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 2) {
                                                                        $status = 'success';
                                                                        $text = 'Sudah Kembali';
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
                                                                        data-acc-security-in="{{ $request->acc_security_in }}">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </button>
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
                    <hr>
                    <h5 class="mb-3">Status Persetujuan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Admin:</strong></p>
                            <p><strong>Head Unit:</strong></p>
                            <p><strong>Security (Keluar):</strong></p>
                            <p><strong>Security (Masuk):</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p id="modal-acc-admin">
                                <span class="badge badge-warning" data-status="1">Menunggu</span>
                                <span class="badge badge-success" data-status="2">Disetujui</span>
                                <span class="badge badge-danger" data-status="3">Ditolak</span>
                            </p>
                            <p id="modal-acc-head-unit">
                                <span class="badge badge-warning" data-status="1">Menunggu</span>
                                <span class="badge badge-success" data-status="2">Disetujui</span>
                                <span class="badge badge-danger" data-status="3">Ditolak</span>
                            </p>
                            <p id="modal-acc-security-out">
                                <span class="badge badge-warning" data-status="1">Menunggu</span>
                                <span class="badge badge-success" data-status="2">Disetujui</span>
                                <span class="badge badge-danger" data-status="3">Ditolak</span>
                            </p>
                            <p id="modal-acc-security-in">
                                <span class="badge badge-warning" data-status="1">Menunggu</span>
                                <span class="badge badge-success" data-status="2">Disetujui</span>
                                <span class="badge badge-danger" data-status="3">Ditolak</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#requestDriverTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[5, "desc"]], // Urutkan berdasarkan tanggal (kolom ke-6) secara descending
                "pageLength": 10,
                "responsive": true,
                "dom": '<"top"f>rt<"bottom"lp><"clear">',
                "columnDefs": [
                    { "orderable": false, "targets": [0, 10] }, // Nonaktifkan pengurutan untuk kolom No dan Aksi
                    { "searchable": false, "targets": [0, 10] } // Nonaktifkan pencarian untuk kolom No dan Aksi
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
                $('#modal-nama-ekpedisi').text(button.data('nama-ekpedisi'));
                $('#modal-nopol').text(button.data('nopol'));
                $('#modal-nama-driver').text(button.data('nama-driver'));
                $('#modal-no-hp-driver').text(button.data('no-hp-driver'));
                
                // Handle data kernet yang nullable
                var namaKernet = button.data('nama-kernet');
                var noHpKernet = button.data('no-hp-kernet');
                
                if (namaKernet && noHpKernet) {
                    $('#modal-nama-kernet').text(namaKernet);
                    $('#modal-no-hp-kernet').text(noHpKernet);
                } else {
                    $('#modal-nama-kernet').text('-');
                    $('#modal-no-hp-kernet').text('-');
                }
                
                $('#modal-tanggal').text(button.data('tanggal'));
                $('#modal-jam-keluar').text(button.data('jam-keluar'));
                $('#modal-jam-kembali').text(button.data('jam-kembali'));
                $('#modal-keperluan').text(button.data('keperluan'));
                
                // Status Admin
                var accAdmin = button.data('acc-admin');
                var adminBadge = '';
                if (accAdmin === 2) {
                    adminBadge = '<span class="badge badge-success">Disetujui</span>';
                } else if (accAdmin === 3) {
                    adminBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    adminBadge = '<span class="badge badge-warning">Menunggu</span>';
                }
                $('#modal-acc-admin').html(adminBadge);

                // Status Head Unit - hanya bisa diproses jika Admin menyetujui
                var accHeadUnit = button.data('acc-head-unit');
                var headUnitBadge = '';
                if (accAdmin === 3) {
                    headUnitBadge = '<span class="badge badge-secondary">Tidak Diproses</span>';
                } else if (accHeadUnit === 2) {
                    headUnitBadge = '<span class="badge badge-success">Disetujui</span>';
                } else if (accHeadUnit === 3) {
                    headUnitBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    headUnitBadge = '<span class="badge badge-warning">Menunggu</span>';
                }
                $('#modal-acc-head-unit').html(headUnitBadge);

                // Status Security Keluar - hanya bisa diproses jika Head Unit menyetujui
                var accSecurityOut = button.data('acc-security-out');
                var securityOutBadge = '';
                if (accAdmin === 3 || accHeadUnit === 3) {
                    securityOutBadge = '<span class="badge badge-secondary">Tidak Diproses</span>';
                } else if (accSecurityOut === 2) {
                    securityOutBadge = '<span class="badge badge-success">Sudah Keluar</span>';
                } else if (accSecurityOut === 3) {
                    securityOutBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    securityOutBadge = '<span class="badge badge-warning">Belum Keluar</span>';
                }
                $('#modal-acc-security-out').html(securityOutBadge);

                // Status Security Masuk - hanya bisa diproses jika Security Keluar menyetujui
                var accSecurityIn = button.data('acc-security-in');
                var securityInBadge = '';
                if (accAdmin === 3 || accHeadUnit === 3 || accSecurityOut === 3) {
                    securityInBadge = '<span class="badge badge-secondary">Tidak Diproses</span>';
                } else if (accSecurityIn === 2) {
                    securityInBadge = '<span class="badge badge-success">Sudah Masuk</span>';
                } else if (accSecurityIn === 3) {
                    securityInBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    securityInBadge = '<span class="badge badge-warning">Belum Masuk</span>';
                }
                $('#modal-acc-security-in').html(securityInBadge);
            });
        });
    </script>
</body>
</html>