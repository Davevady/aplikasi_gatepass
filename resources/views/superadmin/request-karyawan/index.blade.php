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
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="requestTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Departemen</th>
                                                    <th>Nama Karyawan</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Jam Kembali</th>
                                                    <th>Keperluan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach($departemens as $departemen)
                                                    @if(isset($departemen->requestKaryawans) && count($departemen->requestKaryawans) > 0)
                                                        @foreach($departemen->requestKaryawans as $request)
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>
                                                                <td>{{ $departemen->name }}</td>
                                                                <td>{{ $request->nama }}</td>
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
                                                                            $text = 'Ditolak HR GA';
                                                                        }
                                                                        // Cek urutan persetujuan sesuai alur
                                                                        elseif($request->acc_lead == 1) {
                                                                            $status = 'warning';
                                                                            $text = 'Menunggu Lead';
                                                                        }
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 1) {
                                                                            $status = 'warning';
                                                                            $text = 'Menunggu HR GA';
                                                                        }
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 2 && $request->acc_security_out == 1) {
                                                                            $status = 'info';
                                                                            $text = 'Disetujui (Belum Keluar)';
                                                                        }
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 1) {
                                                                            $status = 'info';
                                                                            $text = 'Sudah Keluar (Belum Kembali)';
                                                                        }
                                                                        elseif($request->acc_lead == 2 && $request->acc_hr_ga == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 2) {
                                                                            $status = 'success';
                                                                            $text = 'Sudah Kembali';
                                                                        }
                                                                    @endphp
                                                                    <span class="badge badge-{{ $status }}">{{ $text }}</span>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal" 
                                                                            data-nama="{{ $request->nama }}"
                                                                            data-departemen="{{ $departemen->name }}"
                                                                            data-tanggal="{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}"
                                                                            data-jam-keluar="{{ $request->jam_out }}"
                                                                            data-jam-kembali="{{ $request->jam_in }}"
                                                                            data-keperluan="{{ $request->keperluan }}"
                                                                            data-acc-lead="{{ $request->acc_lead }}"
                                                                            data-acc-hr-ga="{{ $request->acc_hr_ga }}"
                                                                            data-acc-security-out="{{ $request->acc_security_out }}"
                                                                            data-acc-security-in="{{ $request->acc_security_in }}">
                                                                        <i class="fas fa-eye"></i> Detail
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
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
                    <h5 class="modal-title" id="detailModalLabel">Detail Permohonan Izin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Karyawan:</strong></p>
                            <p><strong>Departemen:</strong></p>
                            <p><strong>Tanggal:</strong></p>
                            <p><strong>Jam Keluar:</strong></p>
                            <p><strong>Jam Kembali:</strong></p>
                            <p><strong>Keperluan:</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p id="modal-nama"></p>
                            <p id="modal-departemen"></p>
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
                            <p><strong>Lead:</strong></p>
                            <p><strong>HR/GA:</strong></p>
                            <p><strong>Security (Keluar):</strong></p>
                            <p><strong>Security (Masuk):</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p id="modal-acc-lead">
                                <span class="badge badge-warning" data-status="1">Menunggu</span>
                                <span class="badge badge-success" data-status="2">Disetujui</span>
                                <span class="badge badge-danger" data-status="3">Ditolak</span>
                            </p>
                            <p id="modal-acc-hr-ga">
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
            $('#requestTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[3, "desc"]], // Urutkan berdasarkan tanggal (kolom ke-4) secara descending
                "pageLength": 10,
                "responsive": true,
                "dom": '<"top"f>rt<"bottom"lp><"clear">',
                "columnDefs": [
                    { "orderable": false, "targets": [0, 8] }, // Nonaktifkan pengurutan untuk kolom No dan Aksi
                    { "searchable": false, "targets": [0, 8] } // Nonaktifkan pencarian untuk kolom No dan Aksi
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
                $('#modal-nama').text(button.data('nama'));
                $('#modal-departemen').text(button.data('departemen'));
                $('#modal-tanggal').text(button.data('tanggal'));
                $('#modal-jam-keluar').text(button.data('jam-keluar'));
                $('#modal-jam-kembali').text(button.data('jam-kembali'));
                $('#modal-keperluan').text(button.data('keperluan'));
                
                // Status Lead
                var accLead = button.data('acc-lead');
                var leadBadge = '';
                if (accLead === 2) {
                    leadBadge = '<span class="badge badge-success">Disetujui</span>';
                } else if (accLead === 3) {
                    leadBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    leadBadge = '<span class="badge badge-warning">Menunggu</span>';
                }
                $('#modal-acc-lead').html(leadBadge);

                // Status HR/GA - hanya bisa diproses jika Lead menyetujui
                var accHrGa = button.data('acc-hr-ga');
                var hrGaBadge = '';
                if (accLead === 3) {
                    hrGaBadge = '<span class="badge badge-secondary">Tidak Diproses</span>';
                } else if (accHrGa === 2) {
                    hrGaBadge = '<span class="badge badge-success">Disetujui</span>';
                } else if (accHrGa === 3) {
                    hrGaBadge = '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    hrGaBadge = '<span class="badge badge-warning">Menunggu</span>';
                }
                $('#modal-acc-hr-ga').html(hrGaBadge);

                // Status Security Keluar - hanya bisa diproses jika HR/GA menyetujui
                var accSecurityOut = button.data('acc-security-out');
                var securityOutBadge = '';
                if (accLead === 3 || accHrGa === 3) {
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
                if (accLead === 3 || accHrGa === 3 || accSecurityOut === 3) {
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