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
								<a href="/gatepass/request" class="btn btn-light btn-border btn-round mr-2">Permohonan Izin</a>
								<a href="/gatepass/approval" class="btn btn-primary btn-round">Persetujuan Izin</a>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner mt--5">
					<div class="row mt--2">
                        <!-- Statistik Utama -->
                        <div class="col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-file-alt text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Permohonan</p>
                                                <h4 class="card-title">156</h4>
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
                                                <h4 class="card-title">120</h4>
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
                                                <h4 class="card-title">15</h4>
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
                                                <i class="fas fa-clock text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Menunggu</p>
                                                <h4 class="card-title">21</h4>
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
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Budi Santoso</td>
                                                    <td>15 Maret 2024</td>
                                                    <td>13:00 - 15:00</td>
                                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Ani Wijaya</td>
                                                    <td>14 Maret 2024</td>
                                                    <td>10:00 - 12:00</td>
                                                    <td><span class="badge badge-success">Disetujui</span></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Dedi Kurniawan</td>
                                                    <td>14 Maret 2024</td>
                                                    <td>09:00 - 11:00</td>
                                                    <td><span class="badge badge-danger">Ditolak</span></td>
                                                </tr>
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
                    label: 'Jumlah Permohonan',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 40, 33, 27, 20],
                    borderColor: 'rgb(75, 192, 192)',
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
                    data: [120, 15, 21],
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