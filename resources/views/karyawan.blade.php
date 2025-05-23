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

    <!-- Form Izin Keluar Section -->
    <section class="min-vh-100 d-flex align-items-center bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 text-primary mb-2">
                                    <i class="bi bi-person-walking"></i> Form Izin Keluar
                                </h1>
                                <p class="text-muted">Silakan isi form izin keluar dengan lengkap</p>
                            </div>
                            <form method="POST" action="{{ route('request-karyawan.store') }}" id="karyawanForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama') }}" required
                                           placeholder="Nama lengkap karyawan">
                                    <small class="text-muted">Masukkan nama lengkap karyawan.</small>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="departemen_id" class="form-label">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-select @error('departemen_id') is-invalid @enderror" 
                                            id="departemen_id" name="departemen_id" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach($departemens as $departemen)
                                            <option value="{{ $departemen->id }}" {{ old('departemen_id') == $departemen->id ? 'selected' : '' }}>
                                                {{ $departemen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih departemen tempat Anda bekerja.</small>
                                    @error('departemen_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                              id="keperluan" name="keperluan" rows="3" required
                                              placeholder="Jelaskan keperluan pengajuan izin">{{ old('keperluan') }}</textarea>
                                    <small class="text-muted">Tuliskan keperluan pengajuan izin secara singkat dan jelas.</small>
                                    @error('keperluan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jam_out" class="form-label">Jam Keluar <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('jam_out') is-invalid @enderror" 
                                           id="jam_out" name="jam_out" value="{{ old('jam_out') }}" required>
                                    <small class="text-muted">Pilih jam berangkat/keluar dari area kerja.</small>
                                    @error('jam_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jam_in" class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('jam_in') is-invalid @enderror" 
                                           id="jam_in" name="jam_in" value="{{ old('jam_in') }}" required>
                                    <small class="text-muted">Pilih jam kembali ke area kerja (maksimal 1 jam dari jam keluar).</small>
                                    @error('jam_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Hidden approval fields -->
                                <input type="hidden" name="acc_lead" value="0">
                                <input type="hidden" name="acc_hr_ga" value="0">
                                <input type="hidden" name="acc_security_in" value="0">
                                <input type="hidden" name="acc_security_out" value="0">
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-send"></i> Ajukan Izin
                                </button>
                                <a href="{{ route('request-driver.create') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-truck"></i> Izin Driver
                                </a>
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
        document.addEventListener('DOMContentLoaded', function () {
            const jamOut = document.getElementById('jam_out');
            const jamIn = document.getElementById('jam_in');
            const infoSelisih = document.createElement('div');
            infoSelisih.className = 'text-muted small mt-1';
            jamIn.parentNode.appendChild(infoSelisih);

            // AJAX Form Submission
            const karyawanForm = document.getElementById('karyawanForm');
            karyawanForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Tampilkan alert sukses
                            $('.floating-alert').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Berhasil!</h6>
                                            <div class="small">${response.message}</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                            
                            // Reset form
                            karyawanForm.reset();
                            
                            // Reload halaman setelah 5 detik
                            setTimeout(function() {
                                location.reload();
                            }, 5000);
                        } else {
                            // Tampilkan alert error
                            $('.floating-alert').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-exclamation-circle-fill me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Gagal!</h6>
                                            <div class="small">${response.message}</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengirim data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('.floating-alert').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-exclamation-circle-fill me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Gagal!</h6>
                                        <div class="small">${errorMessage}</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    }
                });
            });

            function cekSelisihJam() {
                if (jamOut.value && jamIn.value) {
                    // Konversi ke menit
                    const [outJam, outMenit] = jamOut.value.split(':').map(Number);
                    const [inJam, inMenit] = jamIn.value.split(':').map(Number);

                    const totalOut = outJam * 60 + outMenit;
                    const totalIn = inJam * 60 + inMenit;

                    if (totalIn - totalOut > 60) {
                        // Tampilkan alert error
                        $('.floating-alert').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-exclamation-circle-fill me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Peringatan!</h6>
                                        <div class="small">Jam kembali tidak boleh lebih dari 1 jam dari jam keluar!</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                        jamIn.value = '';
                        infoSelisih.textContent = '';
                    } else {
                        const selisih = totalIn - totalOut;
                        infoSelisih.textContent = `${selisih} menit`;
                    }
                } else {
                    infoSelisih.textContent = '';
                }
            }

            function batasiJamIn() {
                if (jamOut.value) {
                    const [outJam, outMenit] = jamOut.value.split(':').map(Number);
                    const maxJam = outJam + 1;
                    const maxMenit = outMenit;

                    // Set max attribute untuk jam_in
                    jamIn.max = `${maxJam.toString().padStart(2, '0')}:${maxMenit.toString().padStart(2, '0')}`;
                    jamIn.min = jamOut.value;

                    // Jika jam_in sudah diisi dan melebihi batas, kosongkan
                    if (jamIn.value && jamIn.value > jamIn.max) {
                        jamIn.value = '';
                        infoSelisih.textContent = '';
                    }
                }
            }

            jamOut.addEventListener('change', function() {
                batasiJamIn();
                cekSelisihJam();
            });

            jamIn.addEventListener('change', cekSelisihJam);
        });
    </script>
</body>
</html>
<!-- End of Selection -->