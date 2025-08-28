@extends('layouts.app')

@section('title', 'Edit BOM')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Edit BOM</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('bom.index') }}">BOM</a></li>
      <li class="breadcrumb-item active">Edit</li>
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
      <strong>Informasi:</strong> BOM dengan status <strong>{{ $billOfMaterial->status }}</strong>. Setelah update, Anda dapat memilih untuk menyimpan sebagai draft atau langsung submit untuk proses approval.
    </div>

    <!-- Form Edit -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Form Edit Bill of Materials</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <form id="bomForm" action="{{ route('bom.update', $billOfMaterial->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <!-- Header Information -->
          <div class="row mb-3">
            <div class="col-12">
              <h5 class="text-primary">Informasi Header BOM</h5>
              <hr>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-12 col-md-3">
              <label for="nomor_bom">Nomor BOM <span class="text-danger">*</span></label>
              <input type="text" class="form-control bg-light @error('nomor_bom') is-invalid @enderror" 
                     id="nomor_bom" name="nomor_bom" placeholder="Nomor BOM" 
                     value="{{ old('nomor_bom', $billOfMaterial->nomor_bom) }}" 
                     readonly>
              @error('nomor_bom')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">Nomor BOM tidak dapat diubah</small>
            </div>
            
            <div class="form-group col-12 col-md-3">
              <label for="kategori">Kategori <span class="text-danger">*</span></label>
              <select class="form-control @error('kategori') is-invalid @enderror" 
                      id="kategori" name="kategori" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $key => $label)
                  <option value="{{ $key }}" {{ old('kategori', $billOfMaterial->kategori) == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              @error('kategori')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-12 col-md-3">
              <label for="proyek_id">Proyek <span class="text-danger">*</span></label>
              <input type="hidden" name="proyek_id" value="{{ $billOfMaterial->proyek_id }}">
              <input type="text" class="form-control bg-light" 
                     value="{{ $billOfMaterial->proyek->display_name ?? 'Proyek tidak ditemukan' }}" 
                     readonly>
              <small class="form-text text-muted">Proyek tidak dapat diubah</small>
            </div>

            <div class="form-group col-12 col-md-3">
              <label for="revisi_id">Revisi <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('revisi_id') is-invalid @enderror" 
                      id="revisi_id" name="revisi_id" required style="width: 100%;">
                <option value="">Pilih Revisi</option>
                @foreach($revisis as $revisi)
                  <option value="{{ $revisi->id }}" {{ old('revisi_id', $billOfMaterial->revisi_id) == $revisi->id ? 'selected' : '' }}>
                    {{ $revisi->nama_revisi }}
                  </option>
                @endforeach
              </select>
              @error('revisi_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-12 col-md-4">
              <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
              <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                     id="tanggal" name="tanggal" value="{{ old('tanggal', $billOfMaterial->tanggal->format('Y-m-d')) }}" required>
              @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-4">
              <label>Dibuat Oleh</label>
              <input type="text" class="form-control bg-light" 
                     value="{{ $billOfMaterial->createdBy->nama ?? $billOfMaterial->createdBy->name }}" readonly>
              <small class="form-text text-muted">Pembuat BOM</small>
            </div>
            <div class="form-group col-12 col-md-4">
              <label>Status Saat Ini</label>
              <input type="text" class="form-control bg-light" value="{{ $billOfMaterial->status }}" readonly>
              <small class="form-text text-muted">Status akan berubah sesuai pilihan aksi</small>
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
                    <th style="width: 20%;">Kode Material <span class="text-danger">*</span></th>
                    <th style="width: 25%;">Deskripsi</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 10%;">Satuan</th>
                    <th style="width: 15%;">Spesifikasi</th>
                    <th style="width: 15%;">Keterangan</th>
                    <th style="width: 5%;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($billOfMaterial->itemBom as $index => $item)
                  <tr data-row="{{ $index }}">
                    <td>
                      <select class="form-control material-select select2" name="items[{{ $index }}][material_id]" data-row="{{ $index }}" required style="width: 100%;">
                        <option value="">Pilih Kode Material</option>
                        @foreach($materials as $material)
                          <option value="{{ $material->id }}" 
                                  data-desc="{{ $material->nama_material }}" 
                                  data-spec="{{ $material->spesifikasi }}"
                                  data-uom="{{ $material->uom ? $material->uom->satuan : '' }}" 
                                  data-qty="{{ $material->uom ? $material->uom->qty : 1 }}"
                                  {{ old('items.'.$index.'.material_id', $item->kode_material_id) == $material->id ? 'selected' : '' }}>
                            {{ $material->kode_material }} - {{ $material->nama_material }}
                          </option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control desc bg-light" value="{{ $item->kodeMaterial->nama_material }}" readonly>
                    </td>
                    <td>
                      <input type="number" class="form-control qty-input bg-light" name="items[{{ $index }}][qty]" 
                            value="{{ old('items.'.$index.'.qty', $item->qty) }}" step="0.01" min="0.01" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control uom bg-light" name="items[{{ $index }}][satuan]" 
                             value="{{ $item->satuan }}" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control spec bg-light" value="{{ $item->kodeMaterial->spesifikasi }}" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control" name="items[{{ $index }}][keterangan]" 
                             value="{{ old('items.'.$index.'.keterangan', $item->keterangan) }}" placeholder="Keterangan">
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Mobile Card View -->
          <div class="d-block d-md-none" id="itemContainerMobile">
            @foreach($billOfMaterial->itemBom as $index => $item)
            <div class="item-card-mobile" data-row="{{ $index }}">
              <div class="card-header">
                <strong>Item #{{ $index + 1 }}</strong>
                <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
              
              <div class="form-group">
                <label>Kode Material <span class="text-danger">*</span></label>
                <select class="form-control material-select select2" name="items[{{ $index }}][material_id]" data-row="{{ $index }}" required style="width: 100%;">
                  <option value="">Pilih Kode Material</option>
                  @foreach($materials as $material)
                    <option value="{{ $material->id }}" 
                            data-desc="{{ $material->nama_material }}" 
                            data-spec="{{ $material->spesifikasi }}"
                            data-uom="{{ $material->uom ? $material->uom->satuan : '' }}" 
                            data-qty="{{ $material->uom ? $material->uom->qty : 1 }}"
                            {{ old('items.'.$index.'.material_id', $item->kode_material_id) == $material->id ? 'selected' : '' }}>
                      {{ $material->kode_material }} - {{ $material->nama_material }}
                    </option>
                  @endforeach
                </select>
              </div>
              
              <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" class="form-control desc bg-light" value="{{ $item->kodeMaterial->nama_material }}" readonly>
              </div>
              
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Qty <span class="text-danger">*</span></label>
                    <input type="number" class="form-control qty-input bg-light" name="items[{{ $index }}][qty]" 
                          value="{{ old('items.'.$index.'.qty', $item->qty) }}" step="0.01" min="0.01" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" class="form-control uom bg-light" name="items[{{ $index }}][satuan]" 
                           value="{{ $item->satuan }}" readonly>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label>Spesifikasi</label>
                <textarea class="form-control spec bg-light" rows="2" readonly style="resize: none; overflow-y: auto;">{{ $item->kodeMaterial->spesifikasi }}</textarea>
              </div>
              
              <div class="form-group">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="items[{{ $index }}][keterangan]" 
                       value="{{ old('items.'.$index.'.keterangan', $item->keterangan) }}" placeholder="Keterangan (opsional)">
              </div>
            </div>
            @endforeach
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
                    <strong>Update sebagai Draft:</strong>
                    <p class="mb-0 text-muted">BOM diupdate dengan status DRAFT, dapat diedit kembali</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <strong>Update & Submit:</strong>
                    <p class="mb-0 text-muted">BOM diupdate dan langsung disubmit untuk approval level 1</p>
                  </div>
                </div>
              </div>
              
              <!-- Button Groups for Mobile and Desktop -->
              <div class="d-block d-sm-none mb-2">
                <!-- Mobile: Stacked buttons -->
                <button type="submit" class="btn btn-primary btn-block mb-2" name="action" value="draft">
                  <i class="fas fa-save"></i> Update sebagai Draft
                </button>
                <button type="submit" class="btn btn-success btn-block mb-2" name="action" value="submit">
                  <i class="fas fa-paper-plane"></i> Update & Submit untuk Approval
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
                    <i class="fas fa-save"></i> Update sebagai Draft
                  </button>
                  <button type="submit" class="btn btn-success" name="action" value="submit">
                    <i class="fas fa-paper-plane"></i> Update & Submit untuk Approval
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
          <i class="fas fa-question-circle"></i> Flow Approval BOM
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6><strong>Status BOM Saat Ini:</strong></h6>
          <span class="badge 
            @if($billOfMaterial->status === 'DRAFT') badge-secondary
            @elseif($billOfMaterial->status === 'PENDING APPROVAL 1') badge-warning
            @elseif($billOfMaterial->status === 'PENDING APPROVAL 2') badge-info
            @elseif($billOfMaterial->status === 'APPROVED') badge-success
            @elseif($billOfMaterial->status === 'REJECTED') badge-danger
            @else badge-light
            @endif
          ">{{ $billOfMaterial->status }}</span>
        </div>
        
        <div class="mb-3">
          <h6><strong>Status BOM:</strong></h6>
          <ul class="list-unstyled">
            <li><span class="badge badge-secondary">DRAFT</span> - BOM baru dibuat, dapat diedit</li>
            <li><span class="badge badge-warning">PENDING APPROVAL 1</span> - Menunggu persetujuan level 1</li>
            <li><span class="badge badge-info">PENDING APPROVAL 2</span> - Menunggu persetujuan level 2</li>
            <li><span class="badge badge-success">APPROVED</span> - BOM telah disetujui dan dipublish</li>
            <li><span class="badge badge-danger">REJECTED</span> - BOM ditolak, dapat diperbaiki</li>
          </ul>
        </div>
        
        <div class="mb-3">
          <h6><strong>Flow Approval:</strong></h6>
          <ol>
            <li>User membuat BOM → Status: <span class="badge badge-secondary">DRAFT</span></li>
            <li>User submit BOM → Status: <span class="badge badge-warning">PENDING APPROVAL 1</span></li>
            <li>Approver 1 approve → Status: <span class="badge badge-info">PENDING APPROVAL 2</span></li>
            <li>Approver 2 approve → Status: <span class="badge badge-success">APPROVED</span></li>
            <li>Jika di-reject → Status: <span class="badge badge-danger">REJECTED</span> (dapat diperbaiki)</li>
          </ol>
        </div>
        
        <div class="alert alert-warning">
          <small><i class="fas fa-exclamation-triangle"></i> <strong>Penting:</strong> 
          Setelah update, status BOM akan sesuai dengan pilihan aksi yang dipilih. Draft untuk tetap bisa diedit, Submit untuk langsung ke proses approval.</small>
        </div>
        
        <div class="alert alert-info">
          <small><i class="fas fa-info-circle"></i> <strong>Catatan:</strong> 
          Nomor BOM dan Proyek tidak dapat diubah setelah BOM dibuat.</small>
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
/* Select2 z-index fixes */
.select2-container {
  z-index: 1000 !important;
}

.select2-dropdown {
  z-index: 1010 !important;
}

.select2-container--open {
  z-index: 1005 !important;
}

.select2-container--open .select2-dropdown {
  z-index: 1015 !important;
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

.swal2-backdrop-show {
  background-color: rgba(0, 0, 0, 0.4) !important;
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

/* Responsive adjustments */
@media (max-width: 576px) {
  .card-body {
    padding: 1rem 0.75rem;
  }
  
  .item-card-mobile {
    padding: 0.75rem;
    margin-bottom: 0.75rem;
  }
  
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }
  
  .form-control {
    font-size: 0.875rem;
  }
}

/* Desktop Table Styling */
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
</style>
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
  let rowCounter = {{ count($billOfMaterial->itemBom) }};
  
  // Initialize main selects - removed proyek_id since it's now read-only
  const initSelect2 = (selector, placeholder) => {
    $(selector).select2({
      theme: 'bootstrap4',
      placeholder,
      allowClear: true,
      width: '100%'
    });
  };

  initSelect2('#revisi_id', 'Pilih Revisi');
  
  // Initialize Select2 for existing material selects
  $('.material-select').each(function() {
    initSelect2($(this), 'Pilih Kode Material');
  });

  // Force auto-fill untuk existing items setelah Select2 ready
  function forceAutoFillExistingItems() {
    $('.material-select').each(function() {
      const $select = $(this);
      const selectedValue = $select.val();
      
      if (selectedValue) {
        // Trigger change event untuk auto-fill
        $select.trigger('change');
      }
    });
  }

  // Jalankan auto-fill setelah Select2 ready
  setTimeout(forceAutoFillExistingItems, 200);
  
  // Sinkronisasi data antara desktop dan mobile view
  function syncDataBetweenViews() {
    // Sync dari desktop ke mobile
    $('#itemTableDesktop tbody tr').each(function(index) {
      const $desktopRow = $(this);
      const $mobileCard = $(`.item-card-mobile[data-row="${$desktopRow.data('row')}"]`);
      
      if ($mobileCard.length) {
        // Sync select value
        const materialValue = $desktopRow.find('.material-select').val();
        $mobileCard.find('.material-select').val(materialValue);
        
        // Sync other fields
        $mobileCard.find('.desc').val($desktopRow.find('.desc').val());
        $mobileCard.find('.qty-input').val($desktopRow.find('.qty-input').val());
        $mobileCard.find('.uom').val($desktopRow.find('.uom').val());
        $mobileCard.find('.spec').val($desktopRow.find('.spec').val());
        $mobileCard.find('input[name*="keterangan"]').val($desktopRow.find('input[name*="keterangan"]').val());
      }
    });
  }

  // Event listener untuk perubahan screen size
  let resizeTimeout;
  $(window).on('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
      syncDataBetweenViews();
    }, 250);
  });

  // Material change handler - perbaikan untuk sinkronisasi
  $(document).on('change', '.material-select', function() {
    const $select = $(this);
    const $container = $select.closest('tr, .item-card-mobile');
    const rowIndex = $container.data('row');
    const $option = $select.find('option:selected');
    
    if ($option.val()) {
      const data = {
        desc: $option.data('desc') || '',
        spec: $option.data('spec') || '',
        uom: $option.data('uom') || '',
        qty: $option.data('qty') || 1
      };
      
      // Update current view
      const $qtyInput = $container.find('.qty-input');
      $container.find('.desc').val(data.desc);
      $container.find('.spec').val(data.spec);
      $container.find('.uom').val(data.uom);
      
      // Preserve existing qty jika ada, otherwise use UOM qty
      const existingQty = $qtyInput.val();
      if (!existingQty || existingQty === '' || existingQty === '0') {
        $qtyInput.val(data.qty);
      }
      
      $qtyInput.addClass('bg-light').prop('readonly', true);
      
      // Sinkronisasi ke view yang lain
      if ($container.is('tr')) {
        // Update mobile card
        const $mobileCard = $(`.item-card-mobile[data-row="${rowIndex}"]`);
        if ($mobileCard.length) {
          $mobileCard.find('.material-select').val($option.val()).trigger('change.select2');
          $mobileCard.find('.desc').val(data.desc);
          $mobileCard.find('.spec').val(data.spec);
          $mobileCard.find('.uom').val(data.uom);
          if (!existingQty || existingQty === '' || existingQty === '0') {
            $mobileCard.find('.qty-input').val(data.qty);
          }
        }
      } else {
        // Update desktop row
        const $desktopRow = $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`);
        if ($desktopRow.length) {
          $desktopRow.find('.material-select').val($option.val()).trigger('change.select2');
          $desktopRow.find('.desc').val(data.desc);
          $desktopRow.find('.spec').val(data.spec);
          $desktopRow.find('.uom').val(data.uom);
          if (!existingQty || existingQty === '' || existingQty === '0') {
            $desktopRow.find('.qty-input').val(data.qty);
          }
        }
      }
      
      checkDuplicateMaterials();
    } else {
      // Clear fields pada kedua view
      $container.find('.desc, .spec, .uom').val('');
      $container.find('.qty-input').val('').removeClass('bg-light').prop('readonly', false);
      
      // Clear pada view yang lain juga
      if ($container.is('tr')) {
        const $mobileCard = $(`.item-card-mobile[data-row="${rowIndex}"]`);
        $mobileCard.find('.desc, .spec, .uom').val('');
        $mobileCard.find('.qty-input').val('').removeClass('bg-light').prop('readonly', false);
      } else {
        const $desktopRow = $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`);
        $desktopRow.find('.desc, .spec, .uom').val('');
        $desktopRow.find('.qty-input').val('').removeClass('bg-light').prop('readonly', false);
      }
    }
  });

  // Optimized duplicate check - hanya check yang visible
  function checkDuplicateMaterials() {
    const materials = [];
    let isValid = true;
    
    // Tentukan selector berdasarkan view yang aktif (desktop atau mobile)
    const isDesktopView = window.innerWidth >= 768;
    const selector = isDesktopView ? '#itemTableDesktop tbody .material-select' : '.item-card-mobile .material-select';
    
    $(selector).each(function() {
      const $this = $(this);
      const value = $this.val();
      
      // Clear previous validation
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

  // Add new item
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
        <td><input type="text" class="form-control desc bg-light" readonly></td>
        <td><input type="number" class="form-control qty-input bg-light" name="items[${rowCounter}][qty]" readonly></td>
        <td><input type="text" class="form-control uom bg-light" name="items[${rowCounter}][satuan]" readonly></td>
        <td><input type="text" class="form-control spec bg-light" readonly></td>
        <td><input type="text" class="form-control" name="items[${rowCounter}][keterangan]" placeholder="Keterangan (opsional)"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item"><i class="fas fa-trash"></i></button></td>
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
          <input type="text" class="form-control desc bg-light" readonly>
        </div>
        
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label>Qty <span class="text-danger">*</span></label>
              <input type="number" class="form-control qty-input bg-light" name="items[${rowCounter}][qty]" readonly>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label>Satuan</label>
              <input type="text" class="form-control uom bg-light" name="items[${rowCounter}][satuan]" readonly>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label>Spesifikasi</label>
          <textarea class="form-control spec bg-light" rows="2" readonly style="resize: none; overflow-y: auto;"></textarea>
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
  
  // Remove item with validation - perbaikan untuk responsive
  $(document).on('click', '.remove-item', function() {
    const $container = $(this).closest('[data-row]');
    const rowIndex = $container.data('row');
    
    // Hitung total item dari view yang aktif
    const isDesktopView = window.innerWidth >= 768;
    const itemCount = isDesktopView ? 
      $('#itemTableDesktop tbody tr').length : 
      $('.item-card-mobile').length;
    
    if (itemCount > 1) {
      // Destroy select2 dan remove dari kedua view
      $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`).find('.material-select').select2('destroy');
      $(`.item-card-mobile[data-row="${rowIndex}"]`).find('.material-select').select2('destroy');
      
      $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"]`).remove();
      $(`.item-card-mobile[data-row="${rowIndex}"]`).remove();
      
      checkDuplicateMaterials();
      updateArrayIndices();
    } else {
      showAlert('warning', 'Peringatan!', 'Minimal harus ada 1 item!');
    }
  });

  // Update indices after removal
  function updateArrayIndices() {
    let index = 0;
    $('#itemTableDesktop tbody tr').each(function() {
      const $row = $(this);
      $row.attr('data-row', index);
      $row.find('.material-select').attr('name', `items[${index}][material_id]`).attr('data-row', index);
      $row.find('.qty-input').attr('name', `items[${index}][qty]`);
      $row.find('.uom').attr('name', `items[${index}][satuan]`);
      $row.find('input[placeholder*="Keterangan"]').attr('name', `items[${index}][keterangan]`);
      index++;
    });
    
    index = 0;
    $('.item-card-mobile').each(function() {
      const $card = $(this);
      $card.attr('data-row', index);
      $card.find('.card-header strong').text(`Item #${index + 1}`);
      $card.find('.material-select').attr('name', `items[${index}][material_id]`).attr('data-row', index);
      $card.find('.qty-input').attr('name', `items[${index}][qty]`);
      $card.find('.uom').attr('name', `items[${index}][satuan]`);
      $card.find('input[placeholder*="Keterangan"]').attr('name', `items[${index}][keterangan]`);
      index++;
    });
  }
  
  // Form validation - perbaiki untuk responsive
  function validateForm() {
    // Tentukan view yang aktif
    const isDesktopView = window.innerWidth >= 768;
    const itemSelector = isDesktopView ? '#itemTableDesktop tbody tr' : '.item-card-mobile';
    
    const hasItems = $(itemSelector).length > 0;
    if (!hasItems) return 'Minimal harus ada 1 item!';
    
    let hasValidItems = false;
    $(itemSelector).each(function() {
      const materialId = $(this).find('.material-select').val();
      const qty = $(this).find('.qty-input').val();
      if (materialId && qty && parseFloat(qty) > 0) {
        hasValidItems = true;
        return false; // Break loop
      }
    });
    
    if (!hasValidItems) return 'Minimal harus ada 1 item dengan material dan quantity yang valid!';
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
      text: 'BOM akan diupdate dan langsung disubmit untuk approval level 1. Lanjutkan?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Update & Submit!',
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
      text: 'Form akan direset ke data asli. Semua perubahan akan hilang. Lanjutkan?',
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
        location.reload(); // Reload page to restore original data
      }
    });
  });

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

  // Sync keterangan field antara desktop dan mobile
  $(document).on('input', 'input[name*="keterangan"]', function() {
    const $input = $(this);
    const $container = $input.closest('tr, .item-card-mobile');
    const rowIndex = $container.data('row');
    const value = $input.val();
    
    // Sync ke view yang lain
    if ($container.is('tr')) {
      // Update mobile card
      $(`.item-card-mobile[data-row="${rowIndex}"] input[name*="keterangan"]`).val(value);
    } else {
      // Update desktop row
      $(`#itemTableDesktop tbody tr[data-row="${rowIndex}"] input[name*="keterangan"]`).val(value);
    }
  });

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
    timer: 3000,
    showConfirmButton: false,
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