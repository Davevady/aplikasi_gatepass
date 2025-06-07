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
                                <h2 class="text-white pb-2 fw-bold">Ekspedisi</h2>
                                <h5 class="text-white op-7 mb-2">Pengelolaan Data Ekspedisi</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <button type="button" class="btn btn-light btn-border btn-round" data-toggle="modal" data-target="#modalAdd">
                                    <i class="fas fa-plus"></i> Tambah Ekspedisi
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
                                        <table id="ekspedisiTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Ekspedisi</th>
                                                    <th>Alamat</th>
                                                    <th>No. Telp</th>
                                                    <th>Email</th>
                                                    <th>PIC</th>
                                                    <th>No. HP PIC</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ekspedisis as $index => $ekspedisi)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $ekspedisi->nama_ekspedisi }}</td>
                                                    <td>{{ $ekspedisi->alamat }}</td>
                                                    <td>{{ $ekspedisi->no_telp }}</td>
                                                    <td>{{ $ekspedisi->email }}</td>
                                                    <td>{{ $ekspedisi->pic }}</td>
                                                    <td>{{ $ekspedisi->no_hp_pic }}</td>
                                                    <td>
                                                        @if($ekspedisi->status)
                                                            <span class="badge badge-success">Aktif</span>
                                                        @else
                                                            <span class="badge badge-danger">Nonaktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit" 
                                                            data-id="{{ $ekspedisi->id }}"
                                                            data-nama="{{ $ekspedisi->nama_ekspedisi }}"
                                                            data-alamat="{{ $ekspedisi->alamat }}"
                                                            data-no-telp="{{ $ekspedisi->no_telp }}"
                                                            data-email="{{ $ekspedisi->email }}"
                                                            data-pic="{{ $ekspedisi->pic }}"
                                                            data-no-hp-pic="{{ $ekspedisi->no_hp_pic }}"
                                                            data-keterangan="{{ $ekspedisi->keterangan }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete"
                                                            data-id="{{ $ekspedisi->id }}"
                                                            data-nama="{{ $ekspedisi->nama_ekspedisi }}">
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
                    <h5 class="modal-title" id="modalAddLabel">Tambah Ekspedisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAdd" action="{{ route('ekspedisi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_ekspedisi">Nama Ekspedisi</label>
                            <input type="text" class="form-control @error('nama_ekspedisi') is-invalid @enderror" id="nama_ekspedisi" name="nama_ekspedisi" value="{{ old('nama_ekspedisi') }}" required>
                            @error('nama_ekspedisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pic">PIC</label>
                            <input type="text" class="form-control @error('pic') is-invalid @enderror" id="pic" name="pic" value="{{ old('pic') }}" required>
                            @error('pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_hp_pic">No. HP PIC</label>
                            <input type="text" class="form-control @error('no_hp_pic') is-invalid @enderror" id="no_hp_pic" name="no_hp_pic" value="{{ old('no_hp_pic') }}" required>
                            @error('no_hp_pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                    <h5 class="modal-title" id="modalEditLabel">Edit Ekspedisi</h5>
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
                            <label for="edit_nama_ekspedisi">Nama Ekspedisi</label>
                            <input type="text" class="form-control" id="edit_nama_ekspedisi" name="nama_ekspedisi" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_no_telp">No. Telepon</label>
                            <input type="text" class="form-control" id="edit_no_telp" name="no_telp" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="edit_pic">PIC</label>
                            <input type="text" class="form-control" id="edit_pic" name="pic" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_no_hp_pic">No. HP PIC</label>
                            <input type="text" class="form-control" id="edit_no_hp_pic" name="no_hp_pic" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
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
                    <p>Apakah Anda yakin ingin menghapus ekspedisi <strong id="delete-nama"></strong>?</p>
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
        .table-container {
            position: relative;
            margin-top: 20px;
        }

        .table-container .dataTables_filter {
            position: absolute;
            top: -40px;
            right: 0;
            z-index: 1;
        }

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

        #ekspedisiTable th,
        #ekspedisiTable td {
            white-space: nowrap !important;
            min-width: 150px;
        }

        #ekspedisiTable th:nth-child(1),
        #ekspedisiTable td:nth-child(1) {
            min-width: 50px;
        }

        #ekspedisiTable th:last-child,
        #ekspedisiTable td:last-child {
            min-width: 100px;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#ekspedisiTable').DataTable({
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
                    { "orderable": false, "targets": [0, 8] },
                    { "searchable": false, "targets": [0, 8] }
                ]
            });

            // Event handler untuk tombol edit
            $(document).on('click', 'button[data-target="#modalEdit"]', function() {
                var button = $(this);
                var id = button.data('id');
                var nama = button.data('nama');
                var alamat = button.data('alamat');
                var noTelp = button.data('no-telp');
                var email = button.data('email');
                var pic = button.data('pic');
                var noHpPic = button.data('no-hp-pic');
                var keterangan = button.data('keterangan');
                
                $('#edit_id').val(id);
                $('#edit_nama_ekspedisi').val(nama);
                $('#edit_alamat').val(alamat);
                $('#edit_no_telp').val(noTelp);
                $('#edit_email').val(email);
                $('#edit_pic').val(pic);
                $('#edit_no_hp_pic').val(noHpPic);
                $('#edit_keterangan').val(keterangan);
                $('#formEdit').attr('action', `/ekspedisi/update/${id}`);
            });

            // Delete Ekspedisi
            $('#modalDelete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nama = button.data('nama');
                
                $('#delete-nama').text(nama);
                $('#confirmDelete').data('id', id);
            });

            $('#confirmDelete').click(function() {
                const id = $(this).data('id');
                
                // Buat form untuk submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/ekspedisi/delete/${id}`
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

            // Buka modal jika ada error
            @if(session('modal') == 'add')
                $('#modalAdd').modal('show');
            @endif

            @if(session('modal') == 'edit')
                $('#modalEdit').modal('show');
            @endif

            // Reset form ketika modal ditutup
            $('#modalAdd').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });

            $('#modalEdit').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        });
    </script>
</body>
</html> 