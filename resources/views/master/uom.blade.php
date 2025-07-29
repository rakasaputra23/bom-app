@extends('layouts.app')

@section('title', 'Unit of Measure (UoM)')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Unit of Measure (UoM)</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Unit of Measure</li>
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
                    <i class="fas fa-balance-scale mr-1"></i>
                    Data Unit of Measure
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uomModal">
                        <i class="fas fa-plus"></i> Tambah UoM
                    </button>
                </div>
            </div>
            <div class="card-body">
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
                
                <div class="table-responsive">
                    <table id="uomTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal UoM -->
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
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
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
        columns: [
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
                width: '35%'
            },
            { 
                data: 'qty', 
                name: 'qty',
                width: '35%'
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                width: '25%',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" onclick="editUom(${row.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteUom(${row.id}, '${row.satuan}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        responsive: true,
        autoWidth: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[1, 'asc']]
    });

    // Custom search filters
    $('#search_satuan, #search_qty').on('keyup', function() {
        table.ajax.reload();
    });

    // Form submission
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

    // Update form submission
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
                $('#uomTable').DataTable().ajax.reload();
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

    // Reset modal when closed
    $('#uomModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Clear validation on input change
    $('input').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

function resetForm() {
    $('#uomForm')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function editUom(id) {
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
}


function deleteUom(id, satuan) {
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
}
</script>
@endpush