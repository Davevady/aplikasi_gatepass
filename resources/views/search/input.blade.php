<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.auth.head')
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
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('layout.auth.navbar')

    <!-- Alert Section -->
    @include('layout.superadmin.alert')

    <!-- Input Section -->
    <section class="min-vh-100 d-flex align-items-center bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 text-primary mb-2">
                                    <i class="bi bi-search"></i> Cari Surat Izin
                                </h1>
                                <p class="text-muted">Masukkan nomor surat untuk mencari data surat izin</p>
                            </div>

                            <form method="GET" action="{{ route('search') }}" id="searchForm">
                                <div class="mb-3">
                                    <label for="no_surat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_surat') is-invalid @enderror" 
                                           id="no_surat" name="no_surat" value="{{ old('no_surat') }}" required
                                           placeholder="Masukkan nomor surat">
                                    <small class="text-muted">Masukkan nomor surat yang ingin dicari.</small>
                                    @error('no_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-search"></i> Cari Surat
                                </button>

                                <div class="text-center">
                                    <a href="{{ route('request-driver.create') }}" class="btn btn-outline-primary me-2">
                                        <i class="bi bi-truck"></i> Izin Driver
                                    </a>
                                    <a href="{{ route('request-karyawan.create') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-person"></i> Izin Karyawan
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle JS -->
    @include('layout.auth.script')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                
                const noSurat = $('#no_surat').val().trim();
                
                if (!noSurat) {
                    $('.floating-alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-circle-fill me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Peringatan!</h6>
                                    <div class="small">Nomor surat tidak boleh kosong!</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return;
                }

                window.location.href = "{{ route('search') }}?no_surat=" + encodeURIComponent(noSurat);
            });
        });
    </script>
</body>
</html> 