@extends('layouts.app')

@section('title', 'Bill of Materials')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Bill of Materials</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">BOM</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Filter Pencarian -->
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Nomor BOM</label>
      <input type="text" class="form-control" id="search_nomor" placeholder="Cari berdasarkan nomor BOM...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Proyek</label>
      <input type="text" class="form-control" id="search_proyek" placeholder="Cari berdasarkan proyek...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Revisi</label>
      <input type="text" class="form-control" id="search_revisi" placeholder="Cari berdasarkan revisi...">
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar BOM</h3>
    <div class="card-tools">
      <a href="{{ route('bom.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat BOM Baru
      </a>
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="bomTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Nomor</th>
            <th style="width: 20%;">Proyek</th>
            <th style="width: 15%;">Tgl. Terbit</th>
            <th style="width: 15%;">Revisi</th>
            <th style="width: 20%;">Kategori</th>
            <th style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($billOfMaterials as $index => $bom)
          <tr data-bom-id="{{ $bom->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $bom->nomor_bom }}</td>
            <td>{{ $bom->proyek->nama_proyek }}</td>
            <td>{{ date('d/m/Y', strtotime($bom->tanggal)) }}</td>
            <td>{{ $bom->revisi->jenis_revisi }}</td>
            <td>{{ $bom->kategori }}</td>
            <td>
              <button class="btn btn-sm btn-info view-btn" title="Lihat" data-bom-id="{{ $bom->id }}">
                <i class="fas fa-eye"></i>
              </button>
              <a href="{{ route('bom.edit', $bom->id) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <button class="btn btn-sm btn-danger delete-btn" title="Hapus" data-bom-id="{{ $bom->id }}" data-bom-nomor="{{ $bom->nomor_bom }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="viewModalLabel">Detail BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label font-weight-bold">Nomor BOM</label>
                <div class="col-sm-8">
                  <p class="form-control-plaintext" id="view_nomor"></p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label font-weight-bold">Proyek</label>
                <div class="col-sm-8">
                  <p class="form-control-plaintext" id="view_proyek"></p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label font-weight-bold">Tanggal</label>
                <div class="col-sm-8">
                  <p class="form-control-plaintext" id="view_tanggal"></p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label font-weight-bold">Revisi</label>
                <div class="col-sm-8">
                  <p class="form-control-plaintext" id="view_revisi"></p>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12 text-center">
              <h4 class="bg-light py-2" id="view_kategori"></h4>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="bg-secondary">
                <tr>
                  <th width="5%">REV</th>
                  <th width="5%">NO</th>
                  <th width="15%">KODE MATERIAL</th>
                  <th width="25%">DESKRIPSI</th>
                  <th width="10%">QTY</th>
                  <th width="10%">SATUAN</th>
                  <th width="15%">SPESIFIKASI</th>
                  <th width="15%">KETERANGAN</th>
                </tr>
              </thead>
              <tbody id="view_items">
                <!-- Items will be loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus BOM berikut?</p>
        <table class="table table-sm">
          <tr>
            <th width="30%">Nomor BOM</th>
            <td id="delete_nomor"></td>
          </tr>
          <tr>
            <th>Proyek</th>
            <td id="delete_proyek"></td>
          </tr>
        </table>
        <div class="alert alert-warning mt-3">
          <i class="icon fas fa-exclamation-triangle"></i>
          Data yang dihapus tidak dapat dikembalikan!
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <i class="fas fa-trash"></i> Hapus
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('scripts')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#bomTable').DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
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

  // Search functionality
  $('#search_nomor').on('keyup', function() {
    table.column(1).search(this.value).draw();
  });

  $('#search_proyek').on('keyup', function() {
    table.column(2).search(this.value).draw();
  });

  $('#search_revisi').on('keyup', function() {
    table.column(4).search(this.value).draw();
  });

  // View BOM
  $(document).on('click', '.view-btn', function() {
    var bomId = $(this).data('bom-id');
    
    // Show loading
    $('#viewModal .modal-body').html(`
      <div class="text-center py-4">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
        <p>Memuat data...</p>
      </div>
    `);
    $('#viewModal').modal('show');

    // Get data via AJAX
    $.ajax({
      url: `/bom/${bomId}`,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        // Restore modal body structure
        $('#viewModal .modal-body').html(`
          <div class="container-fluid">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Nomor BOM</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_nomor"></p>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Proyek</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_proyek"></p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Tanggal</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_tanggal"></p>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Revisi</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_revisi"></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12 text-center">
                <h4 class="bg-light py-2" id="view_kategori"></h4>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="bg-secondary">
                  <tr>
                    <th width="5%">REV</th>
                    <th width="5%">NO</th>
                    <th width="15%">KODE MATERIAL</th>
                    <th width="25%">DESKRIPSI</th>
                    <th width="10%">QTY</th>
                    <th width="10%">SATUAN</th>
                    <th width="15%">SPESIFIKASI</th>
                    <th width="15%">KETERANGAN</th>
                  </tr>
                </thead>
                <tbody id="view_items">
                  <!-- Items will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        `);

        // Fill modal with data
        $('#view_nomor').text(response.nomor_bom);
        $('#view_proyek').text(response.proyek.nama_proyek);
        $('#view_tanggal').text(response.tanggal_formatted);
        $('#view_revisi').text(response.revisi.jenis_revisi);
        $('#view_kategori').text(response.kategori);

        // Fill items table
        var itemsHtml = '';
        if (response.item_bom && response.item_bom.length > 0) {
          response.item_bom.forEach(function(item, index) {
            itemsHtml += `
              <tr>
                <td>${item.rev_no || '-'}</td>
                <td>${index + 1}</td>
                <td>${item.kode_material.kode_material}</td>
                <td>${item.kode_material.nama_material}</td>
                <td>${item.qty || 0}</td>
                <td>${item.satuan || '-'}</td>
                <td>${item.kode_material.spesifikasi || '-'}</td>
                <td>${item.keterangan || '-'}</td>
              </tr>
            `;
          });
        } else {
          itemsHtml = '<tr><td colspan="8" class="text-center">Tidak ada item</td></tr>';
        }
        $('#view_items').html(itemsHtml);
      },
      error: function(xhr) {
        $('#viewModal .modal-body').html(`
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            Gagal memuat data. Silakan coba lagi.
          </div>
        `);
      }
    });
  });

  // Delete BOM
  var deleteId = null;
  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('bom-id');
    var bomNomor = $(this).data('bom-nomor');
    var $row = $(this).closest('tr');
    var proyekNama = $row.find('td:eq(2)').text();
    
    // Fill confirmation modal
    $('#delete_nomor').text(bomNomor);
    $('#delete_proyek').text(proyekNama);
    $('#deleteModal').modal('show');
  });

  // Confirm delete
  $('#confirmDelete').on('click', function() {
    if (deleteId) {
      $.ajax({
        url: `/bom/${deleteId}`,
        type: 'DELETE',
        data: {
          '_token': '{{ csrf_token() }}'
        },
        success: function(response) {
          $('#deleteModal').modal('hide');
          
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: response.message || 'BOM berhasil dihapus.',
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: response.message || 'Gagal menghapus BOM.'
            });
          }
        },
        error: function(xhr) {
          $('#deleteModal').modal('hide');
          var message = 'Gagal menghapus BOM.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          }
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message
          });
        }
      });
    }
  });

  // Clear deleteId when modal is closed
  $('#deleteModal').on('hidden.bs.modal', function() {
    deleteId = null;
  });
});
</script>
@endpush