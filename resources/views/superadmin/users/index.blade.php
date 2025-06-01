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
                                <h2 class="text-white pb-2 fw-bold">Data Pengguna</h2>
                                <h5 class="text-white op-7 mb-2">Pengelolaan Data Pengguna</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <button type="button" class="btn btn-light btn-border btn-round" data-toggle="modal" data-target="#modalAdd">
                                    <i class="fas fa-plus"></i> Tambah Pengguna
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="userTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Foto</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Departemen</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $index => $user)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($user->photo)
                                                            <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                                        @else
                                                            <img src="{{ asset('images/users/default.png') }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->departemen->name }}</td>
                                                    <td>{{ $user->role->title }}</td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input toggle-status" 
                                                                id="statusSwitch{{ $user->id }}" 
                                                                data-id="{{ $user->id }}"
                                                                {{ $user->is_active ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="statusSwitch{{ $user->id }}">
                                                                <span class="switch-label">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(auth()->user()->role && auth()->user()->role->slug == 'admin')
                                                            <a href="{{ route('users.profile', $user->id) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                        @endif

                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalEdit" 
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}"
                                                            data-departemen="{{ $user->departemen_id }}"
                                                            data-role="{{ $user->role_id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEmail"
                                                            data-id="{{ $user->id }}"
                                                            data-email="{{ $user->email }}">
                                                            <i class="fas fa-envelope"></i> Email
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modalPassword"
                                                            data-id="{{ $user->id }}">
                                                            <i class="fas fa-key"></i> Password
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-warning reset-password-default" 
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}">
                                                            <i class="fas fa-sync"></i> Reset Password
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalPhoto"
                                                            data-id="{{ $user->id }}">
                                                            <i class="fas fa-camera"></i> Foto
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete"
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAdd" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="departemen_id">Departemen</label>
                            <select class="form-control" id="departemen_id" name="departemen_id" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departemens as $departemen)
                                    <option value="{{ $departemen->id }}">{{ $departemen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role_id">Role</label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_departemen_id">Departemen</label>
                            <select class="form-control" id="edit_departemen_id" name="departemen_id" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departemens as $departemen)
                                    <option value="{{ $departemen->id }}">{{ $departemen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_role_id">Role</label>
                            <select class="form-control" id="edit_role_id" name="role_id" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
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
                <form id="formEmail" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="email_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Email Baru</label>
                            <input type="email" class="form-control" id="email" name="email" required>
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
                <form id="formPassword" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="password_id" name="id">
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
                <form id="formPhoto" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="photo_id" name="id">
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

    <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna <strong id="delete-name"></strong>?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table table td {
            white-space: nowrap;
        }

        #userTable th,
        #userTable td {
            white-space: nowrap !important;
            min-width: 150px;
        }

        #userTable th:nth-child(1),
        #userTable td:nth-child(1) {
            min-width: 50px;
        }

        #userTable th:nth-child(2),
        #userTable td:nth-child(2) {
            min-width: 60px;
        }

        #userTable th:last-child,
        #userTable td:last-child {
            min-width: 300px;
        }

        /* Custom Switch Styles */
        .custom-switch {
            padding-left: 3rem;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .custom-control-input:not(:checked) ~ .custom-control-label::before {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .custom-control-label {
            padding-left: 0.5rem;
        }

        .switch-label {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .custom-control-input:checked ~ .custom-control-label .switch-label {
            color: #28a745;
        }

        .custom-control-input:not(:checked) ~ .custom-control-label .switch-label {
            color: #dc3545;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#userTable').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir", 
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "responsive": true,
                "scrollX": true,
                "autoWidth": false,
                "dom": '<"top"f>rt<"bottom"lp><"clear">',
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1, 6, 7] },
                    { "searchable": false, "targets": [0, 1, 6, 7] }
                ]
            });

            // Toggle Status dengan loading state dan refresh
            $('.toggle-status').change(function() {
                const checkbox = $(this);
                const id = checkbox.data('id');
                const isActive = checkbox.prop('checked');
                const label = checkbox.next('label').find('.switch-label');
                
                // Disable checkbox selama proses
                checkbox.prop('disabled', true);
                
                // Update label
                label.text(isActive ? 'Aktif' : 'Nonaktif');
                
                $.ajax({
                    url: `/users/${id}/toggle-active`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        is_active: isActive
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Status pengguna berhasil diubah',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            // Refresh halaman setelah alert
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Reset checkbox state
                        checkbox.prop('checked', !isActive);
                        
                        // Reset label
                        label.text(!isActive ? 'Aktif' : 'Nonaktif');
                        
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengubah status pengguna',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    complete: function() {
                        // Re-enable checkbox
                        checkbox.prop('disabled', false);
                    }
                });
            });

            // Event handler untuk tombol edit
            $('#modalEdit').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var departemen = button.data('departemen');
                var role = button.data('role');
                
                var modal = $(this);
                modal.find('#edit_id').val(id);
                modal.find('#edit_name').val(name);
                modal.find('#edit_departemen_id').val(departemen);
                modal.find('#edit_role_id').val(role);
                modal.find('#formEdit').attr('action', `/users/update-basic-info/${id}`);
            });

            // Event handler untuk tombol update email
            $('#modalEmail').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var email = button.data('email');
                
                var modal = $(this);
                modal.find('#email_id').val(id);
                modal.find('#email').val(email);
                modal.find('#formEmail').attr('action', `/users/update-email/${id}`);
            });

            // Event handler untuk tombol reset password
            $('#modalPassword').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                
                var modal = $(this);
                modal.find('#password_id').val(id);
                modal.find('#formPassword').attr('action', `/users/reset-password/${id}`);
            });

            // Event handler untuk tombol update photo
            $('#modalPhoto').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                
                var modal = $(this);
                modal.find('#photo_id').val(id);
                modal.find('#formPhoto').attr('action', `/users/update-photo/${id}`);
            });

            // Delete User
            $('#modalDelete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                
                $('#delete-name').text(name);
                $('#confirmDelete').data('id', id);
            });

            $('#confirmDelete').click(function() {
                const id = $(this).data('id');
                
                // Buat form untuk submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/users/delete/${id}`
                });

                // Tambahkan CSRF token
                form.append($('<input>', {
                    'name': '_token',
                    'value': '{{ csrf_token() }}',
                    'type': 'hidden'
                }));

                // Tambahkan method DELETE
                form.append($('<input>', {
                    'name': '_method',
                    'value': 'DELETE',
                    'type': 'hidden'
                }));

                // Tambahkan form ke body dan submit
                $('body').append(form);
                form.submit();
            });

            // Reset form ketika modal ditutup
            $('#modalAdd').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });

            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });

            // Buka modal jika ada error
            @if(session('modal') == 'edit')
                $('#modalEdit').modal('show');
            @endif

            @if(session('modal') == 'email')
                $('#modalEmail').modal('show');
            @endif

            @if(session('modal') == 'password')
                $('#modalPassword').modal('show');
            @endif

            @if(session('modal') == 'photo')
                $('#modalPhoto').modal('show');
            @endif

            // Reset Password Default
            $('.reset-password-default').click(function() {
                const button = $(this);
                const id = button.data('id');
                const name = button.data('name');
                
                Swal.fire({
                    title: 'Reset Password Default?',
                    text: `Password untuk ${name} akan direset menjadi "password"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Disable button
                        button.prop('disabled', true);
                        
                        $.ajax({
                            url: `/users/${id}/reset-password-default`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mereset password',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            complete: function() {
                                // Re-enable button
                                button.prop('disabled', false);
                            }
                        });
                    }
                });
            });
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
