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

        <h3 class="profile-username text-center">Mohammad Raka Saputra</h3>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Email</b> <a class="float-right">raka2553@gmail.com</a>
          </li>
          <li class="list-group-item">
            <b>User Group</b> <a class="float-right">Superadmin</a>
          </li>
          <li class="list-group-item">
            <b>Bergabung</b> <a class="float-right">01 Januari 2023</a>
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
        <strong><i class="fas fa-user mr-1"></i> Nama Lengkap</strong>
        <p class="text-muted">Mohammad Raka Saputra</p>
        <hr>
        
        <strong><i class="fas fa-at mr-1"></i> Email</strong>
        <p class="text-muted">raka2553@gmail.com</p>
        <hr>
        
        <strong><i class="fas fa-users mr-1"></i> User Group</strong>
        <p class="text-muted">Superadmin</p>
        <hr>
        
        <strong><i class="fas fa-calendar-alt mr-1"></i> Bergabung Sejak</strong>
        <p class="text-muted">01 Januari 2023</p>
      </div>
    </div>
  </div>
</div>
@endsection