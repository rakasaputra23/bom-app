@extends('layouts.app')

@section('title', 'Kode Material')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Kode Material</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Kode Material</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Form Tambah -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah Kode Material</h3>
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
        <div class="form-group col-md-4">
          <label for="kode_material">Kode Material <span class="text-red">*</span></label>
          <input type="text" class="form-control" id="kode_material" name="kode_material" placeholder="KM001" required>
          <div class="invalid-feedback"></div>
        </div>
        <div class="form-group col-md-4">
          <label for="nama_material">Nama Material <span class="text-red">*</span></label>
          <input type="text" class="form-control" id="nama_material" name="nama_material" placeholder="Nama Material" required>
          <div class="invalid-feedback"></div>
        </div>
        <div class="form-group col-md-4">
          <label for="spesifikasi">Spesifikasi</label>
          <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" placeholder="Spesifikasi">
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-3">
          <label for="uom_id">Satuan <span class="text-red">*</span></label>
          <select class="form-control" id="uom_id" name="uom_id" required>
            <option value="">Pilih Satuan</option>
            @foreach($uoms as $uom)
              <option value="{{ $uom->id }}">{{ $uom->satuan }}</option>
            @endforeach
          </select>
          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-9">
          <label>&nbsp;</label>
          <div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-secondary">
              <i class="fas fa-undo"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Filter Pencarian -->
<div class="row">
  <div class="col-md-3">
    <div class="form-group">
      <label>Cari Kode Material</label>
      <input type="text" class="form-control" id="search_kode" placeholder="Cari berdasarkan kode...">
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label>Cari Nama Material</label>
      <input type="text" class="form-control" id="search_nama" placeholder="Cari berdasarkan nama...">
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label>Cari Spesifikasi</label>
      <input type="text" class="form-control" id="search_spesifikasi" placeholder="Cari spesifikasi...">
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label>Filter Satuan</label>
      <select class="form-control" id="search_satuan">
        <option value="">Semua Satuan</option>
        @foreach($uoms as $uom)
          <option value="{{ $uom->satuan }}">{{ $uom->satuan }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Kode Material</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="materialTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Kode Material</th>
            <th style="width: 25%;">Nama Material</th>
            <th style="width: 25%;">Spesifikasi</th>
            <th style="width: 10%;">Satuan</th>
            <th style="width: 20%;">Aksi</th>
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
        <h5 class="modal-title" id="editModalLabel">Edit Kode Material</h5>
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
            <label for="edit_kode_material">Kode Material <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_kode_material" name="kode_material" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="edit_nama_material">Nama Material <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="edit_nama_material" name="nama_material" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="edit_spesifikasi">Spesifikasi</label>
            <input type="text" class="form-control" id="edit_spesifikasi" name="spesifikasi">
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="edit_uom_id">Satuan <span class="text-red">*</span></label>
            <select class="form-control" id="edit_uom_id" name="uom_id" required>
              <option value="">Pilih Satuan</option>
              @foreach($uoms as $uom)
                <option value="{{ $uom->id }}">{{ $uom->satuan }}</option>
              @endforeach
            </select>
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
        <p>Apakah Anda yakin ingin menghapus kode material <strong id="delete_item_name"></strong>?</p>
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
  var table = $('#materialTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route("kode-material.getData") }}',
      data: function(d) {
        d.search_kode = $('#search_kode').val();
        d.search_nama = $('#search_nama').val();
        d.search_spesifikasi = $('#search_spesifikasi').val();
        d.search_satuan = $('#search_satuan').val();
      }
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'kode_material', name: 'kode_material' },
      { data: 'nama_material', name: 'nama_material' },
      { data: 'spesifikasi', name: 'spesifikasi' },
      { data: 'satuan', name: 'satuan' },
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
          columns: [0, 1, 2, 3, 4]
        }
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-danger btn-sm',
        exportOptions: {
          columns: [0, 1, 2, 3, 4]
        }
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Print',
        className: 'btn btn-info btn-sm',
        exportOptions: {
          columns: [0, 1, 2, 3, 4]
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
  $('#search_kode, #search_nama, #search_spesifikasi').on('keyup', function() {
    table.ajax.reload();
  });

  $('#search_satuan').on('change', function() {
    table.ajax.reload();
  });

  // Add Form Submit
  $('#addForm').on('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    $.ajax({
      url: '{{ route("kode-material.store") }}',
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
    
    $.ajax({
      url: '{{ route("kode-material.show", ":id") }}'.replace(':id', id),
      method: 'GET',
      success: function(response) {
        if(response.success) {
          var data = response.data;
          $('#edit_id').val(data.id);
          $('#edit_kode_material').val(data.kode_material);
          $('#edit_nama_material').val(data.nama_material);
          $('#edit_spesifikasi').val(data.spesifikasi);
          $('#edit_uom_id').val(data.uom_id);
          $('#editModal').modal('show');
          clearValidation('#editForm');
        }
      },
      error: function() {
        toastr.error('Gagal memuat data');
      }
    });
  });

  // Update Button Click
  $('#updateBtn').on('click', function() {
    var id = $('#edit_id').val();
    var formData = new FormData($('#editForm')[0]);
    
    $.ajax({
      url: '{{ route("kode-material.update", ":id") }}'.replace(':id', id),
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
    var kode = $(this).data('kode');
    var nama = $(this).data('nama');
    
    $('#delete_item_name').text(kode + ' - ' + nama);
    $('#deleteBtn').data('id', id);
    $('#deleteModal').modal('show');
  });

  // Delete Confirmation
  $('#deleteBtn').on('click', function() {
    var id = $(this).data('id');
    
    $.ajax({
      url: '{{ route("kode-material.destroy", ":id") }}'.replace(':id', id),
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