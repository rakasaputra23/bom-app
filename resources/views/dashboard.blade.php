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

<!-- Second Row -->
<div class="row">
  <!-- Activities -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-pie mr-1"></i>
          Aktivitas Terbaru
        </h3>
        <div class="card-tools">
          <ul class="nav nav-pills ml-auto">
            <li class="nav-item">
              <a class="nav-link active" href="#activity-chart" data-toggle="tab">Chart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#activity-list" data-toggle="tab">List</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="tab-content p-0">
          <!-- Chart tab -->
          <div class="chart tab-pane active" id="activity-chart">
            <canvas id="activityChart" height="300" style="height: 300px;"></canvas>
          </div>
          <!-- List tab -->
          <div class="tab-pane" id="activity-list">
            <div class="timeline timeline-inverse">
              <div class="time-label">
                <span class="bg-danger">
                  {{ date('d M Y') }}
                </span>
              </div>
              <div>
                <i class="fas fa-user bg-info"></i>
                <div class="timeline-item">
                  <span class="time"><i class="far fa-clock"></i> 2 jam yang lalu</span>
                  <h3 class="timeline-header"><a href="#">Admin</a> menambahkan material baru</h3>
                  <div class="timeline-body">
                    Material "Semen Portland" telah ditambahkan ke dalam sistem.
                  </div>
                </div>
              </div>
              <div>
                <i class="fas fa-edit bg-warning"></i>
                <div class="timeline-item">
                  <span class="time"><i class="far fa-clock"></i> 5 jam yang lalu</span>
                  <h3 class="timeline-header"><a href="#">User</a> melakukan revisi</h3>
                  <div class="timeline-body">
                    Revisi pada proyek "Pembangunan Gedung A" telah disubmit.
                  </div>
                </div>
              </div>
              <div>
                <i class="fas fa-plus bg-success"></i>
                <div class="timeline-item">
                  <span class="time"><i class="far fa-clock"></i> 1 hari yang lalu</span>
                  <h3 class="timeline-header"><a href="#">Manager</a> membuat proyek baru</h3>
                  <div class="timeline-body">
                    Proyek "Renovasi Kantor" telah dibuat dan sedang menunggu persetujuan.
                  </div>
                </div>
              </div>
              <div>
                <i class="far fa-clock bg-gray"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transactions -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-exchange-alt mr-1"></i>
          Transaksi Terbaru
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0">
            <thead>
              <tr>
                <th>ID Transaksi</th>
                <th>Material</th>
                <th>Jumlah</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><a href="#">TRX-001</a></td>
                <td>Semen Portland</td>
                <td>50 Sak</td>
                <td><span class="badge badge-success">Selesai</span></td>
              </tr>
              <tr>
                <td><a href="#">TRX-002</a></td>
                <td>Besi Beton</td>
                <td>100 Batang</td>
                <td><span class="badge badge-warning">Proses</span></td>
              </tr>
              <tr>
                <td><a href="#">TRX-003</a></td>
                <td>Pasir Halus</td>
                <td>5 m³</td>
                <td><span class="badge badge-danger">Pending</span></td>
              </tr>
              <tr>
                <td><a href="#">TRX-004</a></td>
                <td>Keramik</td>
                <td>200 Pcs</td>
                <td><span class="badge badge-info">Dikirim</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Transaksi Baru</a>
        <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Lihat Semua</a>
      </div>
    </div>
  </div>
</div>

<!-- Third Row - Reports -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-bar mr-1"></i>
          Laporan & Statistik
        </h3>
        <div class="card-tools">
          <div class="btn-group">
            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
              <i class="fas fa-wrench"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" role="menu">
              <a href="#" class="dropdown-item">Export PDF</a>
              <a href="#" class="dropdown-item">Export Excel</a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">Pengaturan</a>
            </div>
          </div>
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <div class="chart">
              <canvas id="reportChart" height="180" style="height: 180px;"></canvas>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Material</span>
                <span class="info-box-number">1,410</span>
              </div>
            </div>
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Transaksi Selesai</span>
                <span class="info-box-number">892</span>
              </div>
            </div>
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Dalam Proses</span>
                <span class="info-box-number">518</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-sm-3 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
              <h5 class="description-header">Rp 35,210,000</h5>
              <span class="description-text">TOTAL PENDAPATAN</span>
            </div>
          </div>
          <div class="col-sm-3 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
              <h5 class="description-header">Rp 10,390,000</h5>
              <span class="description-text">TOTAL BIAYA</span>
            </div>
          </div>
          <div class="col-sm-3 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
              <h5 class="description-header">Rp 24,820,000</h5>
              <span class="description-text">TOTAL KEUNTUNGAN</span>
            </div>
          </div>
          <div class="col-sm-3 col-6">
            <div class="description-block">
              <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
              <h5 class="description-header">1200</h5>
              <span class="description-text">JUMLAH PROYEK</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(function () {
  // Activity Chart
  var activityChartCanvas = $('#activityChart').get(0).getContext('2d')
  var activityChart = new Chart(activityChartCanvas, {
    type: 'doughnut',
    data: {
      labels: [
        'Material',
        'Revisi', 
        'Proyek',
        'YUOM'
      ],
      datasets: [
        {
          data: [25, 5, 8, 12],
          backgroundColor: ['#007bff', '#dc3545', '#28a745', '#ffc107'],
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      legend: {
        position: 'bottom',
      }
    }
  })

  // Report Chart
  var reportChartCanvas = $('#reportChart').get(0).getContext('2d')
  var reportChart = new Chart(reportChartCanvas, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
      datasets: [
        {
          label: 'Transaksi',
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          data: [28, 48, 40, 19, 86, 27]
        },
        {
          label: 'Proyek',
          backgroundColor: 'rgba(220,220,220,0.9)',
          borderColor: 'rgba(220,220,220,0.8)',
          data: [65, 59, 80, 81, 56, 55]
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  })
})
</script>
@endpush
@endsection