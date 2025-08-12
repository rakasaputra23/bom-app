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
<!-- Info Alert -->
<div class="alert alert-info">
  <i class="fas fa-info-circle"></i>
  <strong>Informasi:</strong> BOM yang dibuat akan memiliki status <strong>DRAFT</strong>. Anda dapat memilih untuk menyimpan sebagai draft atau langsung submit untuk proses approval.
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
        <div class="form-group col-md-3">
          <label for="nomor_bom">Nomor BOM <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('nomor_bom') is-invalid @enderror" 
                 id="nomor_bom" name="nomor_bom" placeholder="Nomor BOM" 
                 value="{{ old('nomor_bom', $nomorBom) }}" 
                 readonly>
          @error('nomor_bom')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="form-text text-muted">Nomor otomatis tergenerate</small>
        </div>
        <div class="form-group col-md-3">
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
        <div class="form-group col-md-3">
          <label for="proyek_id">Proyek <span class="text-danger">*</span></label>
          <select class="form-control select2 @error('proyek_id') is-invalid @enderror" 
                  id="proyek_id" name="proyek_id" required style="width: 100%;">
            <option value="">Pilih Proyek</option>
            @foreach($proyeks as $proyek)
              <option value="{{ $proyek->id }}" {{ old('proyek_id') == $proyek->id ? 'selected' : '' }}>
                {{ $proyek->display_name }}
              </option>
            @endforeach
          </select>
          @error('proyek_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group col-md-3">
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
      </div>
      
      <div class="row">
        <div class="form-group col-md-4">
          <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
          <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                 id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
          @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group col-md-4">
          <label>Dibuat Oleh</label>
          <input type="text" class="form-control" 
                 value="{{ Auth::user()->nama ?? Auth::user()->name }} ({{ Auth::user()->nip ?? Auth::user()->email }})" readonly>
          <small class="form-text text-muted">Pembuat BOM otomatis tercatat</small>
        </div>
        <div class="form-group col-md-4">
          <label>Status Awal</label>
          <input type="text" class="form-control" value="DRAFT" readonly>
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
      
      <div class="table-responsive">
        <table class="table table-bordered" id="itemTable">
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
            <!-- Initial empty row -->
          </tbody>
        </table>
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
              <div class="col-md-6">
                <strong>Simpan sebagai Draft:</strong>
                <p class="mb-0 text-muted">BOM disimpan dengan status DRAFT, dapat diedit kembali</p>
              </div>
              <div class="col-md-6">
                <strong>Simpan & Submit:</strong>
                <p class="mb-0 text-muted">BOM disimpan dan langsung disubmit untuk approval level 1</p>
              </div>
            </div>
          </div>
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
      
      <!-- Help Section -->
      <div class="row mt-3">
        <div class="col-12">
          <div class="card card-outline card-info collapsed-card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-question-circle"></i> Flow Approval BOM
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h6><strong>Status BOM:</strong></h6>
                  <ul class="list-unstyled">
                    <li><span class="badge badge-secondary">DRAFT</span> - BOM baru dibuat, dapat diedit</li>
                    <li><span class="badge badge-warning">PENDING APPROVAL 1</span> - Menunggu persetujuan level 1</li>
                    <li><span class="badge badge-info">PENDING APPROVAL 2</span> - Menunggu persetujuan level 2</li>
                    <li><span class="badge badge-success">APPROVED</span> - BOM telah disetujui dan dipublish</li>
                    <li><span class="badge badge-danger">REJECTED</span> - BOM ditolak, dapat diperbaiki</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <h6><strong>Flow Approval:</strong></h6>
                  <ol>
                    <li>User membuat BOM → Status: <span class="badge badge-secondary">DRAFT</span></li>
                    <li>User submit BOM → Status: <span class="badge badge-warning">PENDING APPROVAL 1</span></li>
                    <li>Approver 1 approve → Status: <span class="badge badge-info">PENDING APPROVAL 2</span></li>
                    <li>Approver 2 approve → Status: <span class="badge badge-success">APPROVED</span></li>
                    <li>Jika di-reject → Status: <span class="badge badge-danger">REJECTED</span> (dapat diperbaiki)</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<!-- Custom CSS untuk memperbaiki overlay SweetAlert2 -->
<style>
/* Pastikan backdrop SweetAlert2 memblokir semua interaksi */
.swal2-container.swal2-backdrop-fix {
  z-index: 99999 !important;
}

.swal2-container.swal2-backdrop-fix .swal2-backdrop {
  background-color: rgba(0, 0, 0, 0.4) !important;
  pointer-events: all !important;
}

/* Pastikan elemen select2 tidak bisa di-hover saat modal aktif */
.swal2-shown .select2-container,
.swal2-shown .form-control,
.swal2-shown .btn {
  pointer-events: none !important;
}

/* Pastikan modal SweetAlert2 tetap bisa diinteraksi */
.swal2-shown .swal2-container * {
  pointer-events: auto !important;
}

/* Hilangkan highlight effect pada elemen yang tertutup modal */
.swal2-shown .form-control:focus,
.swal2-shown .select2-container--bootstrap4 .select2-selection--single:focus {
  box-shadow: none !important;
  border-color: #ced4da !important;
}

/* Pastikan backdrop menutupi semua elemen */
.swal2-backdrop-show {
  background-color: rgba(0, 0, 0, 0.4) !important;
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
      width: '100%'
    });
  };

  initSelect2('#proyek_id', 'Pilih Proyek');
  initSelect2('#revisi_id', 'Pilih Revisi');

  // Add first item on load
  addNewItem();
  
  // Material change handler
  $(document).on('change', '.material-select', function() {
    const $row = $(this).closest('tr');
    const $option = $(this).find('option:selected');
    
    if ($option.val()) {
      const data = {
        desc: $option.data('desc') || '',
        spec: $option.data('spec') || '',
        uom: $option.data('uom') || '',
        qty: $option.data('qty') || 1
      };
      
      // Auto-fill fields
      $row.find('.desc').val(data.desc);
      $row.find('.spec').val(data.spec);
      $row.find('.uom').val(data.uom);
      $row.find('.qty-input').val(data.qty);
      
      checkDuplicateMaterials();
    } else {
      // Clear fields
      $row.find('.desc, .spec, .uom, .qty-input').val('');
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

  // Add new item
  function addNewItem() {
    const template = `
      <tr data-row="${rowCounter}">
        <td>
          <select class="form-control material-select select2" name="items[${rowCounter}][material_id]" required style="width: 100%;">
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
    
    const $newRow = $(template).appendTo('#itemTable tbody');
    initSelect2($newRow.find('.material-select'), 'Pilih Kode Material');
    rowCounter++;
  }

  $('#addItem').click(addNewItem);
  
  // Remove item with validation
  $(document).on('click', '.remove-item', function() {
    const $tbody = $('#itemTable tbody');
    const rowCount = $tbody.children().length;
    
    if (rowCount > 1) {
      $(this).closest('tr').find('.material-select').select2('destroy');
      $(this).closest('tr').remove();
      checkDuplicateMaterials();
      updateArrayIndices();
    } else {
      showAlert('warning', 'Peringatan!', 'Minimal harus ada 1 item!');
    }
  });

  // Update indices after removal
  function updateArrayIndices() {
    $('#itemTable tbody tr').each(function(index) {
      const $row = $(this);
      $row.find('.material-select').attr('name', `items[${index}][material_id]`);
      $row.find('.qty-input').attr('name', `items[${index}][qty]`);
      $row.find('.uom').attr('name', `items[${index}][satuan]`);
      $row.find('input[placeholder*="Keterangan"]').attr('name', `items[${index}][keterangan]`);
    });
  }
  
  // Form validation
  function validateForm() {
    const hasItems = $('#itemTable tbody tr').length > 0;
    if (!hasItems) return 'Minimal harus ada 1 item!';
    
    let hasValidItems = false;
    $('#itemTable tbody tr').each(function() {
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
    $('#proyek_id, #revisi_id').val(null).trigger('change');
    $('#itemTable tbody').empty();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    $('#bomForm')[0].reset();
    $('#nomor_bom').val('{{ $nomorBom }}');
    $('#tanggal').val('{{ date('Y-m-d') }}');
    
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