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

    <!-- Form Driver Section -->
    <section class="min-vh-100 d-flex align-items-center bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 text-primary mb-2">
                                    <i class="bi bi-truck"></i> Form Izin Keluar Driver
                                </h1>
                                <p class="text-muted">Silakan isi form driver dengan lengkap</p>
                            </div>
                            <form method="POST" action="{{ route('request-driver.store') }}" id="driverForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="ekspedisi_id" class="form-label">Nama Ekpedisi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('ekspedisi_id') is-invalid @enderror" 
                                            id="ekspedisi_id" name="ekspedisi_id" required>
                                        <option value="">Pilih Ekspedisi</option>
                                        @foreach($ekspedisis as $ekspedisi)
                                            <option value="{{ $ekspedisi->id }}" {{ old('ekspedisi_id') == $ekspedisi->id ? 'selected' : '' }}>
                                                {{ $ekspedisi->nama_ekspedisi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih nama ekspedisi pengiriman.</small>
                                    @error('ekspedisi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                 
                                <div class="mb-3">
                                    <label for="nopol_kendaraan" class="form-label">Nomor Polisi Kendaraan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nopol_kendaraan') is-invalid @enderror" 
                                           id="nopol_kendaraan" name="nopol_kendaraan" value="{{ old('nopol_kendaraan') }}" required
                                           pattern="^[A-Z]{1,2}\s\d{1,4}\s?[A-Z]{0,3}$"
                                           placeholder="Contoh: B 1234 CD"
                                           title="Format: B 1234 CD atau AB 1234 XY">
                                    <small class="text-muted">Masukkan nomor polisi sesuai format Indonesia, contoh: B 1234 CD.</small>
                                    @error('nopol_kendaraan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nama_driver" class="form-label">Nama Driver <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_driver') is-invalid @enderror" 
                                           id="nama_driver" name="nama_driver" value="{{ old('nama_driver') }}" required
                                           placeholder="Nama lengkap driver">
                                    <small class="text-muted">Masukkan nama lengkap driver.</small>
                                    @error('nama_driver')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp_driver" class="form-label">No. HP Driver <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_hp_driver') is-invalid @enderror" 
                                           id="no_hp_driver" name="no_hp_driver" value="{{ old('no_hp_driver') }}" required
                                           placeholder="08xxxxxxxxxx">
                                    <small class="text-muted">Masukkan nomor HP driver yang aktif (10-13 digit).</small>
                                    @error('no_hp_driver')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nama_kernet" class="form-label">Nama Kernet</label>
                                    <input type="text" class="form-control @error('nama_kernet') is-invalid @enderror" 
                                           id="nama_kernet" name="nama_kernet" value="{{ old('nama_kernet') }}"
                                           placeholder="Nama lengkap kernet (jika ada)">
                                    <small class="text-muted">Kosongkan jika tidak ada kernet.</small>
                                    @error('nama_kernet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp_kernet" class="form-label">No. HP Kernet</label>
                                    <input type="text" class="form-control @error('no_hp_kernet') is-invalid @enderror" 
                                           id="no_hp_kernet" name="no_hp_kernet" value="{{ old('no_hp_kernet') }}"
                                           placeholder="08xxxxxxxxxx (jika ada)">
                                    <small class="text-muted">Kosongkan jika tidak ada kernet (10-13 digit).</small>
                                    @error('no_hp_kernet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                              id="keperluan" name="keperluan" rows="3" required
                                              placeholder="Jelaskan keperluan pengajuan izin"></textarea>
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
                                    <small class="text-muted">Pilih jam berangkat/keluar kendaraan (format 24 jam).</small>
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
                                    <small class="text-muted">Pilih jam kembali kendaraan (format 24 jam, maksimal 1 jam dari jam keluar).</small>
                                    <div id="selisih_waktu" class="text-muted small mt-1"></div>
                                    @error('jam_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Hidden fields untuk menyimpan waktu lengkap -->
                                <input type="hidden" name="jam_out" id="jam_out">
                                <input type="hidden" name="jam_in" id="jam_in">
                                <!-- Hidden approval fields -->
                                <input type="hidden" name="acc_admin" value="0">
                                <input type="hidden" name="acc_head_unit" value="0">
                                <input type="hidden" name="acc_security_in" value="0">
                                <input type="hidden" name="acc_security_out" value="0">
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-send"></i> Ajukan Izin
                                </button>
                                <a href="{{ route('request-karyawan.create') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-person"></i> Izin Karyawan
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
                
                if (selisih > 60) {
                    selisihWaktu.innerHTML = '<span class="text-danger">Selisih waktu tidak boleh lebih dari 1 jam!</span>';
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
                const maxHour = outHour + 1;

                // Reset jam masuk
                jamInHour.value = '';
                jamInMinute.value = '';

                // Nonaktifkan opsi jam yang tidak valid
                Array.from(jamInHour.options).forEach(option => {
                    if (option.value) {
                        const hour = parseInt(option.value);
                        option.disabled = hour < outHour || hour > maxHour;
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
            const driverForm = document.getElementById('driverForm');
            driverForm.addEventListener('submit', function(e) {
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
                            driverForm.reset();
                            
                            // Reload halaman setelah 3 detik
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