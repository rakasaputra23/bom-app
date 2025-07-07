@extends('layouts.app')

@section('title', 'YUOM')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">YUOM</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">YUOM</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar YUOM</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Deskripsi</th>
          <th>Satuan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>YU001</td>
          <td>Unit Operasional Mesin</td>
          <td>Jam</td>
        </tr>
        <tr>
          <td>YU002</td>
          <td>Cycle Unit</td>
          <td>Unit</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
