<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    @if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('dashboard')))
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('dashboard') }}" class="nav-link">
          <i class="fas fa-home mr-1"></i> Home
        </a>
      </li>
    @endif
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @php
      $user = Auth::user();
    @endphp

    @if($user)
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" 
               class="img-circle elevation-2 mr-1" 
               alt="User Image" 
               style="width: 25px; height: 25px;">
          {{ $user->nama }}
          @if($user->group)
            <small class="text-muted">({{ $user->group->nama }})</small>
          @endif
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          @if(Route::has('profile'))
            <a class="dropdown-item" href="{{ route('profile') }}">
              <i class="fas fa-user mr-2"></i> Profil
            </a>
          @endif

          @if(Route::has('profile.edit') && ($user->isSuperAdmin() || $user->hasPermission('profile.edit')))
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="fas fa-edit mr-2"></i> Edit Profil
            </a>
          @endif

          <div class="dropdown-divider"></div>

          <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    @endif
  </ul>
</nav>
