<!DOCTYPE html>
<html lang="id">
<head>
	@include('layout.superadmin.head')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
	<div class="wrapper">
		@include('layout.superadmin.header')
        @include('layout.superadmin.alert')

        <!-- Alert Password Default -->
        @php
            $userPassword = auth()->user()->password;
            $isDefaultPassword = Hash::check('password', $userPassword);
            
            echo "<script>
                console.log('User Password Hash:', '" . $userPassword . "');
                console.log('Is Default Password:', " . ($isDefaultPassword ? 'true' : 'false') . ");
            </script>";
        @endphp

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($isDefaultPassword)
            <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Password Anda masih menggunakan password default. Untuk keamanan akun, silakan segera ubah password Anda.
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
								<h2 class="text-white pb-2 fw-bold">Dashboard SISIK</h2>
								<h5 class="text-white op-7 mb-2">Selamat datang kembali di Sistem Surat Izin Keluar</h5>
							</div>
							<div class="ml-md-auto py-2 py-md-0">
								<a href="{{ route('request-karyawan.create') }}" class="btn btn-light btn-border btn-round mr-2">Permohonan Karyawan</a>
								<a href="{{ route('request-driver.create') }}" class="btn btn-light btn-border btn-round">Permohonan Driver</a>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner mt--5">
					<div class="row mt--2">
                        @php
                            $totalCards = 2; // Default untuk status (disetujui & ditolak)
                            if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5) {
                                $totalCards++;
                            }
                            if(auth()->user()->role_id != 2 && auth()->user()->role_id != 3) {
                                $totalCards++;
                            }
                            $colClass = $totalCards == 4 ? 'col-md-3' : 'col-md-4';
                        @endphp

                        <!-- Statistik Karyawan -->
                        @if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5)
                        <div class="{{ $colClass }}">
                            <div class="card card-stats card-round" style="cursor: pointer;" onclick="window.location.href='{{ route('request-karyawan.index') }}'">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-users text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Permohonan Karyawan</p>
                                                <h4 class="card-title">{{ $totalKaryawanRequest }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Statistik Driver -->
                        @if(auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
                        <div class="{{ $colClass }}">
                            <div class="card card-stats card-round" style="cursor: pointer;" onclick="window.location.href='{{ route('request-driver.index') }}'">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-truck text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Permohonan Driver</p>
                                                <h4 class="card-title">{{ $totalDriverRequest }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Statistik Status -->
                        <div class="{{ $colClass }}">
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
                        <div class="{{ $colClass }}">
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

                        <!-- Grafik Permohonan Bulanan -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Grafik Permohonan Bulanan</div>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Status Permohonan -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Status Permohonan</div>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Permohonan Terbaru -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Permohonan Terbaru</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Jam Kembali</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
                                                @foreach($latestKaryawanRequests as $request)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $request->nama }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}</td>
                                                        <td>{{ $request->jam_out }}</td>
                                                        <td>{{ $request->jam_in }}</td>
                                                        <td>
                                                            @php
                                                                $status = 'warning';
                                                                $text = 'Menunggu';
                                                                
                                                                if($request->acc_lead == 3 || $request->acc_hr_ga == 3 || $request->acc_security_out == 3 || $request->acc_security_in == 3) {
                                                                    $status = 'danger';
                                                                    $text = 'Ditolak';
                                                                } 
                                                                elseif($request->acc_lead == 2 && $request->acc_hr_ga == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 2) {
                                                                    $status = 'success';
                                                                    $text = 'Disetujui';
                                                                }
                                                            @endphp
                                                            <span class="badge badge-{{ $status }}">{{ $text }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @endif

                                                @if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5)
                                                @foreach($latestDriverRequests as $request)
                                                    <tr>
                                                        <td>{{ $loop->iteration + count($latestKaryawanRequests) }}</td>
                                                        <td>{{ $request->nama_driver }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}</td>
                                                        <td>{{ $request->jam_out }}</td>
                                                        <td>{{ $request->jam_in }}</td>
                                                        <td>
                                                            @php
                                                                $status = 'warning';
                                                                $text = 'Menunggu';
                                                                
                                                                if($request->acc_admin == 3 || $request->acc_head_unit == 3 || $request->acc_security_out == 3 || $request->acc_security_in == 3) {
                                                                    $status = 'danger';
                                                                    $text = 'Ditolak';
                                                                } 
                                                                elseif($request->acc_admin == 2 && $request->acc_head_unit == 2 && $request->acc_security_out == 2 && $request->acc_security_in == 2) {
                                                                    $status = 'success';
                                                                    $text = 'Disetujui';
                                                                }
                                                            @endphp
                                                            <span class="badge badge-{{ $status }}">{{ $text }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
	<script>
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.floating-alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);

            // Add animation when alert appears
            $('.floating-alert').hide().fadeIn('slow');
        });

        // Grafik Permohonan Bulanan
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Permohonan Karyawan',
                    data: [
                        {{ $monthlyData['karyawan'][1] }},
                        {{ $monthlyData['karyawan'][2] }},
                        {{ $monthlyData['karyawan'][3] }},
                        {{ $monthlyData['karyawan'][4] }},
                        {{ $monthlyData['karyawan'][5] }},
                        {{ $monthlyData['karyawan'][6] }},
                        {{ $monthlyData['karyawan'][7] }},
                        {{ $monthlyData['karyawan'][8] }},
                        {{ $monthlyData['karyawan'][9] }},
                        {{ $monthlyData['karyawan'][10] }},
                        {{ $monthlyData['karyawan'][11] }},
                        {{ $monthlyData['karyawan'][12] }}
                    ],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Permohonan Driver',
                    data: [
                        {{ $monthlyData['driver'][1] }},
                        {{ $monthlyData['driver'][2] }},
                        {{ $monthlyData['driver'][3] }},
                        {{ $monthlyData['driver'][4] }},
                        {{ $monthlyData['driver'][5] }},
                        {{ $monthlyData['driver'][6] }},
                        {{ $monthlyData['driver'][7] }},
                        {{ $monthlyData['driver'][8] }},
                        {{ $monthlyData['driver'][9] }},
                        {{ $monthlyData['driver'][10] }},
                        {{ $monthlyData['driver'][11] }},
                        {{ $monthlyData['driver'][12] }}
                    ],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Status Permohonan
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Ditolak', 'Menunggu'],
                datasets: [{
                    data: [{{ $totalDisetujui }}, {{ $totalDitolak }}, {{ $totalMenunggu }}],
                    backgroundColor: [
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>