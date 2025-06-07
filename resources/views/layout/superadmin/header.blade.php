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
            <!-- <div class="collapse" id="search-nav">
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
            </div> -->
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item toggle-nav-search hidden-caret">
                    <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                        <i class="fa fa-search"></i>
                    </a>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title d-flex justify-content-between align-items-center">
                                Notifikasi
                                <a href="#" class="small" onclick="markAllAsRead()">Tandai semua telah dibaca</a>
                            </div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    @php
                                        $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    
                                    @forelse($notifications as $notification)
                                        <a href="javascript:void(0);" class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" 
                                           onclick="showNotifDetail({{ $notification->id }})">
                                            <div class="notif-icon {{ $notification->type === 'driver' ? 'notif-warning' : ($notification->type === 'karyawan' ? 'notif-info' : 'notif-primary') }}">
                                                <i class="fa {{ $notification->type === 'driver' ? 'fa-truck' : ($notification->type === 'karyawan' ? 'fa-user' : 'fa-clock') }}"></i>
                                            </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    {{ $notification->title }}
                                                </span>
                                                <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if(!$notification->is_read)
                                                    <span class="badge badge-primary badge-pill">Baru</span>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center p-3">
                                            <span>Tidak ada notifikasi</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="{{ route('notifications.index') }}">Lihat semua notifikasi<i class="fa fa-angle-right"></i></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if(Auth::user()->photo)
                                <img src="{{ asset(Auth::user()->photo) }}" alt="..." class="avatar-img rounded-circle">
                            @else
                                <img src="{{ asset('images/users/default.png') }}" alt="..." class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset(Auth::user()->photo) }}" alt="image profile" class="avatar-img rounded">
                                        @else
                                            <img src="{{ asset('images/users/default.png') }}" alt="image profile" class="avatar-img rounded">
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <a href="{{ route('users.profile', ['id' => Auth::id()]) }}" class="btn btn-xs btn-secondary btn-sm">Lihat Profil</a>
                                    </div>
                                </div>
                            </li>
                            <li>
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

<!-- Modal Notifikasi -->
<div class="modal fade" id="notifModal" tabindex="-1" role="dialog" aria-labelledby="notifModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notifModalLabel">Detail Notifikasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 id="notifTitle"></h6>
        <p id="notifMessage"></p>
        <small id="notifTime" class="text-muted"></small>
      </div>
    </div>
  </div>
</div>

<script>
function showNotifDetail(id) {
    $.ajax({
        url: '/notification/' + id + '/show',
        method: 'GET',
        success: function(data) {
            $('#notifTitle').text(data.title);
            $('#notifMessage').text(data.message);
            $('#notifTime').text(data.created_at);
            $('#notifModal').modal('show');
            
            // Update UI to mark notification as read
            $('.notification-item[onclick*="' + id + '"]').removeClass('unread').addClass('read');
            $('.notification-item[onclick*="' + id + '"] .badge').remove();
        },
        error: function() {
            alert('Gagal mengambil data notifikasi');
        }
    });
}
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">