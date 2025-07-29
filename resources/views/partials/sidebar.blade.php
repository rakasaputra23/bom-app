<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  @php
    // PERBAIKAN: Brand logo juga harus cek permission atau redirect ke halaman yang bisa diakses
    $logoRoute = 'dashboard'; // Default
    if (Auth::check()) {
      if (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('dashboard')) {
        $logoRoute = 'dashboard';
      } else {
        $firstAccessible = Auth::user()->getFirstAccessibleRoute();
        $logoRoute = $firstAccessible ?: 'profile'; // Fallback ke profile jika tidak ada akses apapun
      }
    }
  @endphp
  
  <a href="{{ route($logoRoute) }}" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" 
         alt="BOM Logo" 
         class="brand-image img-circle elevation-3" 
         style="opacity: .8">
    <span class="brand-text font-weight-light">BOM System</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" 
             class="img-circle elevation-2" 
             alt="User Image">
      </div>
      <div class="info">
        @auth
        <a href="{{ route('profile') }}" class="d-block text-truncate" title="{{ Auth::user()->nama }}">
          {{ Str::limit(Auth::user()->nama ?? 'User', 15) }}
        </a>
        @if(Auth::user()->group)
          <small class="text-muted">{{ Auth::user()->group->nama }}</small>
        @endif
        @endauth
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- PERBAIKAN UTAMA: Dashboard - Hanya tampil jika user punya permission -->
        @if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('dashboard')))
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" 
             class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        @endif

        <!-- BOM Management -->
        @php
          $canAccessBOM = Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->canAccessModule('bom'));
        @endphp
        
        @if($canAccessBOM)
        <li class="nav-item {{ request()->routeIs('bom.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('bom.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              BOM Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('bom.index'))
            <li class="nav-item">
              <a href="{{ route('bom.index') }}" 
                 class="nav-link {{ request()->routeIs('bom.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View BOM</p>
              </a>
            </li>
            @endif
            
            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('bom.create'))
            <li class="nav-item">
              <a href="{{ route('bom.create') }}" 
                 class="nav-link {{ request()->routeIs('bom.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Create BOM</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- Master Data -->
        @php
          $canAccessMasterData = Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->canAccessModule('master_data'));
        @endphp
        
        @if($canAccessMasterData)
        <li class="nav-item {{ request()->routeIs('master.*') || request()->routeIs('kode-material.*') || request()->routeIs('revisi.*') || request()->routeIs('proyek.*') || request()->routeIs('uom.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('master.*') || request()->routeIs('kode-material.*') || request()->routeIs('revisi.*') || request()->routeIs('proyek.*') || request()->routeIs('uom.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Master Data
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('kode-material.index'))
            <li class="nav-item">
              <a href="{{ route('kode-material.index') }}" 
                 class="nav-link {{ request()->routeIs('kode-material.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Kode Material</p>
              </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('revisi.index'))
            <li class="nav-item">
              <a href="{{ route('revisi.index') }}" 
                 class="nav-link {{ request()->routeIs('revisi.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Revisi</p>
              </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('proyek.index'))
            <li class="nav-item">
              <a href="{{ route('proyek.index') }}" 
                 class="nav-link {{ request()->routeIs('proyek.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Proyek</p>
              </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('uom.index'))
            <li class="nav-item">
              <a href="{{ route('uom.index') }}" 
                 class="nav-link {{ request()->routeIs('uom.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>UOM</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- User Management -->
        @php
          $canAccessUserMgmt = Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->canAccessModule('user_management'));
        @endphp
        
        @if($canAccessUserMgmt)
        <li class="nav-item {{ request()->routeIs('user*', 'permissions.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('user*', 'permissions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              User Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user'))
            <li class="nav-item">
              <a href="{{ route('user') }}" 
                 class="nav-link {{ request()->routeIs('user') && !request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group'))
            <li class="nav-item">
              <a href="{{ route('user.group') }}" 
                 class="nav-link {{ request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>User Groups</p>
              </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.index'))
            <li class="nav-item">
              <a href="{{ route('permissions.index') }}" 
                 class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Permissions</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif


        <!-- System Info (for admins only) -->
        @if(Auth::check() && Auth::user()->isSuperAdmin())
        <li class="nav-header">SYSTEM</li>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="showSystemInfo()">
            <i class="nav-icon fas fa-info-circle"></i>
            <p>System Info</p>
          </a>
        </li>
        @endif

        <!-- PERBAIKAN: Pesan jika user tidak punya permission apapun -->
        @php
          $hasAnyAccess = Auth::check() && (
            Auth::user()->isSuperAdmin() || 
            Auth::user()->hasPermission('dashboard') ||
            Auth::user()->canAccessModule('bom') ||
            Auth::user()->canAccessModule('master_data') ||
            Auth::user()->canAccessModule('user_management')
          );
        @endphp

        @if(Auth::check() && !$hasAnyAccess)
        <li class="nav-header">ACCESS RESTRICTED</li>
        <li class="nav-item">
          <div class="nav-link text-warning">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p class="text-sm">
              No menu access<br>
              <small class="text-muted">Contact admin</small>
            </p>
          </div>
        </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>

<script>
// Function untuk show system info (hanya untuk superadmin)
function showSystemInfo() {
    @if(Auth::check() && Auth::user()->isSuperAdmin())
    // Menggunakan alert biasa jika SweetAlert2 belum diinstall
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'System Information',
            html: `
                <div class="text-left">
                    <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                    <p><strong>PHP Version:</strong> {{ phpversion() }}</p>
                    <p><strong>Environment:</strong> {{ config('app.env') }}</p>
                    <p><strong>Debug Mode:</strong> {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
                    <p><strong>Current User:</strong> {{ Auth::user()->nama ?? 'Unknown' }}</p>
                    <p><strong>User Group:</strong> {{ Auth::user()->group->nama ?? 'No Group' }}</p>
                    <p><strong>Available Permissions:</strong> {{ Auth::user()->getAllPermissions()->count() }}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    } else {
        alert(`System Information:
        
Laravel Version: {{ app()->version() }}
PHP Version: {{ phpversion() }}
Environment: {{ config('app.env') }}
Debug Mode: {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
Current User: {{ Auth::user()->nama ?? 'Unknown' }}
User Group: {{ Auth::user()->group->nama ?? 'No Group' }}
Available Permissions: {{ Auth::user()->getAllPermissions()->count() }}`);
    }
    @else
    alert('Access denied!');
    @endif
}
</script>