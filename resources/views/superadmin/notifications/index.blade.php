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
                                <h2 class="text-white pb-2 fw-bold">{{ $title }}</h2>
                                <h5 class="text-white op-7 mb-2">Pengelolaan Data Notifikasi</h5>
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
                                        <table id="notificationTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Judul</th>
                                                    <th>Pesan</th>
                                                    <th>Tanggal</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($notifications as $index => $notification)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $notification->title }}</td>
                                                    <td>{{ Str::limit($notification->message, 50) }}</td>
                                                    <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                                                    <td>
                                                        <span class="badge {{ $notification->is_read ? 'badge-success' : 'badge-warning' }}">
                                                            {{ $notification->is_read ? 'Dibaca' : 'Belum Dibaca' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info view-notification" 
                                                            data-id="{{ $notification->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        @if(auth()->user()->role->title === 'admin')
                                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit"
                                                            data-id="{{ $notification->id }}"
                                                            data-title="{{ $notification->title }}"
                                                            data-message="{{ $notification->message }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        @endif
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

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Notifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul</label>
                        <p id="detail-title" class="form-control-static"></p>
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <p id="detail-message" class="form-control-static"></p>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <p id="detail-date" class="form-control-static"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Notifikasi</h5>
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
                            <label for="edit_title">Judul</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_message">Pesan</label>
                            <textarea class="form-control" id="edit_message" name="message" rows="4" required></textarea>
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

        #notificationTable th,
        #notificationTable td {
            white-space: nowrap !important;
            min-width: 150px;
        }

        #notificationTable th:nth-child(1),
        #notificationTable td:nth-child(1) {
            min-width: 50px;
        }

        #notificationTable th:last-child,
        #notificationTable td:last-child {
            min-width: 200px;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#notificationTable').DataTable({
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
                    { "orderable": false, "targets": [0, 5] },
                    { "searchable": false, "targets": [0, 5] }
                ]
            });

            // View Notification Detail
            $('.view-notification').click(function() {
                const id = $(this).data('id');
                
                $.ajax({
                    url: `/notification/${id}/show`,
                    method: 'GET',
                    success: function(response) {
                        $('#detail-title').text(response.title);
                        $('#detail-message').text(response.message);
                        $('#detail-date').text(response.created_at);
                        $('#modalDetail').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengambil detail notifikasi',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });

            // Event handler untuk tombol edit
            $('#modalEdit').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var title = button.data('title');
                var message = button.data('message');
                
                var modal = $(this);
                modal.find('#edit_id').val(id);
                modal.find('#edit_title').val(title);
                modal.find('#edit_message').val(message);
                modal.find('#formEdit').attr('action', `/notifications/${id}`);
            });

            // Reset form ketika modal ditutup
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });

            // Buka modal jika ada error
            @if(session('modal') == 'edit')
                $('#modalEdit').modal('show');
            @endif
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
