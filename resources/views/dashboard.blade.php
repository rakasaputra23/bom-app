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
      
      <!-- Coming Soon Overlay -->
      <div class="coming-soon-overlay">
        <div class="coming-soon-text">Coming Soon</div>
        <div class="progress-text">On Progress</div>
      </div>
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
      
      <!-- Coming Soon Overlay -->
      <div class="coming-soon-overlay">
        <div class="coming-soon-text">Coming Soon</div>
        <div class="progress-text">On Progress</div>
      </div>
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
      
      <!-- Coming Soon Overlay -->
      <div class="coming-soon-overlay">
        <div class="coming-soon-text">Coming Soon</div>
        <div class="progress-text">On Progress</div>
      </div>
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
      
      <!-- Coming Soon Overlay -->
      <div class="coming-soon-overlay">
        <div class="coming-soon-text">Coming Soon</div>
        <div class="progress-text">On Progress</div>
      </div>
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
      
      <!-- Disabled Overlay -->
      <div class="disabled-overlay">
        <div class="disabled-text">Coming Soon</div>
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
      
      <!-- Disabled Overlay -->
      <div class="disabled-overlay">
        <div class="disabled-text">Coming Soon</div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .coming-soon-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 0.25rem;
    z-index: 10;
  }
  
  .coming-soon-text {
    font-size: 1.2rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
  }
  
  .progress-text {
    font-size: 0.9rem;
    color: #007bff;
    font-weight: 500;
  }
  
  .small-box {
    position: relative;
    overflow: hidden;
  }
  
  .small-box .icon {
    z-index: 0;
  }
  
  .small-box .inner {
    position: relative;
    z-index: 5;
  }
  
  .small-box .small-box-footer {
    position: relative;
    z-index: 10;
    background: rgba(0, 0, 0, 0.1);
  }
  
  .card-tab-disabled {
    opacity: 0.7;
    pointer-events: none;
  }
  
  .disabled-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    border-radius: 0.5rem;
  }
  
  .disabled-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6c757d;
    transform: rotate(-5deg);
    background: rgba(255, 255, 255, 0.9);
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }
</style>
@endpush

@push('scripts')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
  
  // Prevent clicking on disabled elements
  $('.coming-soon-overlay, .disabled-overlay').closest('.small-box, .card').on('click', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Fitur Dalam Pengembangan',
      text: 'Fitur ini sedang dalam pengembangan (Coming Soon)',
      icon: 'info',
      confirmButtonText: 'Mengerti'
    });
    return false;
  });
  
  // Change cursor for disabled elements
  $('.coming-soon-overlay, .disabled-overlay').closest('.small-box, .card').css('cursor', 'not-allowed');
  $('.coming-soon-overlay, .disabled-overlay').closest('.small-box, .card').find('a').css('pointer-events', 'none');
})
</script>
@endpush
@endsection