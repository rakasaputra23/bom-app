@extends('layouts.app')

@section('title', 'Proyek')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Proyek</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item active">Proyek</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Form Tambah -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah Proyek</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <form>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="kode_proyek">Kode Proyek</label>
          <input type="text" class="form-control" id="kode_proyek" placeholder="PRJ001">
        </div>
        <div class="form-group col-md-6">
          <label for="nama_proyek">Nama Proyek</label>
          <input type="text" class="form-control" id="nama_proyek" placeholder="Nama Proyek">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="button" class="btn btn-primary">
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
      <label>Cari Kode Proyek</label>
      <input type="text" class="form-control" id="search_kode" placeholder="Cari berdasarkan kode proyek...">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Cari Nama Proyek</label>
      <input type="text" class="form-control" id="search_nama" placeholder="Cari berdasarkan nama proyek...">
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Proyek</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="proyekTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 25%;">Kode Proyek</th>
            <th style="width: 45%;">Nama Proyek</th>
            <th style="width: 20%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>PRJ001</td>
            <td>Pembangunan Gedung Kantor</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>PRJ002</td>
            <td>Renovasi Rumah Sakit</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>PRJ003</td>
            <td>Konstruksi Jembatan</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>PRJ004</td>
            <td>Pembangunan Perumahan</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>PRJ005</td>
            <td>Infrastruktur Jalan Raya</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>6</td>
            <td>PRJ006</td>
            <td>Pembangunan Mall</td>
            <td>
              <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
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
        <h5 class="modal-title" id="editModalLabel">Edit Proyek</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="edit_kode_proyek">Kode Proyek</label>
            <input type="text" class="form-control" id="edit_kode_proyek" readonly>
          </div>
          <div class="form-group">
            <label for="edit_nama_proyek">Nama Proyek</label>
            <input type="text" class="form-control" id="edit_nama_proyek">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary">Simpan Perubahan</button>
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
        <p>Apakah Anda yakin ingin menghapus proyek <strong id="delete_item_name"></strong>?</p>
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i>
          Data yang telah dihapus tidak dapat dikembalikan!
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger">Hapus</button>
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

<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#proyekTable').DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
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
    order: [[1, 'asc']],
    columnDefs: [
      { 
        targets: [0, 3], 
        orderable: false 
      }
    ]
  });

  // Custom search filters
  function applyFilters() {
    var searchKode = $('#search_kode').val();
    var searchNama = $('#search_nama').val();

    // Apply individual column filters
    table.column(1).search(searchKode, true, false);
    table.column(2).search(searchNama, true, false);

    table.draw();
  }

  // Real-time search
  $('#search_kode, #search_nama').on('keyup', function() {
    clearTimeout($(this).data('timeout'));
    $(this).data('timeout', setTimeout(function() {
      applyFilters();
    }, 300));
  });

  // Edit Modal
  $(document).on('click', '.btn-warning', function() {
    var row = $(this).closest('tr');
    var kode = row.find('td:eq(1)').text();
    var nama = row.find('td:eq(2)').text();

    $('#edit_kode_proyek').val(kode);
    $('#edit_nama_proyek').val(nama);
  });

  // Delete Modal
  $(document).on('click', '.btn-danger', function() {
    var row = $(this).closest('tr');
    var kode = row.find('td:eq(1)').text();
    var nama = row.find('td:eq(2)').text();
    
    $('#delete_item_name').text(kode + ' - ' + nama);
  });

  // Success notification simulation
  $('.modal .btn-primary, .modal .btn-danger').on('click', function() {
    var modal = $(this).closest('.modal');
    var isEdit = modal.attr('id') === 'editModal';
    
    setTimeout(function() {
      modal.modal('hide');
      
      // Simulate success message
      var message = isEdit ? 'Data berhasil diupdate!' : 'Data berhasil dihapus!';
      var alertType = isEdit ? 'success' : 'warning';
      
      // You can integrate with toastr or other notification library here
      console.log(message);
    }, 500);
  });
});
</script>
@endpush