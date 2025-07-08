@extends('layouts.app')

@section('title', 'Edit Profil')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Edit Profil</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
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
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Form Edit Profil</h3>
      </div>
      <form>
        <div class="card-body">
          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" value="Mohammad Raka Saputra">
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" value="raka2553@gmail.com">
          </div>
          
          <div class="form-group">
            <label>User Group</label>
            <input type="text" class="form-control" value="Superadmin" readonly>
          </div>
          
          <div class="form-group">
            <label>Bergabung Sejak</label>
            <input type="text" class="form-control" value="01 Januari 2023" readonly>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection