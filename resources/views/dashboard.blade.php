@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="row">
  <!-- Kode Material -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>25</h3>
        <p>Kode Material</p>
      </div>
      <div class="icon">
        <i class="fas fa-cube"></i>
      </div>
      <a href="#" class="small-box-footer">Kelola Kode Material <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Revisi -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>5</h3>
        <p>Revisi</p>
      </div>
      <div class="icon">
        <i class="fas fa-edit"></i>
      </div>
      <a href="#" class="small-box-footer">Lihat Revisi <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Proyek -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>8</h3>
        <p>Proyek</p>
      </div>
      <div class="icon">
        <i class="fas fa-project-diagram"></i>
      </div>
      <a href="#" class="small-box-footer">Kelola Proyek <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- YUOM -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>12</h3>
        <p>YUOM</p>
      </div>
      <div class="icon">
        <i class="fas fa-balance-scale"></i>
      </div>
      <a href="#" class="small-box-footer">Kelola YUOM <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
@endsection
