@extends('layouts.app')

@section('title', 'User Management')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">User Management</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item active">User</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Form Tambah -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah User</h3>
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
          <label for="nama">Nama Lengkap</label>
          <input type="text" class="form-control" id="nama" placeholder="Nama Lengkap">
        </div>
        <div class="form-group col-md-6">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" placeholder="Username">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
          <label for="user_group">User Group</label>
          <select class="form-control" id="user_group">
            <option value="">Pilih User Group</option>
            <option value="1">Administrator</option>
            <option value="2">Manager</option>
            <option value="3">Staff</option>
            <option value="4">Viewer</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Password">
        </div>
        <div class="form-group col-md-6">
          <label for="confirm_password">Konfirmasi Password</label>
          <input type="password" class="form-control" id="confirm_password" placeholder="Konfirmasi Password">
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
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Nama</label>
      <input type="text" class="form-control" id="search_nama" placeholder="Cari berdasarkan nama...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Username</label>
      <input type="text" class="form-control" id="search_username" placeholder="Cari berdasarkan username...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari User Group</label>
      <select class="form-control" id="search_user_group">
        <option value="">Semua User Group</option>
        <option value="1">Administrator</option>
        <option value="2">Manager</option>
        <option value="3">Staff</option>
        <option value="4">Viewer</option>
      </select>
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar User</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="userTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 20%;">Nama</th>
            <th style="width: 15%;">Username</th>
            <th style="width: 20%;">Email</th>
            <th style="width: 15%;">User Group</th>
            <th style="width: 15%;">Status</th>
            <th style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Admin Sistem</td>
            <td>admin</td>
            <td>admin@example.com</td>
            <td>Administrator</td>
            <td><span class="badge bg-success">Aktif</span></td>
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
            <td>Manager Proyek</td>
            <td>manager</td>
            <td>manager@example.com</td>
            <td>Manager</td>
            <td><span class="badge bg-success">Aktif</span></td>
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
            <td>Staff IT</td>
            <td>staff</td>
            <td>staff@example.com</td>
            <td>Staff</td>
            <td><span class="badge bg-success">Aktif</span></td>
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
            <td>Viewer Only</td>
            <td>viewer</td>
            <td>viewer@example.com</td>
            <td>Viewer</td>
            <td><span class="badge bg-danger">Nonaktif</span></td>
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
        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="edit_nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="edit_nama">
          </div>
          <div class="form-group">
            <label for="edit_username">Username</label>
            <input type="text" class="form-control" id="edit_username">
          </div>
          <div class="form-group">
            <label for="edit_email">Email</label>
            <input type="email" class="form-control" id="edit_email">
          </div>
          <div class="form-group">
            <label for="edit_user_group">User Group</label>
            <select class="form-control" id="edit_user_group">
              <option value="1">Administrator</option>
              <option value="2">Manager</option>
              <option value="3">Staff</option>
              <option value="4">Viewer</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_status">Status</label>
            <select class="form-control" id="edit_status">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
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
        <p>Apakah Anda yakin ingin menghapus user <strong id="delete_user_name"></strong>?</p>
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
  var table = $('#userTable').DataTable({
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
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-danger btn-sm',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Print',
        className: 'btn btn-info btn-sm',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
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
        targets: [0, 6], 
        orderable: false 
      }
    ]
  });

  // Custom search filters
  function applyFilters() {
    var searchNama = $('#search_nama').val();
    var searchUsername = $('#search_username').val();
    var searchUserGroup = $('#search_user_group').val();

    // Apply individual column filters
    table.column(1).search(searchNama, true, false);
    table.column(2).search(searchUsername, true, false);
    table.column(4).search(searchUserGroup, true, false);

    table.draw();
  }

  // Real-time search
  $('#search_nama, #search_username').on('keyup', function() {
    clearTimeout($(this).data('timeout'));
    $(this).data('timeout', setTimeout(function() {
      applyFilters();
    }, 300));
  });

  // User group select filter
  $('#search_user_group').on('change', function() {
    applyFilters();
  });

  // Edit Modal
  $(document).on('click', '.btn-warning', function() {
    var row = $(this).closest('tr');
    var nama = row.find('td:eq(1)').text();
    var username = row.find('td:eq(2)').text();
    var email = row.find('td:eq(3)').text();
    var userGroup = row.find('td:eq(4)').text();
    var status = row.find('td:eq(5)').find('.badge').text();

    $('#edit_nama').val(nama);
    $('#edit_username').val(username);
    $('#edit_email').val(email);
    $('#edit_user_group').val(userGroup);
    $('#edit_status').val(status === 'Aktif' ? '1' : '0');
  });

  // Delete Modal
  $(document).on('click', '.btn-danger', function() {
    var row = $(this).closest('tr');
    var nama = row.find('td:eq(1)').text();
    var username = row.find('td:eq(2)').text();
    
    $('#delete_user_name').text(nama + ' (' + username + ')');
  });

  // Success notification simulation
  $('.modal .btn-primary, .modal .btn-danger').on('click', function() {
    var modal = $(this).closest('.modal');
    var isEdit = modal.attr('id') === 'editModal';
    
    setTimeout(function() {
      modal.modal('hide');
      
      // Simulate success message
      var message = isEdit ? 'Data user berhasil diupdate!' : 'Data user berhasil dihapus!';
      var alertType = isEdit ? 'success' : 'warning';
      
      // You can integrate with toastr or other notification library here
      console.log(message);
    }, 500);
  });
});
</script>
@endpush