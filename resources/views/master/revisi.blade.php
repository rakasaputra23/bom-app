@extends('layouts.app')

@section('title', 'Revisi')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Revisi</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Revisi</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Revisi BOM</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Proyek</th>
          <th>Tanggal Revisi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>R001</td>
          <td>Gudang Sidoarjo</td>
          <td>2025-06-01</td>
        </tr>
        <tr>
          <td>R002</td>
          <td>Kantor Pusat</td>
          <td>2025-07-05</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
