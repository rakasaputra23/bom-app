@extends('layouts.app')

@section('title', 'Unit of Measure (UoM)')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Unit of Measure (UoM)</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item active">Unit of Measure</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Form Tambah -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah Unit of Measure</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <form id="addForm">
      @csrf
      <div class="row">
        <div class="form-group col-md-6">
          <label for="satuan">Satuan <span class="text-red">*</span></label>
<input type="text" class="form-control" id="satuan" name="satuan" placeholder="Contoh: Kg, Pcs" required>
          <div class="invalid-feedback"></div>
        </div>
        <div class="form-group col-md-6">
          <label for="qty">Qty <span class="text-red">*</span></label>
<input type="number" class="form-control" id="qty" name="qty" placeholder="Jumlah" required>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Filter Pencarian -->
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Cari Satuan</label>
      <input type="text" class="form-control" id="search_satuan" placeholder="Cari berdasarkan satuan...">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Cari Jenis</label>
      <input type="text" class="form-control" id="search_qty" placeholder="Cari berdasarkan jenis...">
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Unit of Measure</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="yuomTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 35%;">Satuan</th>
            <th style="width: 35%;">Qty</th>
            <th style="width: 20%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data will be loaded via AJAX -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Unit of Measure</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          @csrf
          @method('PUT')
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="edit_satuan">Satuan <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="edit_qty">Qty <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_qty" name="qty" required>
            <div class="invalid-feedback"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="updateBtn">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus unit of measure <strong id="delete_item_name"></strong>?</p>
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i>
          Data yang telah dihapus tidak dapat dikembalikan!
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="deleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
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
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#yuomTable').DataTable({
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
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'satuan', name: 'satuan' },
      { data: 'qty', name: 'qty' },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true,
    autoWidth: false,
    dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
         '<"row"<"col-md-12"tr>>' +
         '<"row"<"col-md-5"i><"col-md-7"p>>',
    buttons: [
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm',
        exportOptions: {
          columns: [0, 1, 2]
        }
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-danger btn-sm',
        exportOptions: {
          columns: [0, 1, 2]
        }
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Print',
        className: 'btn btn-info btn-sm',
        exportOptions: {
          columns: [0, 1, 2]
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
      processing: "Sedang memproses..."
    },
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    order: [[1, 'asc']]
  });

  // Custom search filters
  $('#search_satuan, #search_qty').on('keyup', function() {
    table.ajax.reload();
  });

  // Add Form Submit
  $('#addForm').on('submit', function(e) {
    e.preventDefault();
    
    var formData = $(this).serialize();
    
    $.ajax({
      url: '{{ route("uom.store") }}',
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if(response.success) {
          toastr.success(response.message);
          $('#addForm')[0].reset();
          table.ajax.reload();
          clearValidation();
        }
      },
      error: function(xhr) {
        if(xhr.status === 422) {
          var errors = xhr.responseJSON.errors;
          displayValidationErrors(errors);
        } else {
          toastr.error('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Server error'));
        }
        console.error('Error:', xhr.responseJSON);
      }
    });
  });

  // Edit Button Click
  $(document).on('click', '.edit-btn', function() {
    var id = $(this).data('id');
    var satuan = $(this).data('satuan');
    var qty = $(this).data('qty');
    
    $('#edit_id').val(id);
    $('#edit_satuan').val(satuan);
    $('#edit_qty').val(qty);
    $('#editModal').modal('show');
    clearValidation('#editForm');
  });

  // Update Button Click
  $('#updateBtn').on('click', function() {
    var id = $('#edit_id').val();
    var formData = $('#editForm').serialize();
    
    $.ajax({
      url: '{{ route("uom.update", ":id") }}'.replace(':id', id),
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if(response.success) {
          toastr.success(response.message);
          $('#editModal').modal('hide');
          table.ajax.reload();
        }
      },
      error: function(xhr) {
        if(xhr.status === 422) {
          displayValidationErrors(xhr.responseJSON.errors, '#editForm');
        } else {
          toastr.error('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Server error'));
        }
      }
    });
  });

  // Delete Button Click
  $(document).on('click', '.delete-btn', function() {
    var id = $(this).data('id');
    var satuan = $(this).data('satuan');
    var qty = $(this).data('qty');
    
    $('#delete_item_name').text(satuan + ' - ' + qty);
    $('#deleteBtn').data('id', id);
    $('#deleteModal').modal('show');
  });

  // Delete Confirmation
  $('#deleteBtn').on('click', function() {
    var id = $(this).data('id');
    
    $.ajax({
      url: '{{ route("uom.destroy", ":id") }}'.replace(':id', id),
      method: 'DELETE',
      data: {
        '_token': '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          toastr.success(response.message);
          $('#deleteModal').modal('hide');
          table.ajax.reload();
        }
      },
      error: function(xhr) {
        toastr.error('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Server error'));
      }
    });
  });

  // Helper Functions
  function displayValidationErrors(errors, formSelector = '') {
    $.each(errors, function(key, value) {
      var input = $(formSelector + ' [name="' + key + '"]');
      input.addClass('is-invalid');
      input.next('.invalid-feedback').text(value[0]);
    });
  }

  function clearValidation(formSelector = '') {
    $(formSelector + ' .is-invalid').removeClass('is-invalid');
    $(formSelector + ' .invalid-feedback').text('');
  }

  // Clear validation on input change
  $('input, select').on('change', function() {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').text('');
  });
});
</script>
@endpush