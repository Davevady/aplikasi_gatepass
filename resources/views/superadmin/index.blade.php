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
                            $colClass = $totalCards == 5 ? 'col-md-3' : 'col-md-4';
                        @endphp

                        <!-- Statistik Status -->
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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

                        <!-- Statistik Karyawan -->
                        @if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5)
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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

                        <div class="row">
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

                            <div class="col-md-4">
                                <!-- Grafik Status Permohonan -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <div class="card-title">Status Permohonan</div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="statusChart"></canvas>
                                    </div>
                                </div>

                                <!-- Grafik Permohonan Per Minggu -->
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="card-title">Grafik Permohonan Per Minggu</div>
                                            <select class="form-control" id="monthSelect" style="width: 200px;">
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
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="weeklyChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Permohonan Terbaru -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="card-title">Daftar Pemohon</div>
                                        <div class="d-flex">
                                            <select class="form-control mr-2" id="filterType" style="width: 150px;">
                                                <option value="all">Semua Tipe</option>
                                                <option value="Karyawan">Karyawan</option>
                                                <option value="Driver">Driver</option>
                                            </select>
                                            <select class="form-control mr-2" id="filterMonth" style="width: 150px;">
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
                                            <select class="form-control" id="filterYear" style="width: 100px;">
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="latestRequestsTable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Jam Kembali</th>
                                                    <th>Status</th>
                                                    <th>Tipe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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

    <!-- Modal Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Detail Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="statusTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Tanggal</th>
                                    <th>Jam Keluar</th>
                                    <th>Jam Kembali</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.superadmin.script')
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    
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

            // Fungsi untuk menampilkan modal status
            function showStatusModal(status) {
                let title = '';
                switch(status) {
                    case 'menunggu':
                        title = 'Data Permohonan Menunggu';
                        break;
                    case 'disetujui':
                        title = 'Data Permohonan Disetujui';
                        break;
                    case 'ditolak':
                        title = 'Data Permohonan Ditolak';
                        break;
                }
                
                $('#statusModalLabel').text(title);
                $('#statusTable tbody').empty();
                
                $.get(`/dashboard/status/${status}`, function(data) {
                    $('#statusTable tbody').empty();
                    data.forEach((item, index) => {
                        $('#statusTable tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama}</td>
                                <td>${item.departemen}</td>
                                <td>${item.tanggal}</td>
                                <td>${item.jam_out}</td>
                                <td>${item.jam_in}</td>
                                <td>${item.tipe}</td>
                                <td><span class="badge badge-${item.status}">${item.text}</span></td>
                            </tr>
                        `);
                    });
                });
                
                $('#statusModal').modal('show');
            }

            // Event click untuk card menunggu
            $('.card-stats:has(.fa-clock)').click(function() {
                showStatusModal('menunggu');
            });

            // Event click untuk card disetujui
            $('.card-stats:has(.fa-check-circle)').click(function() {
                showStatusModal('disetujui');
            });

            // Event click untuk card ditolak
            $('.card-stats:has(.fa-times-circle)').click(function() {
                showStatusModal('ditolak');
            });

            // Inisialisasi DataTable
            var table = $('#latestRequestsTable').DataTable({
                processing: true,
                serverSide: false,
                pageLength: 10,
                language: {
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
                },
                columns: [
                    { data: null, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: 'nama' },
                    { data: 'tanggal' },
                    { data: 'jam_out' },
                    { data: 'jam_in' },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            return `<span class="badge badge-${row.status}">${row.text}</span>`;
                        }
                    },
                    { data: 'tipe' }
                ]
            });

            // Fungsi untuk memuat data
            function loadData() {
                const month = $('#filterMonth').val();
                const year = $('#filterYear').val();
                const type = $('#filterType').val();
                
                $.get(`/dashboard/latest-requests?month=${month}&year=${year}`, function(data) {
                    // Filter berdasarkan tipe jika bukan 'all'
                    if (type !== 'all') {
                        data = data.filter(item => item.tipe === type);
                    }
                    
                    // Clear dan reload data
                    table.clear();
                    table.rows.add(data).draw();
                });
            }

            // Set bulan dan tahun saat ini sebagai default
            const currentDate = new Date();
            $('#filterMonth').val(currentDate.getMonth() + 1);
            $('#filterYear').val(currentDate.getFullYear());

            // Load data awal
            loadData();

            // Event change untuk filter
            $('#filterMonth, #filterYear, #filterType').change(function() {
                loadData();
            });

            // Inisialisasi grafik per minggu
            let weeklyChart = null;
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            function updateWeeklyChart(month) {
                $.get(`/dashboard/weekly/${month}`, function(data) {
                    const weeks = Object.keys(data.karyawan).map(week => `Minggu ${week}`);
                    
                    if (weeklyChart) {
                        weeklyChart.destroy();
                    }

                    const ctx = document.getElementById('weeklyChart').getContext('2d');
                    weeklyChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: weeks,
                            datasets: [
                                @if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5)
                                {
                                    label: 'Permohonan Karyawan',
                                    data: Object.values(data.karyawan),
                                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                    borderColor: 'rgb(75, 192, 192)',
                                    borderWidth: 1
                                },
                                @endif
                                @if(auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
                                {
                                    label: 'Permohonan Driver',
                                    data: Object.values(data.driver),
                                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                    borderColor: 'rgb(255, 99, 132)',
                                    borderWidth: 1
                                }
                                @endif
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });
            }

            // Set bulan saat ini sebagai default
            const currentMonth = new Date().getMonth() + 1;
            $('#monthSelect').val(currentMonth);
            updateWeeklyChart(currentMonth);

            // Event change untuk select bulan
            $('#monthSelect').change(function() {
                updateWeeklyChart($(this).val());
            });
        });

        // Grafik Permohonan Bulanan
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    @if(auth()->user()->role_id != 4 && auth()->user()->role_id != 5)
                    {
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
                    @endif
                    @if(auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
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
                    }
                    @endif
                ]
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