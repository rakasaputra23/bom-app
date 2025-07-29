<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - BOM System</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('dist/img/favicon.ico') }}" type="image/x-icon">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  
  <!-- AdminLTE 3 -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  
  <!-- Custom CSS -->
  <style>
    .login-page {
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset("dist/img/login-bg.jpeg") }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      backdrop-filter: blur(5px);
    }
    .login-box {
      width: 400px;
    }
    .login-logo {
      margin-bottom: 1rem;
    }
    .login-logo a {
      color: #fff;
      font-size: 2.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .login-card {
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      border: none;
      background-color: rgba(255,255,255,0.95);
    }
    .login-card-body {
      padding: 2rem;
      border-radius: 10px;
    }
    .input-group-text {
      background-color: #e9ecef;
      border-color: #ced4da;
    }
    .btn-login {
      background-color: #007bff;
      border-color: #007bff;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .btn-login:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }
    .forgot-password {
      color: #6c757d;
    }
    .forgot-password:hover {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- Login Logo -->
  <div class="login-logo">
    <a href="{{ url('/') }}">
      <img src="{{ asset('dist/img/logo-login.png') }}" alt="BOM Logo" style="height: 80px; margin-bottom: 10px;"><br>
      <b>BOM</b> System
    </a>
  </div>

  <!-- Login Card -->
  <div class="card login-card">
    <div class="card-body login-card-body">
      <p class="login-box-msg text-muted">Silakan login untuk mengakses sistem</p>

      <!-- Login Form -->
      <form action="{{ route('login') }}" method="POST" id="loginForm">
        @csrf
        
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- NIP Field -->
        <div class="input-group mb-3">
          <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                 placeholder="Masukkan NIP" value="{{ old('nip') }}" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('nip')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <!-- Password Field -->
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                 placeholder="Masukkan Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember">Ingat Saya</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-login">
              <i class="fas fa-sign-in-alt mr-1"></i> Login
            </button>
          </div>
        </div>
      </form>

      <!-- Forgot Password Link -->
      <p class="mb-1 mt-3 text-center">
        <a href="{{ route('password.request') }}" class="forgot-password">
          <i class="fas fa-key mr-1"></i> Lupa Password?
        </a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Custom JS -->
<script>
$(document).ready(function() {
  // Add animation to login box
  $('.login-box').hide().fadeIn(500);
  
  // Focus on NIP field when page loads
  $('input[name="nip"]').focus();
  
  // Form submission handling
  $('#loginForm').on('submit', function() {
    $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
  });
  
  // Show/hide password toggle
  $('.input-group-text').on('click', function() {
    const input = $(this).closest('.input-group').find('input');
    if (input.attr('type') === 'password') {
      input.attr('type', 'text');
      $(this).find('i').removeClass('fa-lock').addClass('fa-unlock');
    } else {
      input.attr('type', 'password');
      $(this).find('i').removeClass('fa-unlock').addClass('fa-lock');
    }
  });
});
</script>
</body>
</html>