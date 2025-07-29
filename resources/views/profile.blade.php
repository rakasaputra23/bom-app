@extends('layouts.app')

@section('title', 'Profil Saya')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Profil</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Profil</li>
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
            <b>Posisi</b> <a class="float-right">{{ auth()->user()->posisi }}</a>
          </li>
          <li class="list-group-item">
            <b>User Group</b> <a class="float-right">{{ auth()->user()->group->nama ?? 'Belum Ada Group' }}</a>
          </li>
          <li class="list-group-item">
            <b>Bergabung</b> <a class="float-right">{{ auth()->user()->created_at->format('d F Y') }}</a>
          </li>
        </ul>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Detail Profil</h3>
      </div>
      <div class="card-body">
        <strong><i class="fas fa-id-card mr-1"></i> NIP</strong>
        <p class="text-muted">{{ auth()->user()->nip }}</p>
        <hr>
        
        <strong><i class="fas fa-user mr-1"></i> Nama Lengkap</strong>
        <p class="text-muted">{{ auth()->user()->nama }}</p>
        <hr>
        
        <strong><i class="fas fa-briefcase mr-1"></i> Posisi</strong>
        <p class="text-muted">{{ auth()->user()->posisi }}</p>
        <hr>
        
        <strong><i class="fas fa-at mr-1"></i> Email</strong>
        <p class="text-muted">{{ auth()->user()->email }}</p>
        <hr>
        
        <strong><i class="fas fa-users mr-1"></i> User Group</strong>
        <p class="text-muted">{{ auth()->user()->group->nama ?? 'Belum Ada Group' }}</p>
        <hr>
        
        <strong><i class="fas fa-calendar-alt mr-1"></i> Bergabung Sejak</strong>
        <p class="text-muted">{{ auth()->user()->created_at->format('d F Y H:i') }}</p>
        <hr>
        
        <strong><i class="fas fa-clock mr-1"></i> Terakhir Update</strong>
        <p class="text-muted">{{ auth()->user()->updated_at->format('d F Y H:i') }}</p>
      </div>
    </div>

    @if(auth()->user()->isSuperAdmin())
    <div class="card mt-3">
      <div class="card-header bg-warning">
        <h3 class="card-title"><i class="fas fa-crown mr-1"></i> Status Super Admin</h3>
      </div>
      <div class="card-body">
        <p class="text-muted mb-0">Anda memiliki akses penuh ke seluruh sistem sebagai Super Administrator.</p>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection