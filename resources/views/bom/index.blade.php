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
<!-- Statistics Cards -->
<div class="row mb-3">
  <div class="col-lg-2 col-6">
    <div class="small-box bg-secondary">
      <div class="inner">
        <h3 id="stat-draft">0</h3>
        <p>Draft</p>
      </div>
      <div class="icon">
        <i class="fas fa-edit"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3 id="stat-pending1">0</h3>
        <p>Pending Approval 1</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3 id="stat-pending2">0</h3>
        <p>Pending Approval 2</p>
      </div>
      <div class="icon">
        <i class="fas fa-hourglass-half"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3 id="stat-approved">0</h3>
        <p>Approved</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3 id="stat-rejected">0</h3>
        <p>Rejected</p>
      </div>
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3 id="stat-total">0</h3>
        <p>Total BOM</p>
      </div>
      <div class="icon">
        <i class="fas fa-list-alt"></i>
      </div>
    </div>
  </div>
</div>

<!-- Action Buttons -->
<div class="row mb-3">
  <div class="col-md-6">
    @if(Auth::user()->can('bom.create'))
      <a href="{{ route('bom.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat BOM Baru
      </a>
    @endif
  </div>
  <div class="col-md-6 text-right">
    <button type="button" class="btn btn-secondary" id="clearFilters">
      <i class="fas fa-eraser"></i> Clear Filter
    </button>
    <button type="button" class="btn btn-info" id="refreshData">
      <i class="fas fa-sync-alt"></i> Refresh Data
    </button>
  </div>
</div>

