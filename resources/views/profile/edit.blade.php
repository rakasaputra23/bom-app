@extends('layouts.app')

@section('title', 'Edit Profil')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Edit Profil</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Form Edit Profil</h3>
      </div>
      
      <!-- Form (static/tampilan saja) -->
      <form>
        <div class="card-body">
          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" class="form-control" 
                   id="name" name="name" value="Admin Demo" 
                   placeholder="Masukkan nama lengkap">
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" 
                   id="email" name="email" value="admin@example.com" 
                   placeholder="Masukkan email">
          </div>
          
          <div class="form-group">
            <label for="phone">Telepon</label>
            <input type="text" class="form-control" 
                   id="phone" name="phone" value="+62 812-3456-7890" 
                   placeholder="Masukkan nomor telepon">
          </div>
          
          <div class="form-group">
            <label for="address">Alamat</label>
            <textarea class="form-control" 
                      id="address" name="address" rows="3" 
                      placeholder="Masukkan alamat lengkap">Sidoarjo, Indonesia</textarea>
          </div>
          
          <div class="form-group">
            <label for="photo">Foto Profil</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="photo" name="photo">
                <label class="custom-file-label" for="photo">Pilih file</label>
              </div>
            </div>
            <small class="text-muted">Format: JPG, PNG maksimal 2MB</small>
          </div>
        </div>
        
        <div class="card-footer">
          <button type="button" class="btn btn-primary" onclick="alert('Ini hanya tampilan, tidak ada fungsi update')">Simpan Perubahan</button>
          <a href="{{ route('profile') }}" class="btn btn-default float-right">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Hanya untuk menampilkan nama file yang dipilih (tidak ada upload sebenarnya)
  document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file";
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
  });
</script>
@endpush