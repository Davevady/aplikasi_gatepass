<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="blue">
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar" data-toggle="collapse" data-target=".logo-header">
                <i class="icon-menu"></i>
            </button>
        </div>
        <a href="/" class="logo navbar-brand text-white d-none d-lg-block">
            <i class="bi bi-door-open"></i> SISIK
        </a>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
        <div class="container-fluid">
            <div class="collapse" id="search-nav">
                <form class="navbar-left navbar-form nav-search mr-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-search pr-1">
                                <i class="fa fa-search search-icon"></i>
                            </button>
                        </div>
                        <input type="text" placeholder="Cari surat izin..." class="form-control">
                    </div>
                </form>
            </div>
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item toggle-nav-search hidden-caret">
                    <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                        <i class="fa fa-search"></i>
                    </a>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-envelope"></i>
                    </a>
                    <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                        <li>
                            <div class="dropdown-title d-flex justify-content-between align-items-center">
                                Pesan                                    
                                <a href="#" class="small">Tandai semua telah dibaca</a>
                            </div>
                        </li>
                        <li>
                            <div class="message-notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    <a href="#">
                                        <div class="notif-img"> 
                                            <img src="../assets/img/profile1.jpg" alt="Foto Profil">
                                        </div>
                                        <div class="notif-content">
                                            <span class="subject">Admin SISIK</span>
                                            <span class="block">
                                                Surat izin keluar Anda telah disetujui
                                            </span>
                                            <span class="time">5 menit yang lalu</span> 
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-img"> 
                                            <img src="../assets/img/profile2.jpg" alt="Foto Profil">
                                        </div>
                                        <div class="notif-content">
                                            <span class="subject">HRD</span>
                                            <span class="block">
                                                Mohon lengkapi dokumen pendukung
                                            </span>
                                            <span class="time">12 menit yang lalu</span> 
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);">Lihat semua pesan<i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="notification">4</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">Ada 4 notifikasi baru</div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    <a href="#">
                                        <div class="notif-icon notif-primary"> <i class="fa fa-check-circle"></i> </div>
                                        <div class="notif-content">
                                            <span class="block">
                                                Surat izin keluar Anda telah disetujui
                                            </span>
                                            <span class="time">5 menit yang lalu</span> 
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-success"> <i class="fa fa-clock"></i> </div>
                                        <div class="notif-content">
                                            <span class="block">
                                                Surat izin keluar menunggu persetujuan
                                            </span>
                                            <span class="time">12 menit yang lalu</span> 
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);">Lihat semua notifikasi<i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="../assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg"><img src="../assets/img/profile.jpg" alt="image profile" class="avatar-img rounded"></div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <a href="/profile" class="btn btn-xs btn-secondary btn-sm">Lihat Profil</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/profile">Profil Saya</a>
                                <a class="dropdown-item" href="/surat-izin">Surat Izin Saya</a>
                                <a class="dropdown-item" href="/pesan">Pesan Masuk</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/pengaturan">Pengaturan Akun</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">Keluar</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>