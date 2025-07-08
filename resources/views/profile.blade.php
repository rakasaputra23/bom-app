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

          <h3 class="profile-username text-center">Admin Demo</h3>

          <p class="text-muted text-center">Administrator</p>

          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Email</b> <a class="float-right">admin@example.com</a>
            </li>
            <li class="list-group-item">
              <b>Telepon</b> <a class="float-right">+62 812-3456-7890</a>
            </li>
            <li class="list-group-item">
              <b>Alamat</b> <a class="float-right">Sidoarjo, Indonesia</a>
            </li>
          </ul>

          <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informasi Profil</h3>
        </div>
        <div class="card-body">
          <strong><i class="fas fa-user mr-1"></i> Nama Lengkap</strong>
          <p class="text-muted">Admin Demo</p>
          <hr>
          
          <strong><i class="fas fa-briefcase mr-1"></i> Jabatan</strong>
          <p class="text-muted">Super Administrator</p>
          <hr>
          
          <strong><i class="fas fa-user-shield mr-1"></i> Status</strong>
          <p class="text-muted">
            <span class="badge badge-success">Admin</span>
            <!-- Jika user biasa: -->
            <!-- <span class="badge badge-primary">User</span> -->
          </p>
          <hr>
          
          <strong><i class="fas fa-calendar-alt mr-1"></i> Bergabung Sejak</strong>
          <p class="text-muted">01 Januari 2023</p>
        </div>
      </div>
    </div>
  </div>
  @endsection