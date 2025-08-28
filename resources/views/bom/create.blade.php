@extends('layouts.app')

@section('title', 'Buat BOM Baru')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Buat BOM Baru</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('bom.index') }}">BOM</a></li>
      <li class="breadcrumb-item active">Buat Baru</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12 col-lg-8">
    <!-- Info Alert -->
    <div class="alert alert-info">
      <i class="fas fa-info-circle"></i>
      <strong>Informasi:</strong> BOM yang dibuat akan memiliki status <strong>DRAFT</strong>. Nomor BOM akan di-generate otomatis berdasarkan proyek dan jenis dokumen yang dipilih.
    </div>

    <!-- Form Tambah -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Form Bill of Materials</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <form id="bomForm" action="{{ route('bom.store') }}" method="POST">
          @csrf
          
          <!-- Header Information -->
          <div class="row mb-3">
            <div class="col-12">
              <h5 class="text-primary">Informasi Header BOM</h5>
              <hr>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label for="proyek_id">Proyek <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('proyek_id') is-invalid @enderror" 
                      id="proyek_id" name="proyek_id" required style="width: 100%;">
                <option value="">Pilih Proyek</option>
                @if(isset($proyeks) && $proyeks->count() > 0)
                  @foreach($proyeks as $proyek)
                    <option value="{{ $proyek->id }}" {{ old('proyek_id') == $proyek->id ? 'selected' : '' }}>
                      {{ $proyek->kode_proyek }} - {{ $proyek->nama_proyek }}
                    </option>
                  @endforeach
                @else
                  <option value="" disabled>Data proyek tidak ditemukan</option>
                @endif
              </select>
              @error('proyek_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              @if(!isset($proyeks) || $proyeks->count() == 0)
                <small class="text-danger">Peringatan: Data proyek belum tersedia. Hubungi administrator.</small>
              @endif
            </div>
            
            <div class="form-group col-12 col-md-6">
              <label for="jenis_dokumen_id">Jenis Dokumen <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('jenis_dokumen_id') is-invalid @enderror" 
                      id="jenis_dokumen_id" name="jenis_dokumen_id" required style="width: 100%;">
                <option value="">Pilih Jenis Dokumen</option>
                @if(isset($jenisDokumens) && $jenisDokumens->count() > 0)
                  @foreach($jenisDokumens as $jenisDokumen)
                    <option value="{{ $jenisDokumen->id }}" {{ old('jenis_dokumen_id') == $jenisDokumen->id ? 'selected' : '' }}>
                      {{ $jenisDokumen->kode_dokumen }} - {{ $jenisDokumen->nama_dokumen }}
                    </option>
                  @endforeach
                @else
                  <option value="" disabled>Data jenis dokumen tidak ditemukan</option>
                @endif
              </select>
              @error('jenis_dokumen_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              @if(!isset($jenisDokumens) || $jenisDokumens->count() == 0)
                <small class="text-danger">Peringatan: Data jenis dokumen belum tersedia. Hubungi administrator.</small>
              @endif
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label for="nomor_bom">Nomor BOM <span class="text-danger">*</span></label>
              <input type="text" class="form-control bg-light @error('nomor_bom') is-invalid @enderror" 
                     id="nomor_bom" name="nomor_bom" placeholder="Pilih proyek dan jenis dokumen untuk generate nomor" 
                     value="Auto Generate" 
                     readonly>
              @error('nomor_bom')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group col-12 col-md-6">
              <label for="kategori">Kategori <span class="text-danger">*</span></label>
              <select class="form-control @error('kategori') is-invalid @enderror" 
                      id="kategori" name="kategori" required>
                <option value="">Pilih Kategori</option>
                @foreach(\App\Models\BillOfMaterial::getAvailableCategories() as $key => $label)
                  <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              @error('kategori')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-12 col-md-4">
              <label for="revisi_id">Revisi <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('revisi_id') is-invalid @enderror" 
                      id="revisi_id" name="revisi_id" required style="width: 100%;">
                <option value="">Pilih Revisi</option>
                @foreach($revisis as $revisi)
                  <option value="{{ $revisi->id }}" {{ old('revisi_id') == $revisi->id ? 'selected' : '' }}>
                    {{ $revisi->nama_revisi }}
                  </option>
                @endforeach
              </select>
              @error('revisi_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
              <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                     id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
              @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-4">
              <label>Dibuat Oleh</label>
              <input type="text" class="form-control bg-light" 
                     value="{{ Auth::user()->nama ?? Auth::user()->name }}" readonly>
              <small class="form-text text-muted">Pembuat BOM otomatis tercatat</small>
            </div>
          </div>
          
          <!-- Preview Nomor BOM -->
          <div class="row">
            <div class="col-12">
              <div class="alert alert-light border" id="nomorPreview" style="display: none;">
                <h6 class="text-primary mb-2">
                  <i class="fas fa-eye"></i> Preview Nomor BOM:
                </h6>
                <div class="row">
                  <div class="col-12 col-md-8">
                    <h4 class="mb-0" id="previewNomor" style="font-family: monospace; color: #28a745;"></h4>
                  </div>
                  <div class="col-12 col-md-4 text-md-right">
                    <small class="text-muted">Format: 4XX/IMS/[KODE_DOKUMEN]-[KODE_PROYEK]/[TAHUN]</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Items Section -->
          <div class="row mt-4">
            <div class="col-12">
              <h5 class="text-primary">Item BOM</h5>
              <hr>
            </div>
          </div>
          
          <!-- Desktop Table View -->
          <div class="d-none d-md-block">
            <div class="table-responsive">
              <table class="table table-bordered table-sm" id="itemTableDesktop">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 25%;">Kode Material <span class="text-danger">*</span></th>
                    <th style="width: 20%;">Deskripsi</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 10%;">Satuan</th>
                    <th style="width: 20%;">Spesifikasi</th>
                    <th style="width: 15%;">Keterangan</th>
                    <th style="width: 5%;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dynamic rows will be added here -->
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Mobile Card View -->
          <div class="d-block d-md-none" id="itemContainerMobile">
            <!-- Dynamic cards will be added here -->
          </div>
          
          <div class="row">
            <div class="col-12">
              <button type="button" id="addItem" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Tambah Item
              </button>
              <small class="form-text text-muted">Minimal harus ada 1 item BOM</small>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="row mt-4">
            <div class="col-12">
              <hr>
              <h5 class="text-primary">Pilihan Aksi</h5>
              <div class="alert alert-light">
                <div class="row">
                  <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <strong>Simpan sebagai Draft:</strong>
                    <p class="mb-0 text-muted">BOM disimpan dengan status DRAFT, dapat diedit kembali</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <strong>Simpan & Submit:</strong>
                    <p class="mb-0 text-muted">BOM disimpan dan langsung disubmit untuk approval level 1</p>
                  </div>
                </div>
              </div>
              
              <!-- Button Groups for Mobile and Desktop -->
              <div class="d-block d-sm-none mb-2">
                <!-- Mobile: Stacked buttons -->
                <button type="submit" class="btn btn-primary btn-block mb-2" name="action" value="draft">
                  <i class="fas fa-save"></i> Simpan sebagai Draft
                </button>
                <button type="submit" class="btn btn-success btn-block mb-2" name="action" value="submit">
                  <i class="fas fa-paper-plane"></i> Simpan & Submit untuk Approval
                </button>
                <button type="reset" class="btn btn-secondary btn-block mb-2">
                  <i class="fas fa-undo"></i> Reset Form
                </button>
                <a href="{{ route('bom.index') }}" class="btn btn-default btn-block">
                  <i class="fas fa-arrow-left"></i> Kembali ke Daftar BOM
                </a>
              </div>
              
              <div class="d-none d-sm-block">
                <!-- Desktop: Grouped buttons -->
                <div class="btn-group" role="group">
                  <button type="submit" class="btn btn-primary" name="action" value="draft">
                    <i class="fas fa-save"></i> Simpan sebagai Draft
                  </button>
                  <button type="submit" class="btn btn-success" name="action" value="submit">
                    <i class="fas fa-paper-plane"></i> Simpan & Submit untuk Approval
                  </button>
                </div>
                <div class="btn-group ml-2" role="group">
                  <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset Form
                  </button>
                  <a href="{{ route('bom.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar BOM
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Right Sidebar - Help Section -->
  <div class="col-12 col-lg-4">
    <div class="card card-outline card-info">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-question-circle"></i> Informasi Generate Nomor BOM
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6><strong>Format Nomor BOM:</strong></h6>
          <p class="text-monospace bg-light p-2 rounded">4XX/IMS/[KODE_DOKUMEN]-[KODE_PROYEK]/[TAHUN]</p>
        </div>
        
        <div class="mb-3">
          <h6><strong>Penjelasan Format:</strong></h6>
          <ul class="list-unstyled">
            <li><strong>4XX:</strong> Kode Unit (otomatis berurut berdasarkan jenis dokumen)</li>
            <li><strong>IMS:</strong> Nama Perusahaan (otomatis)</li>
            <li><strong>KODE_DOKUMEN:</strong> Kode dari jenis dokumen yang dipilih</li>
            <li><strong>KODE_PROYEK:</strong> Kode dari proyek yang dipilih</li>
            <li><strong>TAHUN:</strong> Tahun pembuatan BOM</li>
          </ul>
        </div>
        
        <div class="mb-3">
          <h6><strong>Contoh Nomor BOM:</strong></h6>
          <ul class="list-unstyled">
            <li><code class="bg-light p-1">401/IMS/BRM-E12/2025</code></li>
            <li><code class="bg-light p-1">402/IMS/BRM-E13/2025</code></li>
            <li><code class="bg-light p-1">403/IMS/SPE-E12/2025</code></li>
          </ul>
        </div>
        
        <div class="alert alert-warning">
          <small><i class="fas fa-exclamation-triangle"></i> <strong>Penting:</strong> 
          Nomor urut (XX pada 4XX) akan bertambah otomatis berdasarkan jenis dokumen yang sama, 
          bukan berdasarkan proyek. Pilih proyek dan jenis dokumen untuk melihat preview nomor yang akan di-generate.</small>
        </div>
        
        <div class="alert alert-info">
          <small><i class="fas fa-info-circle"></i> <strong>Catatan:</strong> 
          Sistem akan mencari nomor urut terakhir dari jenis dokumen yang sama untuk menentukan nomor berikutnya.</small>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<!-- Custom CSS -->
<style>
/* Fix untuk Select2 dropdown z-index */
.main-header .navbar {
  z-index: 1030 !important;
}

.main-sidebar {
  z-index: 1020 !important;
}

.select2-container {
  z-index: 1000 !important;
}

.select2-dropdown {
  z-index: 1010 !important;
}

.select2-container--bootstrap4 .select2-dropdown {
  z-index: 1010 !important;
  border: 1px solid #ced4da !important;
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
}

#itemTableDesktop .select2-container {
  z-index: 1005 !important;
}

#itemTableDesktop .select2-dropdown {
  z-index: 1015 !important;
}

.select2-container--open {
  z-index: 1005 !important;
}

.select2-container--open .select2-dropdown {
  z-index: 1015 !important;
}

.card {
  position: relative;
  z-index: 1;
}

.card-body {
  position: relative;
  z-index: 2;
}

.content-wrapper {
  z-index: 1;
}

.select2-container .select2-selection {
  position: relative;
  z-index: 1;
}

.select2-container--bootstrap4.select2-container--open .select2-selection {
  z-index: 1005 !important;
}

.table-responsive {
  position: relative;
  z-index: 1;
}

.table-responsive .select2-container {
  z-index: 1005 !important;
}

.table-responsive .select2-dropdown {
  z-index: 1015 !important;
}

.navbar-nav .nav-item .nav-link,
.navbar-brand,
.navbar-toggler {
  position: relative;
  z-index: 1040 !important;
}

/* Mobile Sidebar Fix */
@media (max-width: 991.98px) {
  .main-sidebar {
    z-index: 1050 !important;
  }
  
  .main-sidebar .sidebar {
    overflow-y: auto;
    overflow-x: hidden;
  }
  
  /* Pastikan sidebar overlay bisa diklik */
  .sidebar-mini.sidebar-collapse .main-sidebar:hover {
    width: 250px !important;
  }
  
  /* Fix untuk mobile menu toggle */
  [data-widget="pushmenu"] {
    pointer-events: auto !important;
    z-index: 1060 !important;
  }
  
  /* Pastikan backdrop sidebar bisa diklik untuk menutup */
  .sidebar-open .content-wrapper::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.1);
    z-index: 1040;
    pointer-events: auto;
  }
}

