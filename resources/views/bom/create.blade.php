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
    <form>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="kode_bom">Kode BOM</label>
          <input type="text" class="form-control" id="kode_bom" placeholder="Kode BOM" value="BOM-006" readonly>
        </div>
        <div class="form-group col-md-4">
          <label for="proyek">Proyek</label>
          <select class="form-control" id="proyek">
            <option value="">Pilih Proyek</option>
            <option value="1">Proyek Pembangunan Hotel</option>
            <option value="2">Proyek Apartemen Taman</option>
            <option value="3">Proyek Jembatan Suramadu</option>
            <option value="4">Proyek Mall Metropolitan</option>
            <option value="5">Proyek Gedung Perkantoran</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="revisi">Revisi</label>
          <select class="form-control" id="revisi">
            <option value="">Pilih Revisi</option>
            <option value="1">Rev. 1.0</option>
            <option value="2">Rev. 2.0</option>
            <option value="3">Rev. 3.0</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group col-md-6">
          <label for="status">Status</label>
          <select class="form-control" id="status">
            <option value="draft">Draft</option>
            <option value="submitted">Submitted</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" rows="3" placeholder="Deskripsi BOM"></textarea>
      </div>
      
      <hr>
      <h4>Item BOM</h4>
      
      <div class="table-responsive">
        <table class="table table-bordered" id="itemTable">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th style="width: 30%;">Material</th>
              <th style="width: 15%;">Kuantitas</th>
              <th style="width: 15%;">Satuan</th>
              <th style="width: 25%;">Keterangan</th>
              <th style="width: 10%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>
                <select class="form-control material-select">
                  <option value="">Pilih Material</option>
                  <option value="1">Besi Beton 10mm</option>
                  <option value="2">Besi Beton 12mm</option>
                  <option value="3">Semen Portland</option>
                  <option value="4">Pasir Beton</option>
                  <option value="5">Batu Split</option>
                </select>
              </td>
              <td><input type="number" class="form-control" step="0.01" min="0" value="0"></td>
              <td>
                <select class="form-control uom-select" disabled>
                  <option value="">-</option>
                  <option value="1">Batang</option>
                  <option value="2">Sak</option>
                  <option value="3">m³</option>
                  <option value="4">kg</option>
                </select>
              </td>
              <td><input type="text" class="form-control" placeholder="Keterangan"></td>
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
          <button type="button" class="btn btn-primary">
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
  // Initialize Select2
  $('.material-select').select2({
    theme: 'bootstrap4',
    placeholder: 'Pilih Material'
  });
  
  // Material change event
  $(document).on('change', '.material-select', function() {
    var row = $(this).closest('tr');
    var materialId = $(this).val();
    
    // In a real app, you would fetch UOM from server based on materialId
    var uom = '';
    switch(materialId) {
      case '1':
      case '2':
        uom = 'Batang';
        break;
      case '3':
        uom = 'Sak';
        break;
      case '4':
      case '5':
        uom = 'm³';
        break;
      default:
        uom = '';
    }
    
    row.find('.uom-select').val(uom || '');
  });
  
  // Add new item
  $('#addItem').click(function() {
    var rowCount = $('#itemTable tbody tr').length + 1;
    var newRow = `
      <tr>
        <td>${rowCount}</td>
        <td>
          <select class="form-control material-select">
            <option value="">Pilih Material</option>
            <option value="1">Besi Beton 10mm</option>
            <option value="2">Besi Beton 12mm</option>
            <option value="3">Semen Portland</option>
            <option value="4">Pasir Beton</option>
            <option value="5">Batu Split</option>
          </select>
        </td>
        <td><input type="number" class="form-control" step="0.01" min="0" value="0"></td>
        <td>
          <select class="form-control uom-select" disabled>
            <option value="">-</option>
            <option value="1">Batang</option>
            <option value="2">Sak</option>
            <option value="3">m³</option>
            <option value="4">kg</option>
          </select>
        </td>
        <td><input type="text" class="form-control" placeholder="Keterangan"></td>
        <td>
          <button type="button" class="btn btn-danger btn-sm remove-item">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
    
    $('#itemTable tbody').append(newRow);
    $('#itemTable tbody tr:last .material-select').select2({
      theme: 'bootstrap4',
      placeholder: 'Pilih Material'
    });
  });
  
  // Remove item
  $(document).on('click', '.remove-item', function() {
    if($('#itemTable tbody tr').length > 1) {
      $(this).closest('tr').remove();
      // Update row numbers
      $('#itemTable tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
      });
    } else {
      alert('Minimal harus ada 1 item!');
    }
  });
  
  // Save BOM
  $('.btn-primary').click(function() {
    // In a real app, you would submit the form here
    console.log('BOM berhasil disimpan!');
    // Redirect to index page
    window.location.href = "{{ route('bom.index') }}";
  });
});
</script>
@endpush