<!-- Filter Pencarian -->
<div class="card card-outline card-secondary collapsed-card">
  <div class="card-header">
    <h3 class="card-title">
      <i class="fas fa-filter"></i> Filter & Pencarian
    </h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>Cari Nomor BOM</label>
          <input type="text" class="form-control" id="search_nomor" placeholder="Cari berdasarkan nomor BOM...">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Cari Proyek</label>
          <input type="text" class="form-control" id="search_proyek" placeholder="Cari berdasarkan proyek...">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Filter Status</label>
          <select class="form-control" id="filter_status">
            <option value="">Semua Status</option>
            <option value="DRAFT">Draft</option>
            <option value="Pending Approval 1">Pending Approval 1</option>
            <option value="Pending Approval 2">Pending Approval 2</option>
            <option value="APPROVED">Approved</option>
            <option value="REJECTED">Rejected</option>
            <!-- Alternative values if the above don't work -->
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Cari Pembuat</label>
          <input type="text" class="form-control" id="search_creator" placeholder="Cari berdasarkan pembuat...">
        </div>
      </div>
    </div>
    <!-- New Row for Date Filters -->
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>Filter Tanggal Mulai</label>
          <div class="input-group">
            <input type="text" class="form-control" id="date_from" placeholder="dd/mm/yyyy" readonly>
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-calendar-alt"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Filter Tanggal Akhir</label>
          <div class="input-group">
            <input type="text" class="form-control" id="date_to" placeholder="dd/mm/yyyy" readonly>
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-calendar-alt"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Filter Rentang Tanggal</label>
          <div class="input-group">
            <input type="text" class="form-control" id="date_range" placeholder="Pilih rentang tanggal..." readonly>
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-calendar-range"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <div>
            <button type="button" class="btn btn-primary btn-block" id="applyDateFilter">
              <i class="fas fa-filter"></i> Terapkan Filter Tanggal
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar BOM</h3>
    <div class="card-tools">
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
            <th style="width: 12%;">Nomor</th>
            <th style="width: 15%;">Proyek</th>
            <th style="width: 10%;">Tgl. Terbit</th>
            <th style="width: 10%;">Kategori</th>
            <th style="width: 12%;">Status</th>
            <th style="width: 12%;">Pembuat</th>
            <th style="width: 12%;">Terakhir Update</th>
            <th style="width: 12%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($billOfMaterials as $index => $bom)
          <tr data-bom-id="{{ $bom->id }}" data-status="{{ $bom->status }}">
            <td>{{ $index + 1 }}</td>
            <td>
              <strong>{{ $bom->nomor_bom }}</strong><br>
              <small class="text-muted">{{ $bom->revisi->jenis_revisi ?? '-' }}</small>
            </td>
            <td>
              <strong>{{ $bom->proyek->kode_proyek ?? '-' }}</strong><br>
              <small>{{ Str::limit($bom->proyek->nama_proyek ?? '-', 25) }}</small>
            </td>
            <td>{{ date('d/m/Y', strtotime($bom->tanggal)) }}</td>
            <td><span class="badge badge-light">{{ $bom->kategori }}</span></td>
            <td>{!! $bom->status_badge !!}</td>
            <td>
              <strong>{{ $bom->createdBy->nama ?? '-' }}</strong><br>
              <small class="text-muted">{{ $bom->createdBy->nip ?? '-' }}</small>
            </td>
            <td>
              {{ $bom->updated_at ? $bom->updated_at->format('d/m/Y H:i') : '-' }}<br>
              @if($bom->status === 'APPROVED' && $bom->approvedBy2)
                <small class="text-success">Approved by: {{ $bom->approvedBy2->nama }}</small>
              @elseif($bom->status === 'REJECTED' && $bom->rejectedBy)
                <small class="text-danger">Rejected by: {{ $bom->rejectedBy->nama }}</small>
              @endif
            </td>
            <td>
              <div class="btn-group-vertical" role="group" style="width: 100%;">
                <!-- View Button -->
                <button class="btn btn-sm btn-info view-btn" title="Lihat Detail" data-bom-id="{{ $bom->id }}">
                  <i class="fas fa-eye"></i> Lihat
                </button>

                <!-- Export PDF Button - ADD THIS -->
                @if(Auth::user()->can('bom.export'))
                    <a href="{{ route('bom.export-pdf', $bom->id) }}" 
                      class="btn btn-sm btn-success" 
                      title="Export PDF" 
                      target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                @endif
                
                <!-- Edit Button - Only for DRAFT/REJECTED and creator -->
                @if($bom->canBeEdited() && ($bom->created_by === Auth::id() || Auth::user()->can('bom.edit')))
                  <a href="{{ route('bom.edit', $bom->id) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                @endif

                <!-- Submit Button - Only for creator and DRAFT/REJECTED status -->
                @if($bom->canBeSubmitted() && ($bom->created_by === Auth::id() || Auth::user()->can('bom.submit')))
                  <button class="btn btn-sm btn-primary submit-btn" title="Submit untuk Approval" data-bom-id="{{ $bom->id }}">
                    <i class="fas fa-paper-plane"></i> Submit
                  </button>
                @endif

                <!-- Approval Level 1 Button -->
                @if($bom->canBeApprovedBy1() && Auth::user()->can('bom.approve.1'))
                  <button class="btn btn-sm btn-success approve1-btn" title="Approve Level 1" data-bom-id="{{ $bom->id }}">
                    <i class="fas fa-check"></i> Approve L1
                  </button>
                @endif

                <!-- Approval Level 2 Button -->
                @if($bom->canBeApprovedBy2() && Auth::user()->can('bom.approve.2'))
                  <button class="btn btn-sm btn-success approve2-btn" title="Final Approve" data-bom-id="{{ $bom->id }}">
                    <i class="fas fa-check-double"></i> Final Approve
                  </button>
                @endif

                <!-- Reject Button - For both approval levels -->
                @if($bom->isPending() && Auth::user()->can('bom.reject'))
                  <button class="btn btn-sm btn-danger reject-btn" title="Reject" data-bom-id="{{ $bom->id }}">
                    <i class="fas fa-times"></i> Reject
                  </button>
                @endif

                <!-- Delete Button - Only for DRAFT/REJECTED and creator -->
                @if(in_array($bom->status, ['DRAFT', 'REJECTED']) && ($bom->created_by === Auth::id() || Auth::user()->can('bom.destroy')))
                  <button class="btn btn-sm btn-danger delete-btn" title="Hapus" data-bom-id="{{ $bom->id }}" data-bom-nomor="{{ $bom->nomor_bom }}" data-bom-status="{{ $bom->status }}">
                    <i class="fas fa-trash"></i> Hapus
                  </button>
                @endif
              </div>
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
          <!-- Content will be loaded via AJAX -->
        </div>
      </div>
            <!-- Update modal footer di viewModal -->
      <div class="modal-footer">
          <a href="#" 
            class="btn btn-success" 
            id="exportPdfBtn" 
            target="_blank"
            title="Export ke PDF">
              <i class="fas fa-file-pdf"></i> Export PDF
          </a>
          <a href="#" 
            class="btn btn-info" 
            id="previewPdfBtn" 
            target="_blank"
            title="Preview PDF">
              <i class="fas fa-eye"></i> Preview PDF
          </a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times"></i> Tutup
          </button>
      </div>
    </div>
  </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="approvalModalLabel">Approve BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="approvalForm">
          <div class="form-group">
            <label>Nomor BOM</label>
            <input type="text" class="form-control" id="approval_nomor" readonly>
          </div>
          <div class="form-group">
            <label>Keterangan Approval (Opsional)</label>
            <textarea class="form-control" id="approval_note" rows="3" placeholder="Masukkan keterangan approval..."></textarea>
            <small class="form-text text-muted">Keterangan ini akan tercatat dalam history approval</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="button" class="btn btn-success" id="confirmApproval">
          <i class="fas fa-check"></i> <span id="approvalButtonText">Approve</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="rejectModalLabel">Reject BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="rejectForm">
          <div class="form-group">
            <label>Nomor BOM</label>
            <input type="text" class="form-control" id="reject_nomor" readonly>
          </div>
          <div class="form-group">
            <label>Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="reject_note" rows="4" placeholder="Masukkan alasan penolakan dengan jelas..." required></textarea>
            <small class="form-text text-muted">Alasan penolakan akan dikirim ke pembuat BOM untuk perbaikan</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="button" class="btn btn-danger" id="confirmReject">
          <i class="fas fa-times"></i> Reject BOM
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
<!-- Date Range Picker -->
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<!-- Tempus Dominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<style>
.btn-group-vertical .btn {
  margin-bottom: 2px;
}
.btn-group-vertical .btn:last-child {
  margin-bottom: 0;
}

