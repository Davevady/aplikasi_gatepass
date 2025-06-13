<!DOCTYPE html>
<html lang="id">
<head>
	@include('layout.superadmin.head')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @php
        $userPassword = auth()->user()->password;
        $isDefaultPassword = Hash::check('password', $userPassword);
    @endphp
    <script>
        console.log('User Password Hash:', '{{ $userPassword }}');
        console.log('Is Default Password:', {{ $isDefaultPassword ? 'true' : 'false' }});

        // Mendefinisikan variabel global untuk role_id dan data grafik
        const currentUserRoleId = @json(auth()->user()->role_id);
        const monthlyKaryawanData = @json(array_values($monthlyData['karyawan']));
        const monthlyDriverData = @json(array_values($monthlyData['driver']));
        const totalStatusData = @json([$totalDisetujui, $totalDitolak, $totalMenunggu]);
    </script>
	<div class="wrapper">
		@include('layout.superadmin.header')
        @include('layout.superadmin.alert')

        <!-- Alert Password Default -->

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
                            <div class="card card-stats card-round" style="cursor: pointer;" onclick="window.location.href='{{ route('request-karyawan.index') }}';">
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
                            <div class="card card-stats card-round" style="cursor: pointer;" onclick="window.location.href='{{ route('request-driver.index') }}';">
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
                                            <select class="form-control mr-2" id="filterYear" style="width: 100px;">
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
                                                            <input class="form-check-input" type="radio" name="previewType" id="previewFiltered" value="filtered" checked>
                                                            <label class="form-check-label" for="previewFiltered">
                                                                Data yang Ditampilkan
                                                            </label>
                                                        </div>
                                                        <div class="form-check ml-2">
                                                            <input class="form-check-input" type="radio" name="previewType" id="previewAll" value="all">
                                                            <label class="form-check-label" for="previewAll">
                                                                Semua Data
                                                            </label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <button type="button" class="btn btn-info btn-sm btn-block" onclick="previewPDF()">
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
                                                            <input class="form-check-input" type="radio" name="pdfType" id="pdfFiltered" value="filtered" checked>
                                                            <label class="form-check-label" for="pdfFiltered">
                                                                Data yang Ditampilkan
                                                            </label>
                                                        </div>
                                                        <div class="form-check ml-2">
                                                            <input class="form-check-input" type="radio" name="pdfType" id="pdfAll" value="all">
                                                            <label class="form-check-label" for="pdfAll">
                                                                Semua Data
                                                            </label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <button type="button" class="btn btn-primary btn-sm btn-block" onclick="exportData('pdf')">
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
                                                            <input class="form-check-input" type="radio" name="excelType" id="excelFiltered" value="filtered" checked>
                                                            <label class="form-check-label" for="excelFiltered">
                                                                Data yang Ditampilkan
                                                            </label>
                                                        </div>
                                                        <div class="form-check ml-2">
                                                            <input class="form-check-input" type="radio" name="excelType" id="excelAll" value="all">
                                                            <label class="form-check-label" for="excelAll">
                                                                Semua Data
                                                            </label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <button type="button" class="btn btn-success btn-sm btn-block" onclick="exportData('excel')">
                                                            <i class="fas fa-file-excel"></i> Export Excel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="latestRequestsTable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>No Surat</th>
                                                    <th>Nama</th>
                                                    <th>No Telp</th>
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
                                    <th>No Surat</th>
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
                    { data: 'no_surat' },
                    { data: 'nama' },
                    { data: 'no_telp' },
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
                
                $.get(`/dashboard/latest-requests?month=${month}&year=${year}&dataType=${type}`, function(data) {
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
                    const datasetsWeekly = [];

                    // Menggunakan variabel yang sudah didefinisikan (global)
                    if (currentUserRoleId != 4 && currentUserRoleId != 5) {
                        datasetsWeekly.push({
                            label: 'Permohonan Karyawan',
                            data: Object.values(data.karyawan),
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 1
                        });
                    }

                    if (currentUserRoleId != 2 && currentUserRoleId != 3) {
                        datasetsWeekly.push({
                            label: 'Permohonan Driver',
                            data: Object.values(data.driver),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1
                        });
                    }

                    weeklyChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: weeks,
                            datasets: datasetsWeekly
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
        const monthlyDatasets = [];

        // Menggunakan variabel yang sudah didefinisikan (global)
        if (currentUserRoleId != 4 && currentUserRoleId != 5) {
            monthlyDatasets.push({
                label: 'Permohonan Karyawan',
                data: monthlyKaryawanData,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            });
        }

        // Menggunakan variabel yang sudah didefinisikan (global)
        if (currentUserRoleId != 2 && currentUserRoleId != 3) {
            monthlyDatasets.push({
                label: 'Permohonan Driver',
                data: monthlyDriverData,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            });
        }

        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: monthlyDatasets
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
                    data: totalStatusData,
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

        function previewPDF() {
            const month = $('#filterMonth').val();
            const year = $('#filterYear').val();
            const dataType = $('#filterType').val();
            const exportType = $('input[name="previewType"]:checked').val();
            
            const url = `/export/dashboard/preview/${month}/${year}/${dataType}?type=${exportType}`;
            window.open(url, '_blank');
        }

        function exportData(format) {
            const month = $('#filterMonth').val();
            const year = $('#filterYear').val();
            const dataType = $('#filterType').val();
            const exportType = $(`input[name="${format}Type"]:checked`).val();
            
            const url = `/export/dashboard/${format}/${month}/${year}/${dataType}?type=${exportType}`;
            window.location.href = url;
        }
    </script>
    <style>
        /* Custom styles for the dropdown menu */
        .btn-group .dropdown-menu {
            min-width: 220px; /* Lebar minimum untuk dropdown */
            border-radius: 8px; /* Sudut membulat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Bayangan lembut */
            padding: 15px; /* Padding lebih besar */
            background-color: #ffffff; /* Latar belakang putih */
        }

        .btn-group .dropdown-menu .form-check {
            margin-bottom: 10px; /* Jarak antar radio button */
        }

        .btn-group .dropdown-menu .form-check-label {
            font-size: 1rem; /* Ukuran font label */
            color: #333; /* Warna teks */
            margin-left: 5px; /* Jarak antara radio dan label */
        }

        .btn-group .dropdown-menu .form-check-input[type="radio"] {
            width: 1.1em; /* Ukuran radio button */
            height: 1.1em; /* Ukuran radio button */
            margin-top: 0.25em; /* Penyelarasan vertikal */
        }

        .btn-group .dropdown-menu hr {
            border-top: 1px solid #eee; /* Garis pemisah lebih tipis */
            margin: 10px 0; /* Margin garis */
        }

        .btn-group .dropdown-menu .btn-sm {
            padding: 8px 15px; /* Padding tombol di dalam dropdown */
            font-size: 0.95rem; /* Ukuran font tombol */
            border-radius: 6px; /* Sudut membulat tombol */
        }
    </style>
</body>
</html>