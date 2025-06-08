<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-door-open-fill"></i> SISIK
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @if(!Request::is('login'))
                    @if(auth()->check())
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('search') }}">
                                <i class="bi bi-search me-1"></i> Cari Surat
                            </a>
                        </li>
                    @endif
                @endif
                @if(Request::is('login'))
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('request-karyawan.create') }}">
                        <i class="bi bi-person-walking me-1"></i> Izin Keluar Karyawan
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('request-driver.create') }}">
                        <i class="bi bi-truck me-1"></i> Izin Keluar Driver
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>