/* Date filter styling */
.date-filter-active {
  background-color: #e3f2fd !important;
  border: 2px solid #2196f3 !important;
}

.date-range-display {
  font-size: 12px;
  color: #666;
  margin-top: 5px;
}
</style>
@endpush

@push('scripts')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Date Range Picker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempus Dominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<script>
$(document).ready(function() {
  // Load statistics
  loadStatistics();
  
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
    order: [[7, 'desc']],
    columnDefs: [
      { 
        targets: [0, 8], 
        orderable: false 
      }
    ]
  });

  // ===== NEW DATE FILTER FUNCTIONALITY =====
  
  // Initialize date pickers
  $('#date_from').datetimepicker({
    format: 'DD/MM/YYYY',
    locale: 'id'
  });

  $('#date_to').datetimepicker({
    format: 'DD/MM/YYYY',
    locale: 'id'
  });

  // Initialize date range picker
  $('#date_range').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY',
      separator: ' - ',
      applyLabel: 'Terapkan',
      cancelLabel: 'Batal',
      fromLabel: 'Dari',
      toLabel: 'Sampai',
      customRangeLabel: 'Custom',
      weekLabel: 'W',
      daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
      monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
      firstDay: 1
    },
    opens: 'left',
    drops: 'down',
    ranges: {
      'Hari Ini': [moment(), moment()],
      'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
      '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
      'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
      'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
      '3 Bulan Terakhir': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
      'Tahun Ini': [moment().startOf('year'), moment().endOf('year')]
    }
  });

  // Sync individual date inputs with date range
  $('#date_from').on('dp.change', function (e) {
    if (e.date && $('#date_to').val()) {
      updateDateRangeFromIndividual();
    }
  });

  $('#date_to').on('dp.change', function (e) {
    if (e.date && $('#date_from').val()) {
      updateDateRangeFromIndividual();
    }
  });

  function updateDateRangeFromIndividual() {
    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    
    if (dateFrom && dateTo) {
      $('#date_range').val(dateFrom + ' - ' + dateTo);
      $('#date_range').data('daterangepicker').setStartDate(moment(dateFrom, 'DD/MM/YYYY'));
      $('#date_range').data('daterangepicker').setEndDate(moment(dateTo, 'DD/MM/YYYY'));
    }
  }

  // Sync date range with individual inputs
  $('#date_range').on('apply.daterangepicker', function(ev, picker) {
    $('#date_from').val(picker.startDate.format('DD/MM/YYYY'));
    $('#date_to').val(picker.endDate.format('DD/MM/YYYY'));
  });

  // Custom search function for status handling
  $.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
      // Only apply to our specific table for status filter
      if (settings.nTable.id !== 'bomTable') {
        return true;
      }

      var filterStatus = $('#filter_status').val();
      
      if (!filterStatus) {
        return true; // No status filter applied
      }

      // Get status from table (column index 5 - "Status")
      var rowStatus = data[5];
      
      if (!rowStatus) {
        return false;
      }

      // Remove HTML tags and get clean text
      var cleanStatus = $('<div>').html(rowStatus).text().trim();
      
      // Check various possible status formats
      var statusMatches = [
        filterStatus,
        filterStatus.toLowerCase(),
        filterStatus.toUpperCase(),
        filterStatus.replace('_', ' '),
        filterStatus.replace('PENDING_APPROVAL_1', 'Pending Approval 1'),
        filterStatus.replace('PENDING_APPROVAL_2', 'Pending Approval 2')
      ];

      // Check if any variation matches
      return statusMatches.some(function(status) {
        return cleanStatus.toLowerCase().includes(status.toLowerCase()) ||
               rowStatus.toLowerCase().includes(status.toLowerCase());
      });
    }
  );

  // Custom date filter function for DataTables
  $.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
      // Only apply to our specific table for date filter
      if (settings.nTable.id !== 'bomTable') {
        return true;
      }

      var dateFrom = $('#date_from').val();
      var dateTo = $('#date_to').val();
      
      if (!dateFrom && !dateTo) {
        return true; // No date filter applied
      }

      // Get date from table (column index 3 - "Tgl. Terbit")
      var rowDate = data[3]; // Format: dd/mm/yyyy
      
      if (!rowDate || rowDate === '-') {
        return false;
      }

      // Convert row date to moment object
      var rowMoment = moment(rowDate, 'DD/MM/YYYY');
      
      if (!rowMoment.isValid()) {
        return false;
      }

      var fromMoment = dateFrom ? moment(dateFrom, 'DD/MM/YYYY') : null;
      var toMoment = dateTo ? moment(dateTo, 'DD/MM/YYYY') : null;

      // Check date range
      if (fromMoment && toMoment) {
        return rowMoment.isBetween(fromMoment, toMoment, 'day', '[]');
      } else if (fromMoment) {
        return rowMoment.isSameOrAfter(fromMoment, 'day');
      } else if (toMoment) {
        return rowMoment.isSameOrBefore(toMoment, 'day');
      }

      return true;
    }
  );

  // Apply date filter
  $('#applyDateFilter').on('click', function() {
    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    
    if (!dateFrom && !dateTo) {
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: 'Silakan pilih rentang tanggal terlebih dahulu'
      });
      return;
    }

    // Validate date range
    if (dateFrom && dateTo) {
      var fromMoment = moment(dateFrom, 'DD/MM/YYYY');
      var toMoment = moment(dateTo, 'DD/MM/YYYY');
      
      if (fromMoment.isAfter(toMoment)) {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
        });
        return;
      }
    }

    // Apply the filter
    table.draw();
    
    // Visual feedback
    $('#date_from, #date_to, #date_range').addClass('date-filter-active');
    
    // Show applied filter info
    var filterText = '';
    if (dateFrom && dateTo) {
      filterText = `Filter tanggal: ${dateFrom} - ${dateTo}`;
    } else if (dateFrom) {
      filterText = `Filter tanggal dari: ${dateFrom}`;
    } else if (dateTo) {
      filterText = `Filter tanggal sampai: ${dateTo}`;
    }
    
    // Add filter indicator
    if (!$('.date-range-display').length) {
      $('.card-body').prepend(`<div class="alert alert-info date-range-display"><i class="fas fa-filter"></i> ${filterText}</div>`);
    } else {
      $('.date-range-display').html(`<i class="fas fa-filter"></i> ${filterText}`);
    }

    Swal.fire({
      icon: 'success',
      title: 'Filter Diterapkan!',
      text: filterText,
      timer: 2000,
      showConfirmButton: false
    });
  });

  // Clear all filters function
  $('#clearFilters').on('click', function() {
    // Clear search inputs
    $('#search_nomor').val('');
    $('#search_proyek').val('');
    $('#search_creator').val('');
    $('#filter_status').val('');
    
    // Clear date filters
    $('#date_from').val('');
    $('#date_to').val('');
    $('#date_range').val('');
    
    // Remove visual feedback
    $('#date_from, #date_to, #date_range').removeClass('date-filter-active');
    $('.date-range-display').remove();
    
    // Clear DataTable searches and filters
    table.search('').columns().search('').draw();
    
    Swal.fire({
      icon: 'success',
      title: 'Filter Dibersihkan!',
      text: 'Semua filter telah direset',
      timer: 1500,
      showConfirmButton: false
    });
  });

  // ===== END NEW DATE FILTER FUNCTIONALITY =====

  // Enhanced search functionality with better status handling
  $('#search_nomor').on('keyup', function() {
    table.column(1).search(this.value).draw();
  });

  $('#search_proyek').on('keyup', function() {
    table.column(2).search(this.value).draw();
  });

  $('#filter_status').on('change', function() {
    // Trigger the custom search function by redrawing the table
    table.draw();
  });

  $('#search_creator').on('keyup', function() {
    table.column(6).search(this.value).draw();
  });

  // Load statistics function
  function loadStatistics() {
    $.ajax({
      url: '{{ route("bom.statistics") }}',
      type: 'GET',
      success: function(response) {
        if (response.success) {
          const data = response.data;
          $('#stat-total').text(data.total_bom);
          $('#stat-draft').text(data.draft);
          $('#stat-pending1').text(data.pending_approval_1);
          $('#stat-pending2').text(data.pending_approval_2);
          $('#stat-approved').text(data.approved);
          $('#stat-rejected').text(data.rejected);
          $('#pending-count').text(data.pending_approval_1 + data.pending_approval_2);
        }
      },
      error: function() {
        console.log('Failed to load statistics');
      }
    });
  }

  // Refresh data
  $('#refreshData').on('click', function() {
    location.reload();
  });

  // Variables for actions
  var currentBomId = null;
  var currentAction = null;

  // FIXED: View BOM function - moved outside and properly separated
  function loadBomDetail(bomId) {
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
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Status</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_status"></p>
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
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label font-weight-bold">Dibuat Oleh</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext" id="view_created_by"></p>
                  </div>
                </div>
              </div>
            </div>
            <div id="approval_history" class="row mb-3" style="display: none;">
              <div class="col-12">
                <h5>History Approval</h5>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                      <tr>
                        <th>Level</th>
                        <th>Approver</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody id="approval_history_body">
                    </tbody>
                  </table>
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
                    <th width="5%">NO</th>
                    <th width="15%">KODE MATERIAL</th>
                    <th width="25%">DESKRIPSI</th>
                    <th width="10%">QTY</th>
                    <th width="10%">SATUAN</th>
                    <th width="15%">SPESIFIKASI</th>
                    <th width="20%">KETERANGAN</th>
                  </tr>
                </thead>
                <tbody id="view_items">
                </tbody>
              </table>
            </div>
          </div>
        `);

        function formatNomorBomDisplay(nomorBom) {
    if (!nomorBom) return '-';
    
    let nomorUrutInfo = '';
    let parts = nomorBom.split('/');
    
    if (parts.length >= 4) {
        // Format: 401/IMS/BRM-E12/2025 (nomor urut ada di kode unit)
        let kodeUnit = parts[0]; // 401, 402, 403, dst
        if (kodeUnit.length >= 3 && kodeUnit.startsWith('4')) {
            let nomorUrut = kodeUnit.substring(1); // Ambil "01" dari "401"
            nomorUrutInfo = `<br><small class="text-muted">Nomor Urut: ${nomorUrut}</small>`;
        }
    }
    
    return `<strong>${nomorBom}</strong>${nomorUrutInfo}`;

    $('#exportPdfBtn').attr('href', `/bom/${bomId}/export-pdf`);
    $('#previewPdfBtn').attr('href', `/bom/${bomId}/preview-pdf`);
}

        // Fill modal with data
        $('#view_nomor').html(formatNomorBomDisplay(response.nomor_bom));
        $('#view_proyek').html(response.proyek ? 
            `<strong>${response.proyek.nama_proyek}</strong><br><small class="text-muted">${response.proyek.kode_proyek}</small>` : '-');
        $('#view_tanggal').text(response.tanggal_formatted);
        $('#view_revisi').text(response.revisi ? response.revisi.jenis_revisi : '-');
        $('#view_kategori').text(response.kategori);
        $('#view_status').html(response.status_badge || response.status);
        $('#view_created_by').html(response.created_by ? 
            response.created_by.nama + '<br><small class="text-muted">' + (response.created_by.nip || '') + '</small>' : '-');


        // Show approval history if exists
        var historyHtml = '';
        var hasHistory = false;

        if (response.approvedBy1) {
          historyHtml += `
            <tr class="table-success">
              <td>Approval 1</td>
              <td>${response.approvedBy1.nama}<br><small>${response.approvedBy1.nip || ''}</small></td>
              <td>${new Date(response.approved_by_1_at).toLocaleDateString('id-ID')} ${new Date(response.approved_by_1_at).toLocaleTimeString('id-ID')}</td>
              <td>${response.approved_by_1_note || '-'}</td>
            </tr>
          `;
          hasHistory = true;
        }

        if (response.approvedBy2) {
          historyHtml += `
            <tr class="table-success">
              <td>Final Approval</td>
              <td>${response.approvedBy2.nama}<br><small>${response.approvedBy2.nip || ''}</small></td>
              <td>${new Date(response.approved_by_2_at).toLocaleDateString('id-ID')} ${new Date(response.approved_by_2_at).toLocaleTimeString('id-ID')}</td>
              <td>${response.approved_by_2_note || '-'}</td>
            </tr>
          `;
          hasHistory = true;
        }

        if (response.rejectedBy) {
          historyHtml += `
            <tr class="table-danger">
              <td>Rejected</td>
              <td>${response.rejectedBy.nama}<br><small>${response.rejectedBy.nip || ''}</small></td>
              <td>${new Date(response.rejected_at).toLocaleDateString('id-ID')} ${new Date(response.rejected_at).toLocaleTimeString('id-ID')}</td>
              <td>${response.rejected_note || '-'}</td>
            </tr>
          `;
          hasHistory = true;
        }

        if (hasHistory) {
          $('#approval_history_body').html(historyHtml);
          $('#approval_history').show();
        }

        // Fill items table - FIXED VERSION sesuai create blade
var itemsHtml = '';
if (response.item_bom && response.item_bom.length > 0) {
    response.item_bom.forEach(function(item, index) {
        // Perbaikan logika qty dan satuan sesuai dengan create blade
        let qty = 0;
        let satuan = '-';
        
        // Jika item memiliki qty tersimpan, gunakan itu
        if (item.qty !== null && item.qty !== undefined && item.qty !== 0) {
            qty = parseFloat(item.qty);
        } 
        // Jika tidak ada qty tersimpan tapi ada UOM, gunakan qty dari UOM
        else if (item.kode_material && item.kode_material.uom && item.kode_material.uom.qty) {
            qty = parseFloat(item.kode_material.uom.qty);
        }
        // Default fallback
        else {
            qty = 1;
        }
        
        // Untuk satuan, prioritas: satuan tersimpan di item > satuan dari UOM > default
        if (item.satuan && item.satuan.trim() !== '') {
            satuan = item.satuan;
        } else if (item.kode_material && item.kode_material.uom && item.kode_material.uom.satuan) {
            satuan = item.kode_material.uom.satuan;
        }
        
        itemsHtml += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${item.kode_material ? item.kode_material.kode_material : '-'}</strong></td>
                <td>${item.kode_material ? item.kode_material.nama_material : '-'}</td>
                <td class="text-right"><strong>${qty.toLocaleString('id-ID')}</strong></td>
                <td class="text-center"><span class="badge badge-light">${satuan}</span></td>
                <td><small>${item.kode_material && item.kode_material.spesifikasi ? item.kode_material.spesifikasi : '-'}</small></td>
                <td><small>${item.keterangan || '-'}</small></td>
            </tr>
        `;
    });
} else {
    itemsHtml = '<tr><td colspan="7" class="text-center text-muted"><em>Tidak ada item</em></td></tr>';
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
  }

  // View BOM - now properly separated
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

    // Load detail
    loadBomDetail(bomId);
  });

  // Submit BOM for approval
  $(document).on('click', '.submit-btn', function() {
    var bomId = $(this).data('bom-id');
    var bomNomor = $(this).closest('tr').find('td:eq(1) strong').text();
    
    Swal.fire({
      title: 'Konfirmasi Submit',
      html: `Submit BOM <strong>${bomNomor}</strong> untuk proses approval?<br><br>BOM akan masuk ke status <span class="badge badge-warning">PENDING APPROVAL 1</span>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Submit!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/bom/${bomId}/submit`,
          type: 'POST',
          data: {
            '_token': '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: response.message
              });
            }
          },
          error: function(xhr) {
            var message = 'Gagal submit BOM.';
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
  });

  // Approve Level 1
  $(document).on('click', '.approve1-btn', function() {
    currentBomId = $(this).data('bom-id');
    currentAction = 'approve1';
    var bomNomor = $(this).closest('tr').find('td:eq(1) strong').text();
    
    $('#approvalModalLabel').text('Approve BOM Level 1');
    $('#approvalButtonText').text('Approve Level 1');
    $('#approval_nomor').val(bomNomor);
    $('#approval_note').val('');
    $('#approvalModal').modal('show');
  });

  // Approve Level 2
  $(document).on('click', '.approve2-btn', function() {
    currentBomId = $(this).data('bom-id');
    currentAction = 'approve2';
    var bomNomor = $(this).closest('tr').find('td:eq(1) strong').text();
    
    $('#approvalModalLabel').text('Final Approve BOM');
    $('#approvalButtonText').text('Final Approve');
    $('#approval_nomor').val(bomNomor);
    $('#approval_note').val('');
    $('#approvalModal').modal('show');
  });

  // Confirm Approval
  $('#confirmApproval').on('click', function() {
    if (currentBomId && currentAction) {
      var note = $('#approval_note').val();
      var url = currentAction === 'approve1' ? `/bom/${currentBomId}/approve-1` : `/bom/${currentBomId}/approve-2`;
      var level = currentAction === 'approve1' ? '1' : '2';
      
      // Show confirmation
      Swal.fire({
        title: 'Konfirmasi Approval',
        text: `Approve BOM level ${level}? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Ya, Approve Level ${level}!`,
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Disable button to prevent double click
          $('#confirmApproval').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
          
          $.ajax({
            url: url,
            type: 'POST',
            data: {
              '_token': '{{ csrf_token() }}',
              'note': note
            },
            success: function(response) {
              $('#approvalModal').modal('hide');
              
              if (response.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: response.message,
                  timer: 3000,
                  showConfirmButton: false
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal!',
                  text: response.message
                });
              }
            },
            error: function(xhr) {
              $('#approvalModal').modal('hide');
              var message = 'Gagal approve BOM.';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
              });
            },
            complete: function() {
              // Re-enable button
              $('#confirmApproval').prop('disabled', false).html('<i class="fas fa-check"></i> <span id="approvalButtonText">Approve</span>');
            }
          });
        }
      });
    }
  });

  // Reject BOM
  $(document).on('click', '.reject-btn', function() {
    currentBomId = $(this).data('bom-id');
    var bomNomor = $(this).closest('tr').find('td:eq(1) strong').text();
    
    $('#reject_nomor').val(bomNomor);
    $('#reject_note').val('');
    $('#rejectModal').modal('show');
  });

  // Confirm Reject
  $('#confirmReject').on('click', function() {
    var note = $('#reject_note').val().trim();
    
    if (!note) {
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: 'Alasan penolakan harus diisi'
      });
      $('#reject_note').focus();
      return;
    }

    if (currentBomId) {
      // Show confirmation
      Swal.fire({
        title: 'Konfirmasi Penolakan',
        text: 'Reject BOM ini? BOM akan dikembalikan ke pembuat untuk diperbaiki.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reject!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Disable button to prevent double click
          $('#confirmReject').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
          
          $.ajax({
            url: `/bom/${currentBomId}/reject`,
            type: 'POST',
            data: {
              '_token': '{{ csrf_token() }}',
              'note': note
            },
            success: function(response) {
              $('#rejectModal').modal('hide');
              
              if (response.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'BOM Berhasil Di-reject!',
                  text: 'BOM telah dikembalikan ke pembuat untuk diperbaiki.',
                  timer: 3000,
                  showConfirmButton: false
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal!',
                  text: response.message
                });
              }
            },
            error: function(xhr) {
              $('#rejectModal').modal('hide');
              var message = 'Gagal reject BOM.';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
              });
            },
            complete: function() {
              // Re-enable button
              $('#confirmReject').prop('disabled', false).html('<i class="fas fa-times"></i> Reject BOM');
            }
          });
        }
      });
    }
  });

  // Delete BOM
  var deleteId = null;
  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('bom-id');
    var bomNomor = $(this).data('bom-nomor');
    var $row = $(this).closest('tr');
    var proyekNama = $row.find('td:eq(2) strong').text();
    
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

  // Clear variables when modals are closed
  $('#approvalModal, #rejectModal').on('hidden.bs.modal', function() {
    currentBomId = null;
    currentAction = null;
  });

  $('#deleteModal').on('hidden.bs.modal', function() {
    deleteId = null;
  });
});
</script>

@if(session('success'))
<script>
$(document).ready(function() {
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
  });
});
</script>
@endif

@if(session('error'))
<script>
$(document).ready(function() {
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '{{ session('error') }}',
    showConfirmButton: true
  });
});
</script>
@endif
@endpush