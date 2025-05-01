<!-- Start of Selection -->
<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.auth.head')
    <style>
        input[type="time"]::-webkit-datetime-edit-ampm-field {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('layout.auth.navbar')

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
                            <form method="POST" action="">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_nik" class="form-label">Nama/NIK</label>
                                    <input type="text" class="form-control @error('nama_nik') is-invalid @enderror" 
                                           id="nama_nik" name="nama_nik" value="{{ old('nama_nik') }}" required>
                                    @error('nama_nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="departemen" class="form-label">Departemen</label>
                                    <input type="text" class="form-control @error('departemen') is-invalid @enderror" 
                                           id="departemen" name="departemen" value="{{ old('departemen') }}" required>
                                    @error('departemen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keperluan" class="form-label">Keperluan</label>
                                    <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                              id="keperluan" name="keperluan" rows="3" required>{{ old('keperluan') }}</textarea>
                                    @error('keperluan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jam_keluar" class="form-label">Jam Keluar</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-select @error('jam_keluar') is-invalid @enderror" 
                                                    name="jam_keluar" required>
                                                <option value="">Pilih Jam</option>
                                                @for($i = 0; $i < 24; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}" 
                                                            {{ old('jam_keluar') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                        {{ sprintf('%02d', $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select @error('menit_keluar') is-invalid @enderror" 
                                                    name="menit_keluar" required>
                                                <option value="">Pilih Menit</option>
                                                @for($i = 0; $i < 60; $i += 5)
                                                    <option value="{{ sprintf('%02d', $i) }}" 
                                                            {{ old('menit_keluar') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                        {{ sprintf('%02d', $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    @error('jam_keluar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="jam_kembali" class="form-label">Jam Kembali</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-select @error('jam_kembali') is-invalid @enderror" 
                                                    name="jam_kembali" required>
                                                <option value="">Pilih Jam</option>
                                                @for($i = 0; $i < 24; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}" 
                                                            {{ old('jam_kembali') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                        {{ sprintf('%02d', $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select @error('menit_kembali') is-invalid @enderror" 
                                                    name="menit_kembali" required>
                                                <option value="">Pilih Menit</option>
                                                @for($i = 0; $i < 60; $i += 5)
                                                    <option value="{{ sprintf('%02d', $i) }}" 
                                                            {{ old('menit_kembali') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                        {{ sprintf('%02d', $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    @error('jam_kembali')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3">Ajukan Izin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle JS -->
    @include('layout.auth.script')
</body>
</html>
<!-- End of Selection -->