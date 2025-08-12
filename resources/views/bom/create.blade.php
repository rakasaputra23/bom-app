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
              <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
              <th style="width: 10%;">Satuan <span class="text-danger">*</span></th>
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
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
  let rowCounter = 0;
  
  // Initialize Select2 for main form selects
  $('#proyek_id').select2({
    theme: 'bootstrap4',
    placeholder: 'Pilih Proyek',
    allowClear: true
  });

  $('#revisi_id').select2({
    theme: 'bootstrap4',
    placeholder: 'Pilih Revisi',
    allowClear: true
  });

  // Add first item on page load
  addNewItem();
  
  // Material change event with preloaded data
  $(document).on('change', '.material-select', function() {
    const row = $(this).closest('tr');
    const selectedOption = $(this).find('option:selected');
    
    if(selectedOption.length > 0 && selectedOption.val() !== '') {
      // Get data attributes from selected option
      const description = selectedOption.data('desc') || '';
      const specification = selectedOption.data('spec') || '';
      const unit = selectedOption.data('uom') || '';
      const qty = selectedOption.data('qty') || 1;
      
      // Auto-fill all fields
      row.find('.desc').val(description);
      row.find('.spec').val(specification);
      row.find('.uom').val(unit);
      row.find('.qty-input').val(qty);
      
      // Check for duplicate materials
      checkDuplicateMaterials();
    } else {
      // Clear all fields if no material selected
      row.find('.desc, .spec, .uom').val('');
      row.find('.qty-input').val('');
    }
  });

  // Check for duplicate materials
  function checkDuplicateMaterials() {
    const selectedMaterials = [];
    let hasDuplicate = false;
    
    $('.material-select').each(function() {
      const materialId = $(this).val();
      if (materialId && selectedMaterials.includes(materialId)) {
        hasDuplicate = true;
        $(this).addClass('is-invalid');
        
        // Show error message
        let errorDiv = $(this).next('.invalid-feedback');
        if (!errorDiv.length) {
          errorDiv = $('<div class="invalid-feedback"></div>').insertAfter($(this));
        }
        errorDiv.text('Material sudah dipilih sebelumnya');
      } else {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
        if (materialId) selectedMaterials.push(materialId);
      }
    });
    
    return !hasDuplicate;
  }

  // Add new item function
  function addNewItem() {
    const newRow = `
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
        <td>
          <input type="text" class="form-control desc bg-light" readonly>
        </td>
        <td>
          <input type="number" class="form-control qty-input" name="items[${rowCounter}][qty]" step="0.01" min="0.01" required>
        </td>
        <td>
          <input type="text" class="form-control uom bg-light" name="items[${rowCounter}][satuan]" readonly>
        </td>
        <td>
          <input type="text" class="form-control spec bg-light" readonly>
        </td>
        <td>
          <input type="text" class="form-control" name="items[${rowCounter}][keterangan]" placeholder="Keterangan (opsional)">
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Item">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
    
    $('#itemTable tbody').append(newRow);
    
    // Initialize Select2 for the new row
    const newSelect = $('#itemTable tbody tr:last .material-select');
    newSelect.select2({
      theme: 'bootstrap4',
      placeholder: 'Pilih Kode Material',
      allowClear: true,
      width: '100%'
    });
    
    rowCounter++;
  }

  // Add item button click
  $('#addItem').click(function() {
    addNewItem();
  });
  
  // Remove item with validation
  $(document).on('click', '.remove-item', function() {
    if($('#itemTable tbody tr').length > 1) {
      // Destroy Select2 before removing
      $(this).closest('tr').find('.material-select').select2('destroy');
      $(this).closest('tr').remove();
      
      // Recheck for duplicates after removal
      checkDuplicateMaterials();
      
      // Update array indices
      updateArrayIndices();
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: 'Minimal harus ada 1 item!'
      });
    }
  });

  // Update array indices after item removal
  function updateArrayIndices() {
    $('#itemTable tbody tr').each(function(index) {
      $(this).find('.material-select').attr('name', `items[${index}][material_id]`);
      $(this).find('.qty-input').attr('name', `items[${index}][qty]`);
      $(this).find('.uom').attr('name', `items[${index}][satuan]`);
      $(this).find('input[placeholder="Keterangan (opsional)"]').attr('name', `items[${index}][keterangan]`);
    });
  }
  
  // Form validation
  $('#bomForm').on('submit', function(e) {
    let isValid = true;
    let errorMessage = '';
    
    // Check if we have items
    const hasItems = $('#itemTable tbody tr').length > 0;
    if (!hasItems) {
      errorMessage = 'Minimal harus ada 1 item!';
      isValid = false;
    }
    
    // Check for valid items
    let hasValidItems = false;
    $('#itemTable tbody tr').each(function() {
      const materialId = $(this).find('.material-select').val();
      const qty = $(this).find('.qty-input').val();
      if (materialId && qty && parseFloat(qty) > 0) {
        hasValidItems = true;
      }
    });
    
    if (!hasValidItems) {
      errorMessage = 'Minimal harus ada 1 item dengan material dan quantity yang valid!';
      isValid = false;
    }
    
    // Check for duplicate materials
    if (!checkDuplicateMaterials()) {
      errorMessage = 'Terdapat material yang sama dipilih lebih dari sekali!';
      isValid = false;
    }
    
    if (!isValid) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: errorMessage
      });
      return false;
    }
  });

  // Handle form submission based on button clicked
  $('button[type="submit"]').on('click', function() {
    const action = $(this).val();
    if (action === 'submit') {
      // Show confirmation for submit action
      $('#bomForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
          title: 'Konfirmasi Submit',
          text: 'BOM akan disimpan dan langsung disubmit untuk approval level 1. Lanjutkan?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Submit!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Add hidden input to indicate submit action
            $('<input>').attr({
              type: 'hidden',
              name: 'submit_for_approval',
              value: 'true'
            }).appendTo('#bomForm');
            
            // Submit form
            $('#bomForm')[0].submit();
          }
        });
      });
    }
  });

  // Clear validation on input change
  $('input, select').on('change', function() {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').remove();
  });

  // Form reset handling
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
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Reset Select2 values
        $('#proyek_id, #revisi_id').val(null).trigger('change');
        
        // Clear all rows and add one fresh row
        $('#itemTable tbody').empty();
        rowCounter = 0;
        addNewItem();
        
        // Clear validation
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Reset form fields
        $('#bomForm')[0].reset();
        $('#nomor_bom').val('{{ $nomorBom }}');
        $('#tanggal').val('{{ date('Y-m-d') }}');
        
        Swal.fire({
          icon: 'success',
          title: 'Form telah direset!',
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
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