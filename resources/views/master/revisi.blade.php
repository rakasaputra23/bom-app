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
<!-- Form Tambah -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah Revisi</h3>
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
          <label for="jenis_revisi">Jenis Revisi <span class="text-red">*</span></label>
          <input type="text" class="form-control" id="jenis_revisi" name="jenis_revisi" placeholder="Jenis Revisi" required>
          <div class="invalid-feedback"></div>
        </div>
        <div class="form-group col-md-6">
          <label for="keterangan">Keterangan <span class="text-red">*</span></label>
          <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan Revisi" required>
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
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Revisi</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="revisiTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 30%;">Jenis Revisi</th>
            <th style="width: 55%;">Keterangan</th>
            <th style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data akan dimuat via AJAX -->
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
        <h5 class="modal-title" id="editModalLabel">Edit Revisi</h5>
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
            <label for="edit_jenis_revisi">Jenis Revisi <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_jenis_revisi" name="jenis_revisi" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="edit_keterangan">Keterangan <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_keterangan" name="keterangan" required>
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
        <p>Apakah Anda yakin ingin menghapus revisi <strong id="delete_item_name"></strong>?</p>
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
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'jenis_revisi', name: 'jenis_revisi' },
      { data: 'keterangan', name: 'keterangan' },
      { 
        data: 'action', 
        name: 'action', 
        orderable: false, 
        searchable: false 
      }
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
  $('#search_jenis, #search_keterangan').on('keyup', function() {
    table.ajax.reload();
  });

  // Add Form Submit
  $('#addForm').on('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    $.ajax({
      url: '{{ route("revisi.store") }}',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
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
          toastr.error('Terjadi kesalahan saat menyimpan data');
        }
      }
    });
  });

  // Edit Button Click
  $(document).on('click', '.edit-btn', function() {
    var id = $(this).data('id');
    var jenis = $(this).data('jenis');
    var keterangan = $(this).data('keterangan');
    
    $('#edit_id').val(id);
    $('#edit_jenis_revisi').val(jenis);
    $('#edit_keterangan').val(keterangan);
    $('#editModal').modal('show');
    clearValidation('#editForm');
  });

  // Update Button Click
  $('#updateBtn').on('click', function() {
    var id = $('#edit_id').val();
    var formData = new FormData($('#editForm')[0]);
    
    $.ajax({
      url: '{{ route("revisi.update", ":id") }}'.replace(':id', id),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if(response.success) {
          toastr.success(response.message);
          $('#editModal').modal('hide');
          table.ajax.reload();
          clearValidation('#editForm');
        }
      },
      error: function(xhr) {
        if(xhr.status === 422) {
          var errors = xhr.responseJSON.errors;
          displayValidationErrors(errors, '#editForm');
        } else {
          toastr.error('Terjadi kesalahan saat mengupdate data');
        }
      }
    });
  });

  // Delete Button Click
  $(document).on('click', '.delete-btn', function() {
    var id = $(this).data('id');
    var jenis = $(this).data('jenis');
    var keterangan = $(this).data('keterangan');
    
    $('#delete_item_name').text(jenis + ' - ' + keterangan);
    $('#deleteBtn').data('id', id);
    $('#deleteModal').modal('show');
  });

  // Delete Confirmation
  $('#deleteBtn').on('click', function() {
    var id = $(this).data('id');
    
    $.ajax({
      url: '{{ route("revisi.destroy", ":id") }}'.replace(':id', id),
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
        var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan saat menghapus data';
        toastr.error(errorMessage);
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