/* SweetAlert2 z-index */
.swal2-container.swal2-backdrop-fix {
  z-index: 2000 !important;
}

.swal2-container.swal2-backdrop-fix .swal2-backdrop {
  background-color: rgba(0, 0, 0, 0.4) !important;
  pointer-events: all !important;
  z-index: 2000 !important;
}

.swal2-popup {
  z-index: 2010 !important;
}

.swal2-shown .select2-container,
.swal2-shown .form-control,
.swal2-shown .btn {
  pointer-events: none !important;
}

.swal2-shown .swal2-container * {
  pointer-events: auto !important;
}

.swal2-shown .form-control:focus,
.swal2-shown .select2-container--bootstrap4 .select2-selection--single:focus {
  box-shadow: none !important;
  border-color: #ced4da !important;
}

.swal2-backdrop-show {
  background-color: rgba(0, 0, 0, 0.4) !important;
}

/* Loading style untuk preview nomor */
#nomorPreview.loading {
  opacity: 0.7;
}

#previewNomor.loading::after {
  content: "Generating...";
  animation: dots 1.5s steps(5, end) infinite;
}

@keyframes dots {
  0%, 20% { content: "Generating"; }
  40% { content: "Generating."; }
  60% { content: "Generating.."; }
  80% { content: "Generating..."; }
}

