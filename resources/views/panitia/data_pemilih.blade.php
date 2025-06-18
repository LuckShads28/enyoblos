@extends('layouts/admin_main')

@section('content')
    @push('css')
        <link href="{{ asset('/') }}assets/vendor/sb-admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    @endpush

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Buat Laporan</a>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex flex-wrap justify-content-end" style="gap: 5px">
                    <a href="{{ route('pemilih.create') }}" class="btn btn-primary">Tambah Data</a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#importPemilihModal">Import
                        Data</a>
                    <a href="{{ route('kirim_email_all') }}" class="btn btn-primary">Kirim Email Semua User</a>
                    <button type="button" class="btn btn-success" id="kirimEmailTerpilih">Kirim Email Terpilih</button>
                    <button type="submit" class="btn btn-danger" form="dataPemilihForm">Hapus Terpilih</button>
                    <a href="{{ route('panitia.download_template_pemilih') }}" class="btn btn-warning">Template
                        Import</a>
                </div>
            </div>
            <form action="{{ route('pemilih.delete_selected') }}" method="post" id="dataPemilihForm">
                @csrf
                @method('delete')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataPemilih" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>
                                        <center><input type="checkbox" name="selectAll" id="selectAll"></center>
                                    </td>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Angkatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $d)
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" name="ids[]" value="{{ $d->id }}">
                                            </center>
                                        </td>
                                        <td>{{ $d->nim }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->cohort }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('kirim_email_user', $d->id) }}"
                                                    class="btn btn-primary mx-1" data-toggle="tooltip"
                                                    title="Kirim Email"><i class="fas fa-envelope"></i></a>
                                                <a href="#" class="btn btn-warning mx-1" data-toggle="tooltip"
                                                    title="Edit Data"><i class="fas fa-pen"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="importPemilihModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('pemilih.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import data pemilih</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if (session()->has('errors'))
                                @foreach (session('errors')->all() as $e)
                                    <div class="alert alert-danger" role="alert">
                                        {{ $e }}
                                    </div>
                                @endforeach
                            @endif
                            <div class="mb-3">
                                <label for="fileImport">File Excel (.xlsx)</label>
                                <input class="form-control form-control-solid @error('angkatan') is-invalid @enderror"
                                    id="fileImport" type="file" name="fileImport" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endpush

    @push('scripts')
        <!-- Page level plugins -->
        <script src="{{ asset('/') }}assets/vendor/sb-admin/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}assets/vendor/sb-admin/vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script>
            // Call the dataTables jQuery plugin
            $(document).ready(function() {
                $('#dataPemilih').DataTable();
            });

            // Select All Script
            $('#selectAll').click(function(event) {
                if (this.checked) {
                    // Iterate each checkbox with name="ids[]"
                    $('input[name="ids[]"]').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('input[name="ids[]"]').each(function() {
                        this.checked = false;
                    });
                }
            });

            // Kirim Email Terpilih Script
            $('#kirimEmailTerpilih').click(function() {
                var selectedIds = [];
                $('input[name="ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert('Pilih minimal 1 pengguna untuk mengirim email.');
                    return;
                }

                if (confirm('Apakah Anda yakin ingin mengirim email ke ' + selectedIds.length + ' pengguna terpilih?')) {
                    // Create form and submit
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route("kirim_email_selected") }}'
                    });
                    
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));

                    $.each(selectedIds, function(index, value) {
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': 'ids[]',
                            'value': value
                        }));
                    });

                    $('body').append(form);
                    form.submit();
                }
            });
        </script>

        @if (session()->has('errors'))
            <script>
                $('#importPemilihModal').modal('toggle')
            </script>
        @endif
    @endpush
@endsection
