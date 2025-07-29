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
      <div class="row">
        <div class="form-group col-md-3">
          <label for="nomor_bom">Nomor BOM</label>
          <input type="text" class="form-control @error('nomor_bom') is-invalid @enderror" 
                 id="nomor_bom" name="nomor_bom" placeholder="Nomor BOM" 
                 value="{{ old('nomor_bom', 'BOM-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" 
                 readonly>
          @error('nomor_bom')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group col-md-3">
          <label for="kategori">Kategori</label>
          <select class="form-control @error('kategori') is-invalid @enderror" 
                  id="kategori" name="kategori" required>
            <option value="">Pilih Kategori</option>
            <option value="JIG, TOOL DAN MAL" {{ old('kategori') == 'JIG, TOOL DAN MAL' ? 'selected' : '' }}>JIG, TOOL DAN MAL</option>
            <option value="TOOLS" {{ old('kategori') == 'TOOLS' ? 'selected' : '' }}>TOOLS</option>
            <option value="CONSUMABLE TOOLS" {{ old('kategori') == 'CONSUMABLE TOOLS' ? 'selected' : '' }}>CONSUMABLE TOOLS</option>
            <option value="SPECIAL PROCESS" {{ old('kategori') == 'SPECIAL PROCESS' ? 'selected' : '' }}>SPECIAL PROCESS</option>
          </select>
          @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group col-md-3">
          <label for="proyek_id">Proyek <span class="text-red">*</span></label>
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
          <label for="revisi_id">Revisi <span class="text-red">*</span></label>
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
      <div class="form-group">
        <label for="tanggal">Tanggal</label>
        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
        @error('tanggal')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      
      <hr>
      <h4>Item BOM</h4>
      
      <div class="table-responsive">
        <table class="table table-bordered" id="itemTable">
          <thead>
            <tr>
              <th style="width: 15%;">Kode Material</th>
              <th style="width: 25%;">Deskripsi</th>
              <th style="width: 10%;">Qty</th>
              <th style="width: 10%;">Satuan</th>
              <th style="width: 15%;">Spesifikasi</th>
              <th style="width: 15%;">Keterangan</th>
              <th style="width: 5%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <select class="form-control material-select select2" name="items[0][material_id]" required style="width: 100%;">
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
                <input type="text" class="form-control desc" readonly>
              </td>
              <td>
                <input type="number" class="form-control qty-input" name="items[0][qty]" step="0.01" min="0.01" readonly>
              </td>
              <td>
                <input type="text" class="form-control uom" name="items[0][satuan]" readonly>
              </td>
              <td>
                <input type="text" class="form-control spec" readonly>
              </td>
              <td>
                <input type="text" class="form-control" name="items[0][keterangan]" placeholder="Keterangan">
              </td>
              <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <button type="button" id="addItem" class="btn btn-secondary">
        <i class="fas fa-plus"></i> Tambah Item
      </button>
      
      <hr>
      
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan BOM
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
          <a href="{{ route('bom.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
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
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {
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

  // Initialize Select2 for material selects
  $('.material-select').select2({
    theme: 'bootstrap4',
    placeholder: 'Pilih Kode Material',
    allowClear: true,
    width: '100%'
  });

  // Auto-fill data for existing items on page load
  $('.material-select').each(function() {
    var row = $(this).closest('tr');
    var selectedOption = $(this).find('option:selected');
    
    if(selectedOption.length > 0 && selectedOption.val() !== '') {
      var description = selectedOption.data('desc') || '';
      var specification = selectedOption.data('spec') || '';
      var unit = selectedOption.data('uom') || '';
      var qty = selectedOption.data('qty') || 1;
      
      row.find('.desc').val(description);
      row.find('.spec').val(specification);
      row.find('.uom').val(unit);
      row.find('.qty-input').val(qty);
    }
  });
  
  // Material change event
  $(document).on('change', '.material-select', function() {
    var row = $(this).closest('tr');
    var selectedOption = $(this).find('option:selected');
    
    if(selectedOption.length > 0 && selectedOption.val() !== '') {
      var description = selectedOption.data('desc') || '';
      var specification = selectedOption.data('spec') || '';
      var unit = selectedOption.data('uom') || '';
      var qty = selectedOption.data('qty') || 1;
      
      // Auto-fill all fields
      row.find('.desc').val(description);
      row.find('.spec').val(specification);
      row.find('.uom').val(unit);
      row.find('.qty-input').val(qty);
    } else {
      // Clear all fields if no material selected
      row.find('.desc').val('');
      row.find('.spec').val('');
      row.find('.uom').val('');
      row.find('.qty-input').val('');
    }
  });

  // Add new item with Select2 initialization
  $('#addItem').click(function() {
    var rowCount = $('#itemTable tbody tr').length;
    var newRow = `
      <tr>
        <td>
          <select class="form-control material-select select2" name="items[${rowCount}][material_id]" required style="width: 100%;">
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
          <input type="text" class="form-control desc" readonly>
        </td>
        <td>
          <input type="number" class="form-control qty-input" name="items[${rowCount}][qty]" step="0.01" min="0.01" readonly>
        </td>
        <td>
          <input type="text" class="form-control uom" name="items[${rowCount}][satuan]" readonly>
        </td>
        <td>
          <input type="text" class="form-control spec" readonly>
        </td>
        <td>
          <input type="text" class="form-control" name="items[${rowCount}][keterangan]" placeholder="Keterangan">
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm remove-item">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
    
    $('#itemTable tbody').append(newRow);
    
    // Initialize Select2 for the new row
    $('#itemTable tbody tr:last .material-select').select2({
      theme: 'bootstrap4',
      placeholder: 'Pilih Kode Material',
      allowClear: true,
      width: '100%'
    });
  });
  
  // Remove item with Select2 destroy
  $(document).on('click', '.remove-item', function() {
    if($('#itemTable tbody tr').length > 1) {
      // Destroy Select2 before removing
      $(this).closest('tr').find('.material-select').select2('destroy');
      $(this).closest('tr').remove();
      
      // Update array indices
      $('#itemTable tbody tr').each(function(index) {
        $(this).find('.material-select').attr('name', `items[${index}][material_id]`);
        $(this).find('.qty-input').attr('name', `items[${index}][qty]`);
        $(this).find('.uom').attr('name', `items[${index}][satuan]`);
        $(this).find('input[placeholder="Keterangan"]').attr('name', `items[${index}][keterangan]`);
      });
    } else {
      alert('Minimal harus ada 1 item!');
    }
  });
  
  // Form validation
  $('#bomForm').on('submit', function(e) {
    var hasItems = $('#itemTable tbody tr').length > 0;
    var hasValidItems = false;
    
    $('#itemTable tbody tr').each(function() {
      var materialId = $(this).find('.material-select').val();
      if(materialId) {
        hasValidItems = true;
      }
    });
    
    if(!hasItems || !hasValidItems) {
      e.preventDefault();
      alert('Minimal harus ada 1 item dengan material yang valid!');
      return false;
    }
  });

  // Clear validation on input change
  $('input, select').on('change', function() {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').text('');
  });

  // Form reset handling
  $('button[type="reset"]').on('click', function() {
    // Reset Select2 values
    $('#proyek_id, #revisi_id').val(null).trigger('change');
    $('.material-select').val(null).trigger('change');
    
    // Clear readonly fields
    $('.desc, .spec, .uom, .qty-input').val('');
    
    // Clear validation
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
  });
});
</script>
@endpush