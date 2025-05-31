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
                                <h2 class="text-white pb-2 fw-bold">Departemen</h2>
                                <h5 class="text-white op-7 mb-2">Pengelolaan Data Departemen</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <button type="button" class="btn btn-light btn-border btn-round" data-toggle="modal" data-target="#modalAdd">
                                    <i class="fas fa-plus"></i> Tambah Departemen
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
                                        <table id="departemenTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Kode</th>
                                                    <th>Deskripsi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($departemens as $index => $departemen)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $departemen->name }}</td>
                                                    <td>{{ $departemen->code }}</td>
                                                    <td>{{ $departemen->description }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit" 
                                                            data-id="{{ $departemen->id }}"
                                                            data-name="{{ $departemen->name }}"
                                                            data-code="{{ $departemen->code }}"
                                                            data-description="{{ $departemen->description }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete"
                                                            data-id="{{ $departemen->id }}"
                                                            data-name="{{ $departemen->name }}">
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
                    <h5 class="modal-title" id="modalAddLabel">Tambah Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAdd" action="{{ route('departemen.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="code">Kode</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                            <small class="form-text text-muted">Masukkan kode departemen (contoh: IT, HR, FIN)</small>
                            <div id="codeSuggestions" class="mt-2">
                                <small class="text-muted">Rekomendasi kode:</small>
                                <div class="suggestions"></div>
                            </div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
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
                    <h5 class="modal-title" id="modalEditLabel">Edit Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id" value="{{ old('edit_id') }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_code">Kode</label>
                            <input type="text" class="form-control" id="edit_code" name="code" value="{{ old('code') }}" required>
                            <small class="form-text text-muted">Masukkan kode departemen (contoh: IT, HR, FIN)</small>
                            <div id="editCodeSuggestions" class="mt-2">
                                <small class="text-muted">Rekomendasi kode:</small>
                                <div class="suggestions"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3">{{ old('description') }}</textarea>
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
                    <p>Apakah Anda yakin ingin menghapus departemen <strong id="delete-name"></strong>?</p>
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

        #departemenTable th,
        #departemenTable td {
            white-space: nowrap !important;
            min-width: 150px;
        }

        #departemenTable th:nth-child(1),
        #departemenTable td:nth-child(1) {
            min-width: 50px;
        }

        #departemenTable th:last-child,
        #departemenTable td:last-child {
            min-width: 100px;
        }
    </style>

    <script>
        $(document).ready(function() {
            console.log('Document ready');
            
            // Inisialisasi DataTable
            var table = $('#departemenTable').DataTable({
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
                    { "orderable": false, "targets": [0, 4] },
                    { "searchable": false, "targets": [0, 4] }
                ]
            });

            console.log('DataTable initialized');

            // Fungsi untuk generate rekomendasi kode
            function generateCodeSuggestions(name) {
                // Rekomendasi 1: Huruf pertama + 2 huruf konsonan pertama
                let suggestion1 = name.charAt(0).toUpperCase();
                let consonants = name.replace(/[aeiou]/gi, '').substring(0, 2);
                suggestion1 += consonants.toUpperCase();

                // Rekomendasi 2: 3 huruf pertama (kapital)
                let suggestion2 = name.substring(0, 3).toUpperCase();

                // Rekomendasi 3: Huruf pertama + 2 huruf terakhir
                let suggestion3 = name.charAt(0).toUpperCase();
                suggestion3 += name.slice(-2).toUpperCase();

                return [suggestion1, suggestion2, suggestion3];
            }

            // Event handler untuk input nama pada form tambah
            $('#name').on('input', function() {
                var name = $(this).val();
                if (name.length > 0) {
                    var suggestions = generateCodeSuggestions(name);
                    var suggestionsHtml = '';
                    suggestions.forEach(function(code) {
                        suggestionsHtml += `<span class="badge badge-info mr-2" style="cursor: pointer;">${code}</span>`;
                    });
                    $('#codeSuggestions .suggestions').html(suggestionsHtml);

                    // Event handler untuk klik pada rekomendasi
                    $('#codeSuggestions .badge').click(function() {
                        $('#code').val($(this).text());
                    });
                } else {
                    $('#codeSuggestions .suggestions').empty();
                }
            });

            // Event handler untuk input nama pada form edit
            $('#edit_name').on('input', function() {
                var name = $(this).val();
                if (name.length > 0) {
                    var suggestions = generateCodeSuggestions(name);
                    var suggestionsHtml = '';
                    suggestions.forEach(function(code) {
                        suggestionsHtml += `<span class="badge badge-info mr-2" style="cursor: pointer;">${code}</span>`;
                    });
                    $('#editCodeSuggestions .suggestions').html(suggestionsHtml);

                    // Event handler untuk klik pada rekomendasi
                    $('#editCodeSuggestions .badge').click(function() {
                        $('#edit_code').val($(this).text());
                    });
                } else {
                    $('#editCodeSuggestions .suggestions').empty();
                }
            });

            // Event handler untuk tombol edit
            $(document).on('click', 'button[data-target="#modalEdit"]', function(e) {
                e.preventDefault();
                console.log('Tombol edit diklik');
                
                var button = $(this);
                var id = button.data('id');
                var name = button.data('name');
                var code = button.data('code');
                var description = button.data('description');
                
                console.log('Data yang diambil:', {
                    id: id,
                    name: name,
                    code: code,
                    description: description
                });
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_code').val(code);
                $('#edit_description').val(description);
                $('#formEdit').attr('action', `/departemen/update/${id}`);
                
                // Generate rekomendasi kode saat modal edit dibuka
                var suggestions = generateCodeSuggestions(name);
                var suggestionsHtml = '';
                suggestions.forEach(function(code) {
                    suggestionsHtml += `<span class="badge badge-info mr-2" style="cursor: pointer;">${code}</span>`;
                });
                $('#editCodeSuggestions .suggestions').html(suggestionsHtml);
                
                $('#modalEdit').modal('show');
            });

            // Event handler untuk klik pada rekomendasi kode
            $(document).on('click', '#editCodeSuggestions .badge', function() {
                $('#edit_code').val($(this).text());
            });

            // Delete Departemen
            $('#modalDelete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                
                console.log('Modal Delete dibuka:', {
                    id: id,
                    name: name
                });
                
                $('#delete-name').text(name);
                $('#confirmDelete').data('id', id);
            });

            $('#confirmDelete').click(function() {
                console.log('Konfirmasi delete diklik');
                const id = $(this).data('id');
                
                // Buat form untuk submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/departemen/delete/${id}`
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
                console.log('Modal Add dibuka karena error');
                $('#modalAdd').modal('show');
            @endif

            @if(session('modal') == 'edit')
                console.log('Modal Edit dibuka karena error');
                var editId = '{{ old("edit_id") }}';
                var editName = '{{ old("name") }}';
                var editCode = '{{ old("code") }}';
                var editDescription = '{{ old("description") }}';
                
                console.log('Data error yang diambil:', {
                    id: editId,
                    name: editName,
                    code: editCode,
                    description: editDescription
                });
                
                $('#modalEdit').modal('show');
                $('#edit_id').val(editId);
                $('#edit_name').val(editName);
                $('#edit_code').val(editCode);
                $('#edit_description').val(editDescription);
                $('#formEdit').attr('action', `/departemen/update/${editId}`);

                // Generate rekomendasi kode saat modal edit dibuka karena error
                var suggestions = generateCodeSuggestions(editName);
                var suggestionsHtml = '';
                suggestions.forEach(function(code) {
                    suggestionsHtml += `<span class="badge badge-info mr-2" style="cursor: pointer;">${code}</span>`;
                });
                $('#editCodeSuggestions .suggestions').html(suggestionsHtml);
            @endif

            // Reset form ketika modal ditutup
            $('#modalAdd').on('hidden.bs.modal', function () {
                console.log('Modal Add ditutup');
                $(this).find('form')[0].reset();
                $('#codeSuggestions .suggestions').empty();
            });

            $('#modalEdit').on('hidden.bs.modal', function () {
                console.log('Modal Edit ditutup');
                $(this).find('form')[0].reset();
                $('#editCodeSuggestions .suggestions').empty();
            });

            // Tampilkan error di form edit
            @if($errors->any())
                @if(session('modal') == 'edit')
                    $('#modalEdit').modal('show');
                    @foreach($errors->all() as $error)
                        console.log('Error:', '{{ $error }}');
                    @endforeach
                @endif
            @endif
        });
    </script>
</body>
</html>