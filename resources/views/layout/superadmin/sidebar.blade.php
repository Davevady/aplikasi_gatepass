    <!-- Sidebar -->
    <div class="sidebar sidebar-style-2" id="sidebar">			
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <div class="user">
                    <div class="avatar-sm float-left mr-2">
                        @if(Auth::user()->photo)
                            <img src="{{ asset(Auth::user()->photo) }}" alt="image profile" class="avatar-img rounded-circle">
                        @else
                            <img src="{{ asset('images/users/default.png') }}" alt="image profile" class="avatar-img rounded-circle">
                        @endif
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                            <span>
                                {{ Auth::user()->name }}
                                <span class="user-level">{{ Auth::user()->role->title }}</span>
                                <span class="caret"></span>
                            </span>
                        </a>
                        <div class="clearfix"></div>
                        <div class="collapse in" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="{{ route('users.profile', ['id' => Auth::id()]) }}">
                                        <span class="link-collapse">Lihat Profil</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}">
                                        <span class="link-collapse">Keluar</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- <ul class="nav nav-primary">
                    <li class="nav-item" id="search-nav">
                        <div class="input-group stylish-input-group">
                            <input type="text" placeholder="Cari..." class="form-control" id="searchInputSidebar">
                        </div>
                    </li>
                </ul> -->
                <ul class="nav nav-primary" id="sidebarMenu">
                    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}" data-name="Beranda">
                        <a href="/dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            <p>Beranda</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Menu</h4>
                    </li>
                    @if(in_array(Auth::user()->role->slug, ['admin', 'security', 'lead', 'hr-ga', 'checker', 'head-unit']))
                    <li class="nav-item {{ request()->is('request-karyawan*') || request()->is('request-driver*') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#submenuGatepass">
                            <i class="fas fa-id-card"></i>
                            <p>Gate Pass</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->is('request-karyawan*') || request()->is('request-driver*') ? 'show' : '' }}" id="submenuGatepass">
                            <ul class="nav nav-collapse">
                                @if(in_array(Auth::user()->role->slug, ['admin', 'security', 'lead', 'hr-ga']))
                                <li data-name="Permohonan Karyawan" class="{{ request()->is('request-karyawan*') ? 'active' : '' }}">
                                    <a href="/request-karyawan"> 
                                        <span class="sub-item">Permohonan Karyawan</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array(Auth::user()->role->slug, ['admin', 'security', 'checker', 'head-unit']))
                                <li data-name="Permohonan Driver" class="{{ request()->is('request-driver*') ? 'active' : '' }}">
                                    <a href="/request-driver"> 
                                        <span class="sub-item">Permohonan Driver</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(Auth::user()->role->slug === 'admin')
                    <li class="nav-item {{ request()->is('users*') || request()->is('role*') || request()->is('departemen*') || request()->is('ekspedisi*') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#submenuMaster">
                            <i class="fas fa-cog"></i>
                            <p>Master Data</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->is('users*') || request()->is('role*') || request()->is('departemen*') || request()->is('ekspedisi*') ? 'show' : '' }}" id="submenuMaster">
                            <ul class="nav nav-collapse">
                                <li data-name="Hak Akses" class="{{ request()->is('role*') ? 'active' : '' }}">
                                    <a href="/role"> 
                                        <span class="sub-item">Data Role</span>
                                    </a>
                                </li>
                                <li data-name="Data Departemen" class="{{ request()->is('departemen*') ? 'active' : '' }}">
                                    <a href="/departemen"> 
                                        <span class="sub-item">Data Departemen</span>
                                    </a>
                                </li>
                                <li data-name="Data Ekspedisi" class="{{ request()->is('ekspedisi*') ? 'active' : '' }}">
                                    <a href="/ekspedisi"> 
                                        <span class="sub-item">Data Ekspedisi</span>
                                    </a>
                                </li>
                                <li data-name="Data Pengguna" class="{{ request()->is('users*') ? 'active' : '' }}">
                                    <a href="/users"> 
                                        <span class="sub-item">Data Pengguna</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#submenuNotifications">
                            <i class="fas fa-bell"></i>
                            <p>Informasi</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->is('notifications*') ? 'show' : '' }}" id="submenuNotifications">
                            <ul class="nav nav-collapse">
                                <li data-name="Notifikasi" class="{{ request()->is('notifications*') ? 'active' : '' }}">
                                    <a href="/notifications"> 
                                        <span class="sub-item">Notifikasi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->