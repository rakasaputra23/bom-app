<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('dashboard') }}" class="nav-link">
        <i class="fas fa-home mr-1"></i> Home
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- User Account Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
         data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" 
             class="img-circle elevation-2 mr-1" 
             alt="User Image" 
             style="width: 25px; height: 25px;">
        {{ Auth::user()->nama }}
        @if(Auth::user()->group)
          <small class="text-muted">({{ Auth::user()->group->nama }})</small>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('profile') }}">
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <a class="dropdown-item" href="{{ route('profile.edit') }}">
          <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>