@extends('layouts.app')

@section('title', 'Kode Material')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Kode Material</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Kode Material</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Kode Material</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Material</th>
          <th>Satuan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>KM001</td>
          <td>Besi Hollow</td>
          <td>Meter</td>
        </tr>
        <tr>
          <td>KM002</td>
          <td>Cat Tembok</td>
          <td>Liter</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
