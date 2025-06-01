<!DOCTYPE html>
<html lang="id">
<head>
    @include('layout.superadmin.head')
</head>
<body>
    <div class="wrapper">
        @include('layout.superadmin.header')
        @include('layout.superadmin.alert')

        <!-- Sidebar -->
        @include('layout.superadmin.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Profil Pengguna</h2>
                                <h5 class="text-white op-7 mb-2">Detail Informasi Pengguna</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <a href="{{ url()->previous() }}" class="btn btn-light btn-border btn-round">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row">
                        <!-- Cover Photo -->
                        <div class="col-12">
                            <div class="card card-profile">
                                <div class="card-header cover-photo">
                                    <div class="profile-picture">
                                        @if($user->photo)
                                            <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="rounded-circle profile-img">
                                        @else
                                            <img src="{{ asset('images/users/default.png') }}" alt="{{ $user->name }}" class="rounded-circle profile-img">
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body profile-info">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h2 class="profile-name">{{ $user->name }}</h2>
                                            <div class="profile-meta">
                                                <span><i class="fas fa-briefcase"></i> {{ $user->role->title }}</span>
                                                <span><i class="fas fa-building"></i> {{ $user->departemen->name }}</span>
                                                <span><i class="fas fa-envelope"></i> {{ $user->email }}</span>
                                                <span><i class="fas fa-clock"></i> Bergabung {{ $user->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="profile-actions">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalEmail">
                                                    <i class="fas fa-envelope"></i> Update Email
                                                </button>
                                                <button class="btn btn-info" data-toggle="modal" data-target="#modalPassword">
                                                    <i class="fas fa-key"></i> Reset Password
                                                </button>
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalPhoto">
                                                    <i class="fas fa-camera"></i> Update Foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="col-md-8">
                            <!-- Status Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Status</h4>
                                </div>
                                <div class="card-body">
                                    <div class="status-info">
                                        <div class="status-item">
                                            <span class="status-label">Status Akun</span>
                                            <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                        <div class="status-item">
                                            <span class="status-label">Role</span>
                                            <span class="status-value">{{ $user->role->title }}</span>
                                        </div>
                                        <div class="status-item">
                                            <span class="status-label">Departemen</span>
                                            <span class="status-value">{{ $user->departemen->name }}</span>
                                        </div>
                                        <div class="status-item">
                                            <span class="status-label">Bergabung Sejak</span>
                                            <span class="status-value">{{ $user->created_at->format('d F Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notifikasi Terbaru -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Notifikasi Terbaru</h4>
                                </div>
                                <div class="card-body">
                                    @if($user->notifications->count() > 0)
                                        <div class="timeline">
                                            @foreach($user->notifications->take(5) as $notification)
                                                <div class="timeline-item">
                                                    <div class="timeline-marker"></div>
                                                    <div class="timeline-content">
                                                        <h3 class="timeline-title">{{ $notification->title }}</h3>
                                                        <p>{{ $notification->message }}</p>
                                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-center text-muted">Belum ada notifikasi</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Statistik -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Statistik</h4>
                                </div>
                                <div class="card-body">
                                    <div class="stats-item">
                                        <div class="stats-icon">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="stats-info">
                                            <span class="stats-value">{{ $user->notifications->count() }}</span>
                                            <span class="stats-label">Notifikasi</span>
                                        </div>
                                    </div>
                                    <div class="stats-item">
                                        <div class="stats-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="stats-info">
                                            <span class="stats-value">{{ $user->created_at->diffForHumans() }}</span>
                                            <span class="stats-label">Bergabung</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kontak -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Informasi Kontak</h4>
                                </div>
                                <div class="card-body">
                                    <div class="contact-info">
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fas fa-building"></i>
                                            <span>{{ $user->departemen->name }}</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fas fa-user-tag"></i>
                                            <span>{{ $user->role->title }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.superadmin.script')
        </div>
    </div>

    <!-- Modal Update Email -->
    <div class="modal fade" id="modalEmail" tabindex="-1" role="dialog" aria-labelledby="modalEmailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEmailLabel">Update Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEmail" action="{{ route('users.update-email', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Email Baru</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="modalPasswordLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPasswordLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPassword" action="{{ route('users.reset-password', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Update Photo -->
    <div class="modal fade" id="modalPhoto" tabindex="-1" role="dialog" aria-labelledby="modalPhotoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPhotoLabel">Update Foto Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPhoto" action="{{ route('users.update-photo', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="photo">Foto Baru</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .cover-photo {
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 8px 8px 0 0;
            background-image: url("{{ asset('images/profile-bg.jpg') }}");
        }

        /* Apply styles with higher specificity to override _cards.scss */
        .card-profile .profile-picture {
            position: absolute;
            bottom: -60px; /* Default position for large screens */
            left: 50px; /* Default position for large screens */
            /* Set explicit pixel width and height */
            width: 150px; /* Default width */
            height: 150px; /* Ensure height is same as width */
            border-radius: 50%;
            background: #fff;
            padding: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden; /* Ensure anything outside the circle is hidden */
            /* Ensure margin and other conflicting styles are reset */
            margin: 0; /* Reset margin */
            right: auto; /* Reset right position */
            text-align: left; /* Reset text alignment */
        }

        .profile-img {
            /* Image should fill the square container */
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover; /* This is key for maintaining aspect ratio while filling */
            border-radius: 50%; /* Ensure the image itself is rounded */
            transition: all 0.3s ease;
        }

        .profile-img[src*="default.png"] {
            object-fit: contain; /* Use contain for default image to show the whole icon */
            background-color: #f0f2f5;
            padding: 20px;
        }

        .profile-info {
            padding-top: 80px;
            padding-bottom: 20px;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .profile-meta span {
            color: #65676b;
            font-size: 14px;
        }

        .profile-meta i {
            margin-right: 5px;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .profile-actions .btn {
            width: 100%;
            margin-bottom: 5px;
        }

        .status-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .status-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .status-label {
            color: #65676b;
            font-size: 14px;
        }

        .status-value {
            font-weight: 500;
        }

        .stats-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-icon {
            width: 40px;
            height: 40px;
            background: #f0f2f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .stats-icon i {
            color: #1877f2;
        }

        .stats-info {
            display: flex;
            flex-direction: column;
        }

        .stats-value {
            font-weight: 600;
            font-size: 16px;
        }

        .stats-label {
            color: #65676b;
            font-size: 14px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-item i {
            width: 30px;
            color: #1877f2;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #1877f2;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #1877f2;
        }

        .timeline-content {
            padding: 15px;
            background: #f0f2f5;
            border-radius: 8px;
        }

        .timeline-title {
            font-size: 16px;
            margin-bottom: 5px;
            color: #1877f2;
        }

        .badge {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 20px;
        }

        /* Responsive styles - use higher specificity */
        @media (max-width: 1200px) { /* Large devices (desktops, less than 1200px) */
            .card-profile .profile-picture {
                width: 100px; /* Adjust width for medium screens */
                height: 100px; /* Ensure height is same */
                left: 20px;
                bottom: -50px; /* Adjusted position */
            }
            .profile-info {
                padding-top: 60px; /* Adjusted padding */
            }
        }

        @media (max-width: 992px) { /* Medium devices (tablets, less than 992px) */
            .card-profile .profile-picture {
                width: 100px; /* Adjust width for medium screens */
                height: 100px; /* Ensure height is same */
                left: 20px;
                bottom: -50px; /* Adjusted position */
            }
             .profile-info {
                padding-top: 60px; /* Adjusted padding */
            }

            .profile-name {
                font-size: 24px;
            }

            .profile-meta {
                gap: 10px;
            }

            .profile-meta span {
                font-size: 12px;
            }
        }

        @media (max-width: 768px) { /* Small devices (landscape phones, less than 768px) */
            .cover-photo {
                height: 200px;
            }

            .card-profile .profile-picture {
                bottom: -40px; /* Adjusted position */
                left: 15px; /* Adjusted position */
                width: 80px; /* Adjust width for tablet */
                height: 80px; /* Ensure height is same */
            }

            .profile-info {
                padding-top: 60px; /* Adjusted padding */
            }

            .profile-name {
                font-size: 24px;
            }

            .profile-meta {
                gap: 10px;
            }

            .profile-meta span {
                font-size: 12px;
            }
        }

        @media (max-width: 576px) { /* Extra small devices (portrait phones, less than 576px) */
            .cover-photo {
                height: 150px;
            }

            .card-profile .profile-picture {
                bottom: -30px; /* Adjusted position */
                left: 50%; /* Center horizontally */
                transform: translateX(-50%); /* Center horizontally */
                width: 100px; /* Adjust width for mobile */
                height: 100px; /* Ensure height is same */
            }

            .profile-info {
                padding-top: 50px; /* Adjusted padding */
                 text-align: center; /* Center text content */
            }

            .profile-name {
                font-size: 20px;
            }

            .profile-meta {
                flex-direction: column;
                gap: 5px;
            }

            .profile-actions {
                margin-top: 15px;
            }

            .profile-actions .btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        /* Hover effect */
        .profile-picture:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .profile-picture:hover .profile-img {
            filter: brightness(1.1);
        }
    </style>

    <script>
        $(document).ready(function() {
            // Reset form ketika modal ditutup
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });
        });
    </script>
</body>
</html>
