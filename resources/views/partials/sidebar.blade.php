<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">BOM App</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="{{ route('profile') }}" class="d-block">Admin</a>

      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Transaction<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Transaksi Penjualan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan</p>
              </a>
            </li>
          </ul>
        
        <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>
            Data Master
            <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
            <a href="{{ route('kode.material') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Kode Material</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('revisi') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Revisi</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('proyek') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Proyek</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('yuom') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>YUOM</p>
            </a>
            </li>
        </ul>
        </li>
          </ul>
        </li>
        </li>
      </ul>
    </nav>
  </div>
</aside>