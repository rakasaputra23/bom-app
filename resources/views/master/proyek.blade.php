@extends('layouts.app')

@section('title', 'Proyek')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Proyek</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Proyek</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Data Proyek Aktif</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nama Proyek</th>
          <th>Lokasi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Pembangunan Gudang</td>
          <td>Sidoarjo</td>
          <td>Berjalan</td>
        </tr>
        <tr>
          <td>Renovasi Kantor</td>
          <td>Surabaya</td>
          <td>Perencanaan</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
