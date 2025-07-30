@extends('layouts.app')

@section('title', 'Proyek')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Proyek</h1>
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
      <li class="breadcrumb-item active">Proyek</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
@php
    // PERBAIKAN: Force refresh user relations untuk memastikan permission terbaru
    Auth::user()->refreshRelations();
    
    // Check permissions dengan fresh data
    $canIndex = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('proyek.index');
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('proyek.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('proyek.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('proyek.destroy');
    $hasAnyAccess = $canIndex || $canCreate || $canEdit || $canDelete;
@endphp

@if($hasAnyAccess)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-project-diagram mr-1"></i>
                    Data Proyek
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#proyekModal">
                        <i class="fas fa-plus"></i> Tambah Proyek
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
                            <label>Cari Kode Proyek</label>
                            <input type="text" class="form-control" id="search_kode" placeholder="Cari berdasarkan kode...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cari Nama Proyek</label>
                            <input type="text" class="form-control" id="search_nama" placeholder="Cari berdasarkan nama...">
                        </div>
                    </div>
                </div>
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="proyekTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Proyek</th>
                                <th>Nama Proyek</th>
                                @if($canEdit || $canDelete)
                                <th>Aksi</th>
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
                <p class="text-muted">Anda tidak memiliki izin untuk melihat data proyek.</p>
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
<!-- Modal Tambah Proyek -->
<div class="modal fade" id="proyekModal" tabindex="-1" aria-labelledby="proyekModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proyekModalLabel">Tambah Proyek</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="proyekForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_proyek">Kode Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_proyek" name="kode_proyek" placeholder="PRJ001" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="nama_proyek">Nama Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_proyek" name="nama_proyek" placeholder="Nama Proyek" required>
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
<!-- Modal Edit Proyek -->
<div class="modal fade" id="editProyekModal" tabindex="-1" aria-labelledby="editProyekModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProyekModalLabel">Edit Proyek</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProyekForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_kode_proyek">Kode Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_kode_proyek" name="kode_proyek" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_proyek">Nama Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_proyek" name="nama_proyek" required>
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
            data: 'kode_proyek', 
            name: 'kode_proyek',
            width: permissions.hasActionColumn ? '20%' : '25%'
        },
        { 
            data: 'nama_proyek', 
            name: 'nama_proyek',
            width: permissions.hasActionColumn ? '55%' : '70%'
        }
    ];

    // Tambahkan kolom aksi jika user punya permission edit atau delete
    if (permissions.hasActionColumn) {
        columns.push({
            data: 'action', 
            name: 'action', 
            orderable: false, 
            searchable: false,
            width: '20%',
            render: function(data, type, row) {
                let buttons = '';
                
                if (permissions.canEdit) {
                    buttons += `<button onclick="editProyek(${row.id})" class="btn btn-sm btn-warning mr-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>`;
                }
                
                if (permissions.canDelete) {
                    buttons += `<button onclick="deleteProyek(${row.id}, '${row.kode_proyek}', '${row.nama_proyek}')" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>`;
                }
                
                return buttons || '-';
            }
        });
    }

    // Konfigurasi export columns
    let exportColumns = permissions.hasActionColumn ? [0, 1, 2] : [0, 1, 2];

    // Initialize DataTable
    var table = $('#proyekTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("proyek.getData") }}',
            data: function(d) {
                d.search_kode = $('#search_kode').val();
                d.search_nama = $('#search_nama').val();
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
                title: 'Data Proyek',
                exportOptions: { columns: exportColumns }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: 'Data Proyek',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: exportColumns }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: 'Data Proyek',
                exportOptions: { columns: exportColumns }
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
    $('#search_kode, #search_nama').on('keyup', function() {
        table.ajax.reload();
    });

    // Form submission untuk Add
    if (permissions.canCreate) {
        $('#proyekForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: '{{ route("proyek.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#proyekModal').modal('hide');
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
        $('#editProyekForm').on('submit', function(e) {
            e.preventDefault();
            
            let id = $('#edit_id').val();
            let formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: `{{ route('proyek.update', ':id') }}`.replace(':id', id),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editProyekModal').modal('hide');
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
        $('#proyekModal').on('hidden.bs.modal', function() {
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
    $('#proyekForm')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    @endif
}

function editProyek(id) {
    @if($canEdit)
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `{{ route('proyek.show', ':id') }}`.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#edit_id').val(response.data.id);
                $('#edit_kode_proyek').val(response.data.kode_proyek);
                $('#edit_nama_proyek').val(response.data.nama_proyek);
                
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                $('#editProyekModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Gagal memuat data Proyek'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Gagal memuat data Proyek'
            });
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

function deleteProyek(id, kode, nama) {
    @if($canDelete)
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus proyek "${kode} - ${nama}"!`,
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
                url: `{{ route('proyek.destroy', ':id') }}`.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#proyekTable').DataTable().ajax.reload();
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