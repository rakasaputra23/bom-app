@extends('layouts.app')

@section('title', 'Revisi')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Revisi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('dashboard'))
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    Dashboard
                @endif
            </li>
            <li class="breadcrumb-item active">Revisi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    // Force refresh user relations untuk memastikan permission terbaru
    Auth::user()->refreshRelations();
    
    // Check permissions dengan fresh data
    $canIndex = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('revisi.index');
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('revisi.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('revisi.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('revisi.destroy');
    $hasAnyAccess = $canIndex || $canCreate || $canEdit || $canDelete;
@endphp

@if($hasAnyAccess)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Data Revisi
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#revisiModal">
                        <i class="fas fa-plus"></i> Tambah Revisi
                    </button>
                    @endif
                </div>
            </div>
            
            @if($canIndex)
            <div class="card-body">
                <!-- Filter Section -->
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
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="revisiTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenis Revisi</th>
                                <th>Keterangan</th>
                                @if($canEdit || $canDelete)
                                <th width="15%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            @else
            <div class="card-body text-center">
                <i class="fas fa-eye-slash text-warning fa-3x mb-3"></i>
                <h4 class="text-warning">Tidak Dapat Melihat Data</h4>
                <p class="text-muted">Anda tidak memiliki izin untuk melihat data revisi.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h4 class="text-warning">Akses Terbatas</h4>
                <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <p class="text-muted">Silakan hubungi administrator untuk mendapatkan akses.</p>
            </div>
        </div>
    </div>
</div>
@endif

@if($canCreate)
<!-- Modal Tambah Revisi -->
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
                @csrf
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
@endif

@if($canEdit)
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
                @csrf
                @method('PUT')
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
@endif
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Permission flags for JavaScript - MENGGUNAKAN FRESH DATA
    const permissions = {
        canIndex: {{ $canIndex ? 'true' : 'false' }},
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        hasActionColumn: {{ ($canEdit || $canDelete) ? 'true' : 'false' }}
    };

    // Hanya jalankan inisialisasi jika user punya permission index
    if (!permissions.canIndex) {
        return;
    }

    // Konfigurasi kolom DataTable berdasarkan permission
    let columns = [
        { 
            data: 'DT_RowIndex', 
            name: 'DT_RowIndex', 
            orderable: false, 
            searchable: false,
            width: '5%'
        },
        { 
            data: 'jenis_revisi', 
            name: 'jenis_revisi',
            width: permissions.hasActionColumn ? '30%' : '40%'
        },
        { 
            data: 'keterangan', 
            name: 'keterangan',
            width: permissions.hasActionColumn ? '50%' : '55%'
        }
    ];

    // Tambahkan kolom aksi jika user punya permission edit atau delete
    if (permissions.hasActionColumn) {
        columns.push({
            data: 'action', 
            name: 'action', 
            orderable: false, 
            searchable: false,
            width: '15%',
            render: function(data, type, row) {
                let buttons = '';
                
                if (permissions.canEdit) {
                    buttons += `<button onclick="editRevisi(${row.id})" class="btn btn-sm btn-warning mr-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>`;
                }
                
                if (permissions.canDelete) {
                    buttons += `<button onclick="deleteRevisi(${row.id}, '${row.jenis_revisi}', '${row.keterangan}')" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>`;
                }
                
                return buttons || '-';
            }
        });
    }

    // Initialize DataTable
    var table = $('#revisiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("revisi.getData") }}',
            data: function(d) {
                d.search_jenis = $('#search_jenis').val();
                d.search_keterangan = $('#search_keterangan').val();
            }
        },
        columns: columns,
        responsive: true,
        autoWidth: false,
        dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
             '<"row"<"col-md-12"tr>>' +
             '<"row"<"col-md-5"i><"col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm mr-1',
                title: 'Data Revisi',
                exportOptions: { columns: [0, 1, 2] }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: 'Data Revisi',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0, 1, 2] }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: 'Data Revisi',
                exportOptions: { columns: [0, 1, 2] }
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Data tidak tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir", 
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            processing: "Memproses data..."
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[1, 'asc']]
    });

    // Custom search filters
    $('#search_jenis, #search_keterangan').on('keyup', function() {
        table.ajax.reload();
    });

    // Form submission untuk Add
    if (permissions.canCreate) {
        $('#revisiForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = $(this).serialize();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: '{{ route("revisi.store") }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#revisiModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        resetForm();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                        
                        $.each(errors, function(key, value) {
                            $(`[name="${key}"]`).addClass('is-invalid');
                            $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]);
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
    }

    // Form submission untuk Update
    if (permissions.canEdit) {
        $('#editRevisiForm').on('submit', function(e) {
            e.preventDefault();
            
            let id = $('#edit_id').val();
            let formData = $(this).serialize();
            
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: `{{ route('revisi.update', ':id') }}`.replace(':id', id),
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editRevisiModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                        
                        $.each(errors, function(key, value) {
                            $(`#edit_${key}`).addClass('is-invalid');
                            $(`#edit_${key}`).siblings('.invalid-feedback').text(value[0]);
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
    }

    // Reset modal when closed
    if (permissions.canCreate) {
        $('#revisiModal').on('hidden.bs.modal', function() {
            resetForm();
        });
    }

    // Clear validation on input change
    $('input').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

// Helper Functions
function resetForm() {
    @if($canCreate)
    $('#revisiForm')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    @endif
}

function editRevisi(id) {
    @if($canEdit)
    // Show loading indicator
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `{{ route('revisi.show', ':id') }}`.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#edit_id').val(response.data.id);
                $('#edit_jenis_revisi').val(response.data.jenis_revisi);
                $('#edit_keterangan').val(response.data.keterangan);
                
                // Clear validation errors
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                $('#editRevisiModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Gagal memuat data Revisi'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Gagal memuat data Revisi'
            });
            console.error('Error:', xhr.responseJSON);
        }
    });
    @else
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak!',
        text: 'Anda tidak memiliki izin untuk mengedit data ini.'
    });
    @endif
}

function deleteRevisi(id, jenis, keterangan) {
    @if($canDelete)
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus revisi "${jenis} - ${keterangan}"!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ route('revisi.destroy', ':id') }}`.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#revisiTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Gagal menghapus data'
                        });
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: message
                    });
                }
            });
        }
    });
    @else
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak!',
        text: 'Anda tidak memiliki izin untuk menghapus data ini.'
    });
    @endif
}
</script>
@endpush