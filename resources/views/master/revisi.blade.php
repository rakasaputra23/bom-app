@extends('layouts.app')

@section('title', 'Revisi')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Revisi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Revisi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Data Revisi
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#revisiModal">
                        <i class="fas fa-plus"></i> Tambah Revisi
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cari Jenis Revisi</label>
                            <input type="text" class="form-control" id="search_jenis" placeholder="Cari berdasarkan jenis revisi...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cari Keterangan</label>
                            <input type="text" class="form-control" id="search_keterangan" placeholder="Cari berdasarkan keterangan...">
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="revisiTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenis Revisi</th>
                                <th>Keterangan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revisi -->
<div class="modal fade" id="revisiModal" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revisiModalLabel">Tambah Revisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="revisiForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis_revisi">Jenis Revisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_revisi" name="jenis_revisi" placeholder="Contoh: Revisi Desain, Revisi Konten" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan revisi" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Revisi -->
<div class="modal fade" id="editRevisiModal" tabindex="-1" aria-labelledby="editRevisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRevisiModalLabel">Edit Revisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRevisiForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_jenis_revisi">Jenis Revisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_jenis_revisi" name="jenis_revisi" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_keterangan" name="keterangan" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#revisiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("revisi.getData") }}',
            data: function(d) {
                d.search_jenis = $('#search_jenis').val();
                d.search_keterangan = $('#search_keterangan').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'jenis_revisi', name: 'jenis_revisi' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        responsive: true,
        autoWidth: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });

    // Search filters
    $('#search_jenis, #search_keterangan').on('keyup', function() {
        table.ajax.reload();
    });

    // Form submission
    $('#revisiForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = '{{ route("revisi.store") }}';
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#revisiModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000
                    });
                    form.trigger('reset');
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    
                    $.each(errors, function(key, value) {
                        $(`[name="${key}"]`).addClass('is-invalid');
                        $(`[name="${key}"]`).next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server'
                    });
                }
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Edit form submission
    $('#editRevisiForm').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const id = $('#edit_id').val();
    const url = '{{ route("revisi.update", ":id") }}'.replace(':id', id);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize() + '&_method=PUT',
            success: function(response) {
                if (response.success) {
                    $('#editRevisiModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    
                    $.each(errors, function(key, value) {
                        $(`#edit_${key}`).addClass('is-invalid');
                        $(`#edit_${key}`).next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server'
                    });
                }
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Clear validation on input change
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').text('');
    });
});

function editRevisi(id) {
    $.ajax({
        url: '{{ route("revisi.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        beforeSend: function() {
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#edit_id').val(response.data.id);
                $('#edit_jenis_revisi').val(response.data.jenis_revisi);
                $('#edit_keterangan').val(response.data.keterangan);
                $('#editRevisiModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Gagal memuat data'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Gagal memuat data'
            });
        }
    });
}

function deleteRevisi(id, jenis) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus revisi "${jenis}"!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("revisi.destroy", ":id") }}'.replace(':id', id),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000
                    });
                    $('#revisiTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Gagal menghapus data'
                    });
                }
            });
        }
    });
}
</script>
@endpush