/* Mobile Card Styling */
.item-card-mobile {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  padding: 1rem;
  margin-bottom: 1rem;
  background-color: #fff;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.item-card-mobile .card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #dee2e6;
}

.item-card-mobile .form-group {
  margin-bottom: 0.75rem;
}

.item-card-mobile .form-group:last-child {
  margin-bottom: 0;
}

.item-card-mobile label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
}

.item-card-mobile .form-control,
.item-card-mobile .select2-container {
  font-size: 0.875rem;
}

.item-card-mobile .btn-danger {
  padding: 0.375rem 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
  .table-responsive {
    font-size: 0.875rem;
  }
  
  .card-body {
    padding: 1rem 0.75rem;
  }
  
  .item-card-mobile {
    padding: 0.75rem;
    margin-bottom: 0.75rem;
  }
  
  .item-card-mobile .form-control,
  .item-card-mobile .select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.8125rem + 2px);
    font-size: 0.875rem;
  }
  
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }
  
  .form-control {
    font-size: 0.875rem;
  }
  
  .select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.8125rem + 2px);
    font-size: 0.875rem;
  }
}

@media (max-width: 768px) {
  .card-body {
    padding: 1rem 0.75rem;
  }
}

/* Desktop Table Styling - Full width columns */
#itemTableDesktop th,
#itemTableDesktop td {
  vertical-align: middle;
  padding: 0.5rem 0.25rem;
}

#itemTableDesktop .form-control,
#itemTableDesktop .select2-container {
  width: 100% !important;
  min-width: 0;
}

#itemTableDesktop .select2-selection__rendered {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Kolom yang dapat scroll horizontal jika diperlukan */
#itemTableDesktop tbody tr td:nth-child(2),
#itemTableDesktop tbody tr td:nth-child(5) {
  max-width: 200px;
  word-wrap: break-word;
  white-space: normal;
}

#itemTableDesktop tbody tr td:nth-child(6) {
  max-width: 150px;
  word-wrap: break-word;
  white-space: normal;
}
</style>
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
  let rowCounter = 0;
  
  // Initialize main selects
  const initSelect2 = (selector, placeholder) => {
    $(selector).select2({
      theme: 'bootstrap4',
      placeholder,
      allowClear: true,
      width: '100%',
      dropdownAutoWidth: true
    });
  };

  initSelect2('#proyek_id', 'Pilih Proyek');
  initSelect2('#revisi_id', 'Pilih Revisi');
  initSelect2('#jenis_dokumen_id', 'Pilih Jenis Dokumen');

  // Add first item on load
  addNewItem();
  
  // Handler untuk generate nomor BOM ketika proyek dan jenis dokumen dipilih
  function generateNomorBom() {
    const proyekId = $('#proyek_id').val();
    const jenisDokumenId = $('#jenis_dokumen_id').val();
    
    if (proyekId && jenisDokumenId) {
      // Show loading
      $('#nomorPreview').show().addClass('loading');
      $('#previewNomor').addClass('loading').text('');
      
      $.ajax({
        url: '{{ route("bom.generate-nomor") }}',
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          proyek_id: proyekId,
          jenis_dokumen_id: jenisDokumenId
        },
        success: function(response) {
          if (response.success) {
            $('#nomor_bom').val(response.nomor_bom);
            $('#previewNomor').removeClass('loading').text(response.nomor_bom);
            $('#nomorPreview').removeClass('loading');
          } else {
            showAlert('error', 'Error!', response.message || 'Gagal generate nomor BOM');
            hideNomorPreview();
          }
        },
        error: function(xhr) {
          let message = 'Gagal generate nomor BOM';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          }
          showAlert('error', 'Error!', message);
          hideNomorPreview();
        }
      });
    } else {
      hideNomorPreview();
    }
  }
  
  function hideNomorPreview() {
    $('#nomorPreview').hide().removeClass('loading');
    $('#previewNomor').removeClass('loading').text('');
    $('#nomor_bom').val('Auto Generate');
  }
  
  // Event handlers untuk generate nomor
  $('#proyek_id, #jenis_dokumen_id').on('change', function() {
    generateNomorBom();
  });
  
  // Material change handler
  $(document).on('change', '.material-select', function() {
    const $option = $(this).find('option:selected');
    const rowIndex = $(this).data('row');
    
    if ($option.val()) {
      const data = {
        desc: $option.data('desc') || '',
        spec: $option.data('spec') || '',
        uom: $option.data('uom') || '',
        qty: $option.data('qty') || 1
      };
      
      // Update desktop table
      const $desktopRow = $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`);
      if ($desktopRow.length) {
        $desktopRow.find('.desc-field').val(data.desc);
        $desktopRow.find('.spec-field').val(data.spec);
        $desktopRow.find('.uom-field').val(data.uom);
        $desktopRow.find('.qty-input').val(data.qty);
      }
      
      // Update mobile card
      const $mobileCard = $(`.item-card-mobile[data-row="${rowIndex}"]`);
      if ($mobileCard.length) {
        $mobileCard.find('.desc-field').val(data.desc);
        $mobileCard.find('.spec-field').val(data.spec);
        $mobileCard.find('.uom-field').val(data.uom);
        $mobileCard.find('.qty-input').val(data.qty);
      }
      
      checkDuplicateMaterials();
    } else {
      // Clear fields for both desktop and mobile
      const $desktopRow = $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`);
      const $mobileCard = $(`.item-card-mobile[data-row="${rowIndex}"]`);
      
      $desktopRow.find('.desc-field, .spec-field, .uom-field, .qty-input').val('');
      $mobileCard.find('.desc-field, .spec-field, .uom-field, .qty-input').val('');
    }
  });

  // Optimized duplicate check
  function checkDuplicateMaterials() {
    const materials = [];
    let isValid = true;
    
    $('.material-select').each(function() {
      const $this = $(this);
      const value = $this.val();
      
      $this.removeClass('is-invalid').next('.invalid-feedback').remove();
      
      if (value && materials.includes(value)) {
        $this.addClass('is-invalid');
        $('<div class="invalid-feedback">Material sudah dipilih sebelumnya</div>').insertAfter($this);
        isValid = false;
      } else if (value) {
        materials.push(value);
      }
    });
    
    return isValid;
  }

  // Add new item with both desktop and mobile templates
  function addNewItem() {
    // Desktop table row template
    const desktopTemplate = `
      <tr data-row="${rowCounter}">
        <td>
          <select class="form-control material-select select2" name="items[${rowCounter}][material_id]" data-row="${rowCounter}" required style="width: 100%;">
            <option value="">Pilih Kode Material</option>
            @foreach($materials as $material)
              <option value="{{ $material->id }}" 
                      data-desc="{{ $material->nama_material }}" 
                      data-spec="{{ $material->spesifikasi }}"
                      data-uom="{{ $material->uom ? $material->uom->satuan : '' }}" 
                      data-qty="{{ $material->uom ? $material->uom->qty : 1 }}">
                {{ $material->kode_material }} - {{ $material->nama_material }}
              </option>
            @endforeach
          </select>
        </td>
        <td>
          <input type="text" class="form-control desc-field" name="items[${rowCounter}][deskripsi]" readonly>
        </td>
        <td>
          <input type="number" class="form-control qty-input" name="items[${rowCounter}][qty]" min="1" step="0.01" required readonly>
        </td>
        <td>
          <input type="text" class="form-control uom-field" name="items[${rowCounter}][satuan]" readonly>
        </td>
        <td>
          <input type="text" class="form-control spec-field" name="items[${rowCounter}][spesifikasi]" readonly>
        </td>
        <td>
          <input type="text" class="form-control" name="items[${rowCounter}][keterangan]" placeholder="Keterangan">
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
    
    // Mobile card template
    const mobileTemplate = `
      <div class="item-card-mobile" data-row="${rowCounter}">
        <div class="card-header">
          <strong>Item #${rowCounter + 1}</strong>
          <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        
        <div class="form-group">
          <label>Kode Material <span class="text-danger">*</span></label>
          <select class="form-control material-select select2" name="items[${rowCounter}][material_id]" data-row="${rowCounter}" required style="width: 100%;">
            <option value="">Pilih Kode Material</option>
            @foreach($materials as $material)
              <option value="{{ $material->id }}" 
                      data-desc="{{ $material->nama_material }}" 
                      data-spec="{{ $material->spesifikasi }}"
                      data-uom="{{ $material->uom ? $material->uom->satuan : '' }}" 
                      data-qty="{{ $material->uom ? $material->uom->qty : 1 }}">
                {{ $material->kode_material }} - {{ $material->nama_material }}
              </option>
            @endforeach
          </select>
        </div>
        
        <div class="form-group">
          <label>Deskripsi</label>
          <input type="text" class="form-control desc-field" name="items[${rowCounter}][deskripsi]" readonly>
        </div>
        
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label>Qty <span class="text-danger">*</span></label>
              <input type="number" class="form-control qty-input" name="items[${rowCounter}][qty]" min="1" step="0.01" required readonly>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label>Satuan</label>
              <input type="text" class="form-control uom-field" name="items[${rowCounter}][satuan]" readonly>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label>Spesifikasi</label>
          <textarea class="form-control spec-field" name="items[${rowCounter}][spesifikasi]" rows="2" readonly style="resize: none; overflow-y: auto;"></textarea>
        </div>
        
        <div class="form-group">
          <label>Keterangan</label>
          <input type="text" class="form-control" name="items[${rowCounter}][keterangan]" placeholder="Keterangan (opsional)">
        </div>
      </div>
    `;
    
    // Add to desktop table
    const $newDesktopRow = $(desktopTemplate).appendTo('#itemTableDesktop tbody');
    initSelect2($newDesktopRow.find('.material-select'), 'Pilih Kode Material');
    
    // Add to mobile container
    const $newMobileCard = $(mobileTemplate).appendTo('#itemContainerMobile');
    initSelect2($newMobileCard.find('.material-select'), 'Pilih Kode Material');
    
    rowCounter++;
  }

  $('#addItem').click(addNewItem);
  
  // Remove item with validation - FUNGSI MINIMAL 1 ITEM
  $(document).on('click', '.remove-item', function() {
    const $container = $(this).closest('[data-row]');
    const rowIndex = $container.data('row');
    const totalItems = $('.item-card-mobile, #itemTableDesktop tbody tr').length;
    
    if (totalItems > 1) {
      // Remove from both desktop and mobile
      $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`).find('.material-select').select2('destroy');
      $(`.item-card-mobile[data-row="${rowIndex}"]`).find('.material-select').select2('destroy');
      
      $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`).remove();
      $(`.item-card-mobile[data-row="${rowIndex}"]`).remove();
      
      checkDuplicateMaterials();
      updateMobileItemNumbers();
    } else {
      // TAMPILKAN ALERT JIKA MENCOBA MENGHAPUS ITEM TERAKHIR
      showAlert('warning', 'Tidak dapat menghapus!', 'Minimal harus ada 1 item BOM. Item ini tidak dapat dihapus.');
    }
  });

  // Update mobile item numbers after removal
  function updateMobileItemNumbers() {
    $('.item-card-mobile').each(function(index) {
      $(this).find('.card-header strong').text(`Item #${index + 1}`);
    });
  }
  
  // Form validation
  function validateForm() {
    // Check if nomor BOM sudah di-generate
    const nomorBom = $('#nomor_bom').val();
    if (!nomorBom || nomorBom === 'Auto Generate') {
      return 'Silakan pilih proyek dan jenis dokumen untuk generate nomor BOM';
    }
    
    const hasItems = $('.item-card-mobile, #itemTableDesktop tbody tr').length > 0;
    if (!hasItems) return 'Minimal harus ada 1 item!';
    
    let hasValidItems = false;
    $('.material-select').each(function() {
      const $container = $(this).closest('[data-row]');
      const materialId = $(this).val();
      const qty = $container.find('.qty-input').val();
      
      if (materialId && qty && parseFloat(qty) > 0) {
        hasValidItems = true;
        return false; // Break loop
      }
    });
    
    if (!hasValidItems) return 'Minimal harus ada 1 item dengan material dan quantity yang valid';
    if (!checkDuplicateMaterials()) return 'Terdapat material yang sama dipilih lebih dari sekali!';
    
    return null;
  }

  // Form submission handler
  $('#bomForm').on('submit', function(e) {
    const error = validateForm();
    if (error) {
      e.preventDefault();
      showAlert('warning', 'Peringatan!', error);
      return false;
    }
  });

  // Submit button handler
  $('button[name="action"][value="submit"]').on('click', function(e) {
    e.preventDefault();
    
    const error = validateForm();
    if (error) {
      showAlert('warning', 'Peringatan!', error);
      return;
    }
    
    Swal.fire({
      title: 'Konfirmasi Submit',
      text: 'BOM akan disimpan dan langsung disubmit untuk approval level 1. Lanjutkan?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Submit!',
      cancelButtonText: 'Batal',
      allowOutsideClick: false,
      allowEscapeKey: false,
      backdrop: true,
      heightAuto: false,
      customClass: {
        container: 'swal2-backdrop-fix'
      }
    }).then(result => {
      if (result.isConfirmed) {
        $('<input>').attr({
          type: 'hidden',
          name: 'submit_for_approval',
          value: 'true'
        }).appendTo('#bomForm');
        
        $('#bomForm')[0].submit();
      }
    });
  });

  // Reset form handler
  $('button[type="reset"]').on('click', function(e) {
    e.preventDefault();
    
    Swal.fire({
      title: 'Konfirmasi Reset',
      text: 'Semua data yang telah diisi akan dihapus. Lanjutkan?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#6c757d',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Reset!',
      cancelButtonText: 'Batal',
      allowOutsideClick: false,
      allowEscapeKey: false,
      backdrop: true,
      heightAuto: false,
      customClass: {
        container: 'swal2-backdrop-fix'
      }
    }).then(result => {
      if (result.isConfirmed) {
        resetForm();
      }
    });
  });

  // Reset form function
  function resetForm() {
    // Destroy all select2 instances first
    $('.material-select').select2('destroy');
    
    $('#proyek_id, #revisi_id, #jenis_dokumen_id').val(null).trigger('change');
    $('#itemTableDesktop tbody').empty();
    $('#itemContainerMobile').empty();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    $('#bomForm')[0].reset();
    $('#tanggal').val('{{ date('Y-m-d') }}');
    hideNomorPreview();
    
    rowCounter = 0;
    addNewItem();
    
    showAlert('success', 'Form telah direset!', null, 1500);
  }

  // Optimized alert function
  function showAlert(type, title, text = null, timer = null) {
    const config = {
      icon: type,
      title: title,
      showConfirmButton: !timer,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      backdrop: true,
      heightAuto: false,
      customClass: {
        container: 'swal2-backdrop-fix'
      }
    };
    
    if (text) config.text = text;
    if (timer) config.timer = timer;
    
    return Swal.fire(config);
  }

  // Clear validation on input change
  $(document).on('change', 'input, select', function() {
    $(this).removeClass('is-invalid').next('.invalid-feedback').remove();
  });
});

// Session message handlers
@if(session('success'))
$(document).ready(() => {
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 5000,
    showConfirmButton: true,
    allowOutsideClick: false,
    backdrop: true,
    heightAuto: false,
    customClass: {
      container: 'swal2-backdrop-fix'
    }
  });
});
@endif

@if(session('error'))
$(document).ready(() => {
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '{{ session('error') }}',
    showConfirmButton: true,
    allowOutsideClick: false,
    backdrop: true,
    heightAuto: false,
    customClass: {
      container: 'swal2-backdrop-fix'
    }
  });
});
@endif
</script>
@endpush