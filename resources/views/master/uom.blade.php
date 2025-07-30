@extends('layouts.app')

@section('title', 'Unit of Measure (UoM)')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Unit of Measure (UoM)</h1>
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
      <li class="breadcrumb-item active">Unit of Measure</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
@php
    // Force refresh user relations to ensure latest permissions
    Auth::user()->refreshRelations();
    
    // Check permissions with fresh data
    $canIndex = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('uom.index');
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('uom.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('uom.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('uom.destroy');
    $hasAnyAccess = $canIndex || $canCreate || $canEdit || $canDelete;
@endphp

@if($hasAnyAccess)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-balance-scale mr-1"></i>
                    Data Unit of Measure
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uomModal">
                        <i class="fas fa-plus"></i> Tambah UoM
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
                            <label>Cari Satuan</label>
                            <input type="text" class="form-control" id="search_satuan" placeholder="Cari berdasarkan satuan...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cari Qty</label>
                            <input type="text" class="form-control" id="search_qty" placeholder="Cari berdasarkan qty...">
                        </div>
                    </div>
                </div>
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="uomTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Satuan</th>
                                <th>Qty</th>
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
                <p class="text-muted">Anda tidak memiliki izin untuk melihat data UoM.</p>
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
<!-- Modal Tambah UoM -->
<div class="modal fade" id="uomModal" tabindex="-1" aria-labelledby="uomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uomModalLabel">Tambah Unit of Measure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uomForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="satuan">Satuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Contoh: Kg, Pcs" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="qty">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="qty" name="qty" placeholder="Jumlah" required>
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
<!-- Modal Edit UoM -->
<div class="modal fade" id="editUomModal" tabindex="-1" aria-labelledby="editUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUomModalLabel">Edit Unit of Measure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUomForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_satuan">Satuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_qty">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_qty" name="qty" required>
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
    // Permission flags for JavaScript
    const permissions = {
        canIndex: {{ $canIndex ? 'true' : 'false' }},
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        hasActionColumn: {{ ($canEdit || $canDelete) ? 'true' : 'false' }}
    };

    // Only initialize if user has index permission
    if (!permissions.canIndex) {
        return;
    }

    // Configure DataTable columns based on permissions
    let columns = [
        { 
            data: 'DT_RowIndex', 
            name: 'DT_RowIndex', 
            orderable: false, 
            searchable: false,
            width: '5%'
        },
        { 
            data: 'satuan', 
            name: 'satuan',
            width: permissions.hasActionColumn ? '35%' : '45%'
        },
        { 
            data: 'qty', 
            name: 'qty',
            width: permissions.hasActionColumn ? '35%' : '50%'
        }
    ];

    // Add action column if user has edit or delete permission
    if (permissions.hasActionColumn) {
        columns.push({
            data: 'action', 
            name: 'action', 
            orderable: false, 
            searchable: false,
            width: '25%',
            render: function(data, type, row) {
                let buttons = '';
                
                if (permissions.canEdit) {
                    buttons += `<button onclick="editUom(${row.id})" class="btn btn-sm btn-warning mr-1" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>`;
                }
                
                if (permissions.canDelete) {
                    buttons += `<button onclick="deleteUom(${row.id}, '${row.satuan}')" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>`;
                }
                
                return buttons || '-';
            }
        });
    }

    // Configure export columns
    let exportColumns = permissions.hasActionColumn ? [0, 1, 2] : [0, 1, 2];

    // Initialize DataTable with Export Buttons
    var table = $('#uomTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("uom.getData") }}',
            data: function(d) {
                d.search_satuan = $('#search_satuan').val();
                d.search_qty = $('#search_qty').val();
            }
        },
        columns: columns,
        responsive: true,
        autoWidth: false,
        // Export Buttons Configuration
        dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
             '<"row"<"col-md-12"tr>>' +
             '<"row"<"col-md-5"i><"col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm mr-1',
                title: 'Data Unit of Measure (UoM)',
                exportOptions: {
                    columns: exportColumns
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Add styling to header
                    $('row:first c', sheet).attr('s', '2');
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: 'Data Unit of Measure (UoM)',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: exportColumns
                },
                customize: function(doc) {
                    // Customize PDF styling
                    doc.content[1].table.widths = ['15%', '42.5%', '42.5%'];
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.tableBodyEven.fontSize = 9;
                    doc.styles.tableBodyOdd.fontSize = 9;
                    doc.defaultStyle.fontSize = 9;
                    
                    // Add header
                    doc.content.splice(0, 1, {
                        text: [
                            { text: 'DATA UNIT OF MEASURE (UoM)\n', fontSize: 16, bold: true },
                            { text: 'Tanggal Cetak: ' + new Date().toLocaleDateString('id-ID'), fontSize: 10 }
                        ],
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: 'Data Unit of Measure (UoM)',
                exportOptions: {
                    columns: exportColumns
                },
                customize: function(win) {
                    // Add custom CSS for print
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend(
                            '<div style="text-align:center; margin-bottom: 20px;">' +
                            '<h2 style="margin: 0;">DATA UNIT OF MEASURE (UoM)</h2>' +
                            '<p style="margin: 5px 0;">Tanggal Cetak: ' + new Date().toLocaleDateString('id-ID') + '</p>' +
                            '</div>'
                        );
                    
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '9pt');
                    
                    // Style table headers
                    $(win.document.body).find('table thead tr th')
                        .css({
                            'background-color': '#f8f9fa',
                            'border': '1px solid #dee2e6',
                            'padding': '8px',
                            'text-align': 'center'
                        });
                    
                    // Style table cells
                    $(win.document.body).find('table tbody tr td')
                        .css({
                            'border': '1px solid #dee2e6',
                            'padding': '6px'
                        });
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
            processing: "Memproses data...",
            buttons: {
                excel: "Excel",
                pdf: "PDF", 
                print: "Print"
            }
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[1, 'asc']]
    });

    // Custom search filters
    $('#search_satuan, #search_qty').on('keyup', function() {
        table.ajax.reload();
    });

    // Form submission for Add
    if (permissions.canCreate) {
        $('#uomForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            let url = '{{ route("uom.store") }}';
            
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#uomModal').modal('hide');
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
                    // Reset button state
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    }

    // Form submission for Update
    if (permissions.canEdit) {
        $('#editUomForm').on('submit', function(e) {
            e.preventDefault();
            
            let id = $('#edit_id').val();
            let formData = new FormData(this);
            
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: `{{ route('uom.update', ':id') }}`.replace(':id', id),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editUomModal').modal('hide');
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
        $('#uomModal').on('hidden.bs.modal', function() {
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
    $('#uomForm')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    @endif
}

function editUom(id) {
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
        url: `{{ route('uom.show', ':id') }}`.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#edit_id').val(response.data.id);
                $('#edit_satuan').val(response.data.satuan);
                $('#edit_qty').val(response.data.qty);
                
                // Clear validation errors
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                $('#editUomModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Gagal memuat data UoM'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Gagal memuat data UoM'
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

function deleteUom(id, satuan) {
    @if($canDelete)
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus satuan "${satuan}"!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ route('uom.destroy', ':id') }}`.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#uomTable').DataTable().ajax.reload();
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
                    console.error('Error:', xhr.responseJSON);
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