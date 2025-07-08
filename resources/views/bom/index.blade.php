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
      <label>Cari Kode BOM</label>
      <input type="text" class="form-control" id="search_kode" placeholder="Cari berdasarkan kode BOM...">
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
            <th style="width: 15%;">Kode BOM</th>
            <th style="width: 20%;">Proyek</th>
            <th style="width: 15%;">Revisi</th>
            <th style="width: 15%;">Tanggal</th>
            <th style="width: 20%;">Deskripsi</th>
            <th style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>BOM-001</td>
            <td>Proyek Pembangunan Hotel</td>
            <td>Rev. 1.0</td>
            <td>15/06/2023</td>
            <td>BOM untuk struktur utama</td>
            <td>
              <button class="btn btn-sm btn-info" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>BOM-002</td>
            <td>Proyek Apartemen Taman</td>
            <td>Rev. 2.1</td>
            <td>22/06/2023</td>
            <td>BOM untuk interior lantai 3</td>
            <td>
              <button class="btn btn-sm btn-info" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>BOM-003</td>
            <td>Proyek Jembatan Suramadu</td>
            <td>Rev. 3.0</td>
            <td>05/07/2023</td>
            <td>BOM untuk material baja</td>
            <td>
              <button class="btn btn-sm btn-info" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>BOM-004</td>
            <td>Proyek Mall Metropolitan</td>
            <td>Rev. 1.2</td>
            <td>12/07/2023</td>
            <td>BOM untuk sistem elektrikal</td>
            <td>
              <button class="btn btn-sm btn-info" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>BOM-005</td>
            <td>Proyek Gedung Perkantoran</td>
            <td>Rev. 1.0</td>
            <td>18/07/2023</td>
            <td>BOM untuk material finishing</td>
            <td>
              <button class="btn btn-sm btn-info" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Detail BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Kode BOM</label>
              <input type="text" class="form-control" id="view_kode" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Proyek</label>
              <input type="text" class="form-control" id="view_proyek" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Revisi</label>
              <input type="text" class="form-control" id="view_revisi" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Tanggal</label>
              <input type="text" class="form-control" id="view_tanggal" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>Deskripsi</label>
          <textarea class="form-control" id="view_deskripsi" rows="3" readonly></textarea>
        </div>
        
        <h5>Item BOM</h5>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Material</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody id="view_items">
              <!-- Items will be populated here -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
        <p>Apakah Anda yakin ingin menghapus BOM <strong id="delete_item_name"></strong>?</p>
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
  var table = $('#bomTable').DataTable({
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
    var searchKode = $('#search_kode').val();
    var searchProyek = $('#search_proyek').val();
    var searchRevisi = $('#search_revisi').val();

    // Apply individual column filters
    table.column(1).search(searchKode, true, false);
    table.column(2).search(searchProyek, true, false);
    table.column(3).search(searchRevisi, true, false);

    table.draw();
  }

  // Real-time search
  $('#search_kode, #search_proyek, #search_revisi').on('keyup', function() {
    clearTimeout($(this).data('timeout'));
    $(this).data('timeout', setTimeout(function() {
      applyFilters();
    }, 300));
  });

  // View Modal
  $(document).on('click', '.btn-info', function() {
    var row = $(this).closest('tr');
    var kode = row.find('td:eq(1)').text();
    var proyek = row.find('td:eq(2)').text();
    var revisi = row.find('td:eq(3)').text();
    var tanggal = row.find('td:eq(4)').text();
    var deskripsi = row.find('td:eq(5)').text();

    $('#view_kode').val(kode);
    $('#view_proyek').val(proyek);
    $('#view_revisi').val(revisi);
    $('#view_tanggal').val(tanggal);
    $('#view_deskripsi').val(deskripsi);
    
    // Sample items data
    var itemsHtml = '';
    var sampleItems = [
      { material: 'Besi Beton 10mm', qty: '150', uom: 'Batang', notes: 'Untuk kolom utama' },
      { material: 'Semen Portland', qty: '50', uom: 'Sak', notes: 'Grade 40' },
      { material: 'Pasir Beton', qty: '5', uom: 'm³', notes: 'Kualitas baik' }
    ];
    
    sampleItems.forEach(function(item, index) {
      itemsHtml += `
        <tr>
          <td>${index + 1}</td>
          <td>${item.material}</td>
          <td>${item.qty}</td>
          <td>${item.uom}</td>
          <td>${item.notes}</td>
        </tr>
      `;
    });
    
    $('#view_items').html(itemsHtml);
    $('#viewModal').modal('show');
  });

  // Delete Modal
  $(document).on('click', '.btn-danger', function() {
    var row = $(this).closest('tr');
    var kode = row.find('td:eq(1)').text();
    var proyek = row.find('td:eq(2)').text();
    
    $('#delete_item_name').text(kode + ' - ' + proyek);
    $('#deleteModal').modal('show');
  });

  // Delete action
  $('#deleteModal .btn-danger').on('click', function() {
    setTimeout(function() {
      $('#deleteModal').modal('hide');
      // Simulate success message
      console.log('BOM berhasil dihapus!');
      // In a real app, you would refresh the table here
    }, 500);
  });
});
</script>
@endpush