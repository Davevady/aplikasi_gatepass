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
                                    <label for="nama_ekspedisi" class="form-label">Nama Ekpedisi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('nama_ekspedisi') is-invalid @enderror" 
                                            id="nama_ekspedisi" name="nama_ekspedisi" required>
                                        <option value="">Pilih Ekpedisi</option>
                                        <option value="PT. Jaya Abadi Logistik" {{ old('nama_ekspedisi') == 'PT. Jaya Abadi Logistik' ? 'selected' : '' }}>PT. Jaya Abadi Logistik</option>
                                        <option value="PT. Sinar Jaya Express" {{ old('nama_ekspedisi') == 'PT. Sinar Jaya Express' ? 'selected' : '' }}>PT. Sinar Jaya Express</option>
                                        <option value="PT. Mitra Cargo Indonesia" {{ old('nama_ekspedisi') == 'PT. Mitra Cargo Indonesia' ? 'selected' : '' }}>PT. Mitra Cargo Indonesia</option>
                                        <option value="PT. Prima Logistik Nusantara" {{ old('nama_ekspedisi') == 'PT. Prima Logistik Nusantara' ? 'selected' : '' }}>PT. Prima Logistik Nusantara</option>
                                        <option value="PT. Trans Cargo Mandiri" {{ old('nama_ekspedisi') == 'PT. Trans Cargo Mandiri' ? 'selected' : '' }}>PT. Trans Cargo Mandiri</option>
                                    </select>
                                    <small class="text-muted">Pilih nama ekspedisi pengiriman.</small>
                                    @error('nama_ekspedisi')
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
                                    <input type="time" class="form-control @error('jam_out') is-invalid @enderror" 
                                           id="jam_out" name="jam_out" value="{{ old('jam_out') }}" required>
                                    <small class="text-muted">Pilih jam berangkat/keluar kendaraan.</small>
                                    @error('jam_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jam_in" class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('jam_in') is-invalid @enderror" 
                                           id="jam_in" name="jam_in" value="{{ old('jam_in') }}" required>
                                    <small class="text-muted">Pilih jam kembali kendaraan (maksimal 1 jam dari jam keluar).</small>
                                    @error('jam_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
            const jamOut = document.getElementById('jam_out');
            const jamIn = document.getElementById('jam_in');
            const infoSelisih = document.createElement('div');
            infoSelisih.className = 'text-muted small mt-1';
            jamIn.parentNode.appendChild(infoSelisih);

            // Validasi nomor HP
            const noHpDriver = document.getElementById('no_hp_driver');
            const noHpKernet = document.getElementById('no_hp_kernet');
            const driverForm = document.getElementById('driverForm');

            function validatePhoneNumber(input) {
                // Hapus semua karakter non-digit
                let value = input.value.replace(/\D/g, '');
                
                // Pastikan dimulai dengan 08
                if (value.length > 0 && !value.startsWith('08')) {
                    input.setCustomValidity('Nomor HP harus dimulai dengan 08');
                    return false;
                }
                
                // Validasi panjang
                if (value.length > 0 && (value.length < 10 || value.length > 13)) {
                    input.setCustomValidity('Nomor HP harus 10-13 digit');
                    return false;
                }
                
                // Format ulang nilai input
                input.value = value;
                input.setCustomValidity('');
                return true;
            }

            // Event listener untuk input nomor HP
            noHpDriver.addEventListener('input', function() {
                validatePhoneNumber(this);
            });

            noHpKernet.addEventListener('input', function() {
                validatePhoneNumber(this);
            });

            // Event listener untuk paste
            noHpDriver.addEventListener('paste', function(e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('text');
                let numericValue = pastedData.replace(/\D/g, '');
                this.value = numericValue;
                validatePhoneNumber(this);
            });

            noHpKernet.addEventListener('paste', function(e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('text');
                let numericValue = pastedData.replace(/\D/g, '');
                this.value = numericValue;
                validatePhoneNumber(this);
            });

            // Event listener untuk keydown
            noHpDriver.addEventListener('keydown', function(e) {
                // Izinkan: backspace, delete, tab, escape, enter, arrow keys
                if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                    // Izinkan: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true) ||
                    // Izinkan: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                // Izinkan hanya angka
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

            noHpKernet.addEventListener('keydown', function(e) {
                // Izinkan: backspace, delete, tab, escape, enter, arrow keys
                if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                    // Izinkan: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true) ||
                    // Izinkan: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                // Izinkan hanya angka
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

            // AJAX Form Submission
            driverForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validatePhoneNumber(noHpDriver) || !validatePhoneNumber(noHpKernet)) {
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

            function cekSelisihJam() {
                if (jamOut.value && jamIn.value) {
                    // Konversi ke menit
                    const [outJam, outMenit] = jamOut.value.split(':').map(Number);
                    const [inJam, inMenit] = jamIn.value.split(':').map(Number);

                    const totalOut = outJam * 60 + outMenit;
                    const totalIn = inJam * 60 + inMenit;

                    if (totalIn - totalOut > 60) {
                        alert('Jam kembali tidak boleh lebih dari 1 jam dari jam keluar!');
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