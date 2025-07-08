@extends('layouts.app')

@section('title', 'Kode Material')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Kode Material</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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
    <form>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="kode_material">Kode Material</label>
          <input type="text" class="form-control" id="kode_material" placeholder="KM001">
        </div>
        <div class="form-group col-md-4">
          <label for="nama_material">Nama Material</label>
          <input type="text" class="form-control" id="nama_material" placeholder="Nama Material">
        </div>
        <div class="form-group col-md-4">
          <label for="spesifikasi">Spesifikasi</label>
          <input type="text" class="form-control" id="spesifikasi" placeholder="Spesifikasi">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-3">
          <label for="satuan">Satuan</label>
          <select class="form-control" id="satuan">
            <option value="">Pilih Satuan</option>
            <option value="Batang">Batang</option>
            <option value="Kg">Kg</option>
            <option value="Pcs">Pcs</option>
            <option value="Meter">Meter</option>
            <option value="Liter">Liter</option>
          </select>
        </div>
        <div class="col-md-9">
          <label>&nbsp;</label>
          <div>
            <button type="button" class="btn btn-primary">
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
        <option value="Batang">Batang</option>
        <option value="Kg">Kg</option>
        <option value="Pcs">Pcs</option>
        <option value="Meter">Meter</option>
        <option value="Liter">Liter</option>
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
          <tr>
            <td>1</td>
            <td>KM001</td>
            <td>Besi Hollow</td>
            <td>2x4 galvanis</td>
            <td>Batang</td>
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
            <td>KM002</td>
            <td>Cat Tembok</td>
            <td>Vinilex 5kg</td>
            <td>Kg</td>
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
            <td>KM003</td>
            <td>Paku Beton</td>
            <td>3 inch</td>
            <td>Pcs</td>
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
            <td>KM004</td>
            <td>Kabel Listrik</td>
            <td>NYM 2.5mm</td>
            <td>Meter</td>
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
            <td>KM005</td>
            <td>Semen Portland</td>
            <td>40kg per sak</td>
            <td>Kg</td>
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
            <td>KM006</td>
            <td>Thinner</td>
            <td>Epoxy grade A</td>
            <td>Liter</td>
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
            <td>7</td>
            <td>KM007</td>
            <td>Pipa PVC</td>
            <td>4 inch grade A</td>
            <td>Meter</td>
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
            <td>8</td>
            <td>KM008</td>
            <td>Genteng Beton</td>
            <td>Flat minimalis</td>
            <td>Pcs</td>
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
        <h5 class="modal-title" id="editModalLabel">Edit Kode Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="edit_kode_material">Kode Material</label>
            <input type="text" class="form-control" id="edit_kode_material">
          </div>
          <div class="form-group">
            <label for="edit_nama_material">Nama Material</label>
            <input type="text" class="form-control" id="edit_nama_material">
          </div>
          <div class="form-group">
            <label for="edit_spesifikasi">Spesifikasi</label>
            <input type="text" class="form-control" id="edit_spesifikasi">
          </div>
          <div class="form-group">
            <label for="edit_satuan">Satuan</label>
            <select class="form-control" id="edit_satuan">
              <option value="Batang">Batang</option>
              <option value="Kg">Kg</option>
              <option value="Pcs">Pcs</option>
              <option value="Meter">Meter</option>
              <option value="Liter">Liter</option>
            </select>
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
        <p>Apakah Anda yakin ingin menghapus kode material <strong id="delete_item_name"></strong>?</p>
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
  var table = $('#materialTable').DataTable({
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
    order: [[1, 'asc']],
    columnDefs: [
      { 
        targets: [0, 5], 
        orderable: false 
      }
    ]
  });

  // Custom search filters
  function applyFilters() {
    var searchKode = $('#search_kode').val();
    var searchNama = $('#search_nama').val();
    var searchSpesifikasi = $('#search_spesifikasi').val();
    var searchSatuan = $('#search_satuan').val();

    // Apply individual column filters
    table.column(1).search(searchKode, true, false);
    table.column(2).search(searchNama, true, false);
    table.column(3).search(searchSpesifikasi, true, false);
    table.column(4).search(searchSatuan, true, false);

    table.draw();
  }

  // Real-time search
  $('#search_kode, #search_nama, #search_spesifikasi').on('keyup', function() {
    clearTimeout($(this).data('timeout'));
    $(this).data('timeout', setTimeout(function() {
      applyFilters();
    }, 300));
  });

  $('#search_satuan').on('change', function() {
    applyFilters();
  });

  // Edit Modal
  $(document).on('click', '.btn-warning', function() {
    var row = $(this).closest('tr');
    var kode = row.find('td:eq(1)').text();
    var nama = row.find('td:eq(2)').text();
    var spesifikasi = row.find('td:eq(3)').text();
    var satuan = row.find('td:eq(4)').text();

    $('#edit_kode_material').val(kode);
    $('#edit_nama_material').val(nama);
    $('#edit_spesifikasi').val(spesifikasi);
    $('#edit_satuan').val(satuan);
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