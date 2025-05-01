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
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold shadow-sm me-2" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                </li>
                @endif
                @if(!Request::is('/'))
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm" href="{{ route('landing') }}">
                        <i class="bi bi-person-walking me-1"></i> Izin Keluar
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>