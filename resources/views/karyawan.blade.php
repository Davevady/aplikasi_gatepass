<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.auth.head')
    <style>
        input[type="time"]::-webkit-datetime-edit-ampm-field {
            display: none;
        }
        input[type="time"]::-webkit-calendar-picker-indicator {
            background: none;
        }
        input[type="time"] {
            -moz-appearance: textfield;
        }
        input[type="time"]::-webkit-inner-spin-button,
        input[type="time"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
                                    <i class="bi bi-person-walking"></i> Form Izin Keluar Karyawan
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
                                    <label for="no_telp" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                               id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required
                                               placeholder="Contoh: 081234567890">
                                    </div>
                                    <small class="text-muted">Masukkan nomor telepon aktif untuk notifikasi WhatsApp.</small>
                                    @error('no_telp')
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
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <select class="form-select @error('jam_out') is-invalid @enderror" 
                                                id="jam_out_hour" name="jam_out_hour" required>
                                            <option value="">Jam</option>
                                            @for($i = 0; $i < 24; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                                    {{ old('jam_out_hour') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-text">:</span>
                                        <select class="form-select @error('jam_out') is-invalid @enderror" 
                                                id="jam_out_minute" name="jam_out_minute" required>
                                            <option value="">Menit</option>
                                            @for($i = 0; $i < 60; $i += 5)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ old('jam_out_minute') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-text">WIB</span>
                                    </div>
                                    <small class="text-muted">Pilih jam berangkat/keluar dari area kerja (format 24 jam).</small>
                                    @error('jam_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jam_in" class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                                        <select class="form-select @error('jam_in') is-invalid @enderror" 
                                                id="jam_in_hour" name="jam_in_hour" required>
                                            <option value="">Jam</option>
                                            @for($i = 0; $i < 24; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ old('jam_in_hour') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-text">:</span>
                                        <select class="form-select @error('jam_in') is-invalid @enderror" 
                                                id="jam_in_minute" name="jam_in_minute" required>
                                            <option value="">Menit</option>
                                            @for($i = 0; $i < 60; $i += 5)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ old('jam_in_minute') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-text">WIB</span>
                                    </div>
                                    <small class="text-muted">Pilih jam kembali ke area kerja (format 24 jam).</small>
                                    <div id="selisih_waktu" class="text-muted small mt-1"></div>
                                    @error('jam_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Hidden fields untuk menyimpan waktu lengkap -->
                                <input type="hidden" name="jam_out" id="jam_out">
                                <input type="hidden" name="jam_in" id="jam_in">
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
            // Format nomor telepon
            const noTelpInput = document.getElementById('no_telp');
            noTelpInput.addEventListener('input', function(e) {
                // Hapus semua karakter non-angka
                let value = e.target.value.replace(/\D/g, '');
                
                // Batasi panjang nomor
                if (value.length > 15) {
                    value = value.slice(0, 15);
                }
                
                // Update nilai input
                e.target.value = value;
            });

            const jamOutHour = document.getElementById('jam_out_hour');
            const jamOutMinute = document.getElementById('jam_out_minute');
            const jamInHour = document.getElementById('jam_in_hour');
            const jamInMinute = document.getElementById('jam_in_minute');
            const jamOutHidden = document.getElementById('jam_out');
            const jamInHidden = document.getElementById('jam_in');
            const selisihWaktu = document.getElementById('selisih_waktu');

            // Fungsi untuk menggabungkan jam dan menit
            function gabungkanWaktu(hour, minute) {
                return `${hour}:${minute}`;
            }

            // Fungsi untuk memperbarui nilai hidden input
            function updateHiddenTime() {
                if (jamOutHour.value && jamOutMinute.value) {
                    jamOutHidden.value = gabungkanWaktu(jamOutHour.value, jamOutMinute.value);
                }
                if (jamInHour.value && jamInMinute.value) {
                    jamInHidden.value = gabungkanWaktu(jamInHour.value, jamInMinute.value);
                }
            }

            // Fungsi untuk menghitung selisih waktu dalam menit
            function hitungSelisihMenit(waktu1, waktu2) {
                const [jam1, menit1] = waktu1.split(':').map(Number);
                const [jam2, menit2] = waktu2.split(':').map(Number);
                return (jam2 * 60 + menit2) - (jam1 * 60 + menit1);
            }

            // Fungsi untuk memvalidasi jam masuk
            function validasiJamMasuk() {
                if (!jamOutHour.value || !jamOutMinute.value || !jamInHour.value || !jamInMinute.value) return;

                const waktuKeluar = gabungkanWaktu(jamOutHour.value, jamOutMinute.value);
                const waktuKembali = gabungkanWaktu(jamInHour.value, jamInMinute.value);
                const selisih = hitungSelisihMenit(waktuKeluar, waktuKembali);
                
                if (selisih <= 0) {
                    selisihWaktu.innerHTML = '<span class="text-danger">Jam kembali harus lebih besar dari jam keluar!</span>';
                    jamInHour.value = '';
                    jamInMinute.value = '';
                    return false;
                }

                selisihWaktu.innerHTML = `<span class="text-success">Durasi izin: ${selisih} menit</span>`;
                return true;
            }

            // Fungsi untuk membatasi pilihan jam masuk
            function batasiJamMasuk() {
                if (!jamOutHour.value || !jamOutMinute.value) return;

                const outHour = parseInt(jamOutHour.value);
                const outMinute = parseInt(jamOutMinute.value);

                // Reset jam masuk
                jamInHour.value = '';
                jamInMinute.value = '';

                // Nonaktifkan opsi jam yang tidak valid
                Array.from(jamInHour.options).forEach(option => {
                    if (option.value) {
                        const hour = parseInt(option.value);
                        option.disabled = hour < outHour;
                    }
                });

                // Jika jam sama, nonaktifkan menit yang lebih kecil
                if (jamInHour.value === jamOutHour.value) {
                    Array.from(jamInMinute.options).forEach(option => {
                        if (option.value) {
                            option.disabled = parseInt(option.value) <= outMinute;
                        }
                    });
                }
            }

            // Event listeners
            [jamOutHour, jamOutMinute].forEach(input => {
                input.addEventListener('change', function() {
                    updateHiddenTime();
                    batasiJamMasuk();
                    if (jamInHour.value && jamInMinute.value) {
                        validasiJamMasuk();
                    }
                });
            });

            [jamInHour, jamInMinute].forEach(input => {
                input.addEventListener('change', function() {
                    updateHiddenTime();
                    validasiJamMasuk();
                });
            });

            // Modifikasi AJAX form submission
            const karyawanForm = document.getElementById('karyawanForm');
            karyawanForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validasi jam sebelum submit
                if (!validasiJamMasuk()) {
                    $('.floating-alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-circle-fill me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Peringatan!</h6>
                                    <div class="small">Silakan periksa kembali waktu izin Anda!</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return;
                }

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
        });
    </script>
</body>
</html>
<!-- End of Selection -->