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
      <li class="breadcrumb-item active">Edit Profil</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid img-circle"
              src="{{ asset('dist/img/user2-160x160.jpg') }}"
              alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ auth()->user()->nama }}</h3>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>NIP</b> <a class="float-right">{{ auth()->user()->nip }}</a>
          </li>
          <li class="list-group-item">
            <b>Email</b> <a class="float-right">{{ auth()->user()->email }}</a>
          </li>
          <li class="list-group-item">
            <b>User Group</b> <a class="float-right">{{ auth()->user()->group->nama ?? 'Belum Ada Group' }}</a>
          </li>
          <li class="list-group-item">
            <b>Bergabung</b> <a class="float-right">{{ auth()->user()->created_at->format('d F Y') }}</a>
          </li>
        </ul>

        <a href="{{ route('profile') }}" class="btn btn-secondary btn-block"><b>Kembali ke Profil</b></a>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Form Edit Profil</h3>
      </div>
      
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        
        <div class="card-body">
          <div class="form-group">
            <label for="nip">NIP <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('nip') is-invalid @enderror" 
                   id="nip" 
                   name="nip"
                   value="{{ old('nip', auth()->user()->nip) }}"
                   required>
            @error('nip')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('nama') is-invalid @enderror" 
                   id="nama" 
                   name="nama"
                   value="{{ old('nama', auth()->user()->nama) }}"
                   required>
            @error('nama')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="posisi">Posisi <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('posisi') is-invalid @enderror" 
                   id="posisi" 
                   name="posisi"
                   value="{{ old('posisi', auth()->user()->posisi) }}"
                   required>
            @error('posisi')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email"
                   value="{{ old('email', auth()->user()->email) }}"
                   required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label>User Group</label>
            <input type="text" 
                   class="form-control" 
                   value="{{ auth()->user()->group->nama ?? 'Belum Ada Group' }}" 
                   readonly>
            <small class="text-muted">User Group tidak dapat diubah sendiri. Hubungi administrator untuk mengubah group.</small>
          </div>
          
          <div class="form-group">
            <label>Bergabung Sejak</label>
            <input type="text" 
                   class="form-control" 
                   value="{{ auth()->user()->created_at->format('d F Y H:i') }}" 
                   readonly>
          </div>

          <hr>
          <h5>Ubah Password (Opsional)</h5>
          <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>

          <div class="form-group mt-3">
            <label for="current_password">Password Lama</label>
            <input type="password" 
                   class="form-control @error('current_password') is-invalid @enderror" 
                   id="current_password" 
                   name="current_password">
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password"
                   minlength="6">
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation"
                   minlength="6">
          </div>
        </div>
        
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Simpan Perubahan
          </button>
          <a href="{{ route('profile') }}" class="btn btn-secondary ml-2">
            <i class="fas fa-times mr-1"></i> Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection