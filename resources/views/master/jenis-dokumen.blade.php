@extends('layouts.app')

@section('title', 'Jenis Dokumen')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Jenis Dokumen</h1>
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
            <li class="breadcrumb-item active">Jenis Dokumen</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    // Force refresh user relations untuk memastikan permission terbaru
    if (method_exists(Auth::user(), 'refreshRelations')) {
        Auth::user()->refreshRelations();
    }
    
    // Check permissions dengan fresh data
    $canIndex = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('jenis-dokumen.index');
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('jenis-dokumen.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('jenis-dokumen.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('jenis-dokumen.destroy');
    $hasAnyAccess = $canIndex || $canCreate || $canEdit || $canDelete;
@endphp

@if($hasAnyAccess)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-file-alt mr-1"></i>
                    Data Jenis Dokumen
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#jenisDokumenModal">
                        <i class="fas fa-plus"></i> Tambah Jenis Dokumen
                    </button>
                    @endif
                </div>
            </div>
            
            @if($canIndex)
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cari Kode Dokumen</label>
                            <input type="text" class="form-control" id="search_kode" placeholder="Cari berdasarkan kode dokumen...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cari Nama Dokumen</label>
                            <input type="text" class="form-control" id="search_nama" placeholder="Cari berdasarkan nama dokumen...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="search_status">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="jenisDokumenTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Dokumen</th>
                                <th>Nama Dokumen</th>
                                <th>Deskripsi</th>
                                <th width="10%">Status</th>
                                @if($canEdit || $canDelete)
                                <th width="12%">Aksi</th>
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
                <p class="text-muted">Anda tidak memiliki izin untuk melihat data jenis dokumen.</p>
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
<!-- Modal Tambah Jenis Dokumen -->
<div class="modal fade" id="jenisDokumenModal" tabindex="-1" aria-labelledby="jenisDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisDokumenModalLabel">Tambah Jenis Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="jenisDokumenForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_dokumen">Kode Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_dokumen" name="kode_dokumen" placeholder="Contoh: BRM, SPE, PRC" maxlength="10" required>
                                <small class="form-text text-muted">Maksimal 10 karakter, akan dikonversi ke huruf besar</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_dokumen">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" placeholder="Contoh: Bill of Material" maxlength="100" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi jenis dokumen..." maxlength="255"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="is_active">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
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
<!-- Modal Edit Jenis Dokumen -->
<div class="modal fade" id="editJenisDokumenModal" tabindex="-1" aria-labelledby="editJenisDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJenisDokumenModalLabel">Edit Jenis Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editJenisDokumenForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_kode_dokumen">Kode Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_kode_dokumen" name="kode_dokumen" maxlength="10" required>
                                <small class="form-text text-muted">Maksimal 10 karakter, akan dikonversi ke huruf besar</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_nama_dokumen">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_dokumen" name="nama_dokumen" maxlength="100" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" maxlength="255"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_is_active">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_is_active" name="is_active" required>
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
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
            data: 'kode_dokumen', 
            name: 'kode_dokumen',
            width: '15%'
        },
        { 
            data: 'nama_dokumen', 
            name: 'nama_dokumen',
            width: '25%'
        },
        { 
            data: 'deskripsi', 
            name: 'deskripsi',
            width: permissions.hasActionColumn ? '33%' : '45%',
            render: function(data, type, row) {
                return data || '-';
            }
        },
        { 
            data: 'status', 
            name: 'status',
            orderable: false,
            searchable: false,
            width: '10%'
        }
    ];

    // Tambahkan kolom aksi jika user punya permission edit atau delete
    if (permissions.hasActionColumn) {
        columns.push({
            data: 'action', 
            name: 'action', 
            orderable: false, 
            searchable: false,
            width: '12%'
        });
    }

    // Initialize DataTable
    var table = $('#jenisDokumenTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("jenis-dokumen.getData") }}',
            data: function(d) {
                d.search_kode = $('#search_kode').val();
                d.search_nama = $('#search_nama').val();
                d.search_status = $('#search_status').val();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data. Silakan refresh halaman.'
                });
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
                title: 'Data Jenis Dokumen',
                exportOptions: { 
                    columns: permissions.hasActionColumn ? [0, 1, 2, 3, 4] : [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: 'Data Jenis Dokumen',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { 
                    columns: permissions.hasActionColumn ? [0, 1, 2, 3, 4] : [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: 'Data Jenis Dokumen',
                exportOptions: { 
                    columns: permissions.hasActionColumn ? [0, 1, 2, 3, 4] : [0, 1, 2, 3, 4]
                }
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
    $('#search_kode, #search_nama, #search_status').on('keyup change', function() {
        table.ajax.reload(null, false); // false = keep current page
    });

    // Auto-uppercase kode dokumen input
    $('#kode_dokumen, #edit_kode_dokumen').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Form submission untuk Add
    if (permissions.canCreate) {
        $('#jenisDokumenForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = $(this).serialize();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            // Clear previous validation errors
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: '{{ route("jenis-dokumen.store") }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#jenisDokumenModal').modal('hide');
                        table.ajax.reload(null, false);
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
                        
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server'
                        });
                    }
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Form submission untuk Edit
        $('#editJenisDokumenForm').on('submit', function(e) {
            e.preventDefault();
            
            let id = $('#edit_id').val();
            let formData = $(this).serialize();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            // Clear previous validation errors
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: '{{ route("jenis-dokumen.update", ":id") }}'.replace(':id', id),
                method: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editJenisDokumenModal').modal('hide');
                        table.ajax.reload(null, false);
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
                        
                        $.each(errors, function(key, value) {
                            $(`#edit_${key}`).addClass('is-invalid');
                            $(`#edit_${key}`).siblings('.invalid-feedback').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server'
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
    $('#jenisDokumenModal, #editJenisDokumenModal').on('hidden.bs.modal', function() {
        resetForm();
        resetEditForm();
    });

    // Clear validation on input change
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

// Helper Functions
function resetForm() {
    if ($('#jenisDokumenForm').length) {
        $('#jenisDokumenForm')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
}

function resetEditForm() {
    if ($('#editJenisDokumenForm').length) {
        $('#editJenisDokumenForm')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#edit_id').val('');
    }
}

function editJenisDokumen(id) {
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
        url: `{{ route('jenis-dokumen.show', ':id') }}`.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#edit_id').val(response.data.id);
                $('#edit_kode_dokumen').val(response.data.kode_dokumen);
                $('#edit_nama_dokumen').val(response.data.nama_dokumen);
                $('#edit_deskripsi').val(response.data.deskripsi || '');
                $('#edit_is_active').val(response.data.is_active ? '1' : '0');
                
                // Clear validation errors
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                $('#editJenisDokumenModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Gagal memuat data Jenis Dokumen'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Gagal memuat data Jenis Dokumen'
            });
            console.error('Error:', xhr.responseText);
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

function deleteJenisDokumen(id, kode, nama) {
    @if($canDelete)
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus jenis dokumen "${kode} - ${nama}"!`,
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
                url: `{{ route('jenis-dokumen.destroy', ':id') }}`.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#jenisDokumenTable').DataTable().ajax.reload(null, false);
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
                    console.error('Delete Error:', xhr.responseText);
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