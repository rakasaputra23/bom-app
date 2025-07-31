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

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Montserrat', sans-serif;
    }

    .login-page {
      background-image: url('{{ asset("dist/img/login-bg1.jpg") }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-page::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 400px;
      padding: 2rem;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 25px;
      padding: 0.8rem 1.2rem;
      font-size: 1rem;
      color: #333;
      width: 100%;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 1);
      border-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
      outline: none;
    }

    .form-control::placeholder {
      color: #999;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .password-toggle:hover {
      color: #333;
    }

    .password-field {
      position: relative;
    }

    .btn-login {
      background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
      border: none;
      border-radius: 25px;
      padding: 0.8rem 2rem;
      font-size: 1rem;
      font-weight: 600;
      color: #333;
      width: 100%;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 154, 158, 0.4);
    }

    .login-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 1.5rem 0;
    }

    .remember-me {
      display: flex;
      align-items: center;
      color: white;
      font-size: 0.9rem;
    }

    .remember-me input {
      margin-right: 0.5rem;
      accent-color: #ff9a9e;
    }

    .forgot-password {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }

    .forgot-password:hover {
      color: white;
      text-decoration: underline;
    }

    .alert {
      background: rgba(220, 53, 69, 0.9);
      color: white;
      border: 1px solid rgba(220, 53, 69, 0.3);
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      backdrop-filter: blur(10px);
    }

    .alert-success {
      background: rgba(40, 167, 69, 0.9);
      border-color: rgba(40, 167, 69, 0.3);
    }

    .alert .close {
      color: white;
      opacity: 0.8;
    }

    .alert .close:hover {
      opacity: 1;
    }

    .invalid-feedback {
      color: #ff6b6b;
      font-size: 0.875rem;
      margin-top: 0.5rem;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .is-invalid {
      border-color: #ff6b6b !important;
      background: rgba(255, 107, 107, 0.1) !important;
    }

    /* Logo styling */
    .login-logo {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-logo img {
      height: 60px;
      margin-bottom: 0.5rem;
      filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
    }

    .brand-text {
      color: white;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .brand-bom {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem;
      font-weight: 700;
    }

    .brand-system {
      font-family: 'Pacifico', cursive;
      font-size: 1.2rem;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
      .login-container {
        padding: 1rem;
        max-width: 350px;
      }
      
      .login-box {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-container">
  <!-- Logo -->
  <div class="login-logo">
    <a href="{{ url('/') }}" style="text-decoration: none;">
      <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA"><br>
      <span class="brand-text">
        <span class="brand-bom">BOM</span>
        <span class="brand-system">System</span>
      </span>
    </a>
  </div>

  <!-- Login Box -->
  <div class="login-box">
    <!-- Error/Success Messages -->
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Login Gagal!</h5>
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
        {{ session('success') }}
      </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" id="loginForm">
      @csrf
      
      <!-- NIP Field -->
      <div class="form-group">
        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
               placeholder="NIP" value="{{ old('nip') }}" required autofocus>
        @error('nip')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <!-- Password Field -->
      <div class="form-group">
        <div class="password-field">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                 placeholder="Password" required id="passwordField">
          <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        </div>
        @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <!-- Remember Me & Forgot Password -->
      <div class="login-options">
        <label class="remember-me">
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
          Remember Me
        </label>
        <a href="{{ route('password.request') }}" class="forgot-password">
          Forgot Password
        </a>
      </div>

      <!-- Login Button -->
      <button type="submit" class="btn-login">
        SIGN IN
      </button>
    </form>
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
  $('.login-container').hide().fadeIn(800);
  
  // Focus on NIP field when page loads
  $('input[name="nip"]').focus();
  
  // Password toggle functionality
  $('#togglePassword').on('click', function() {
    const passwordField = $('#passwordField');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    $(this).toggleClass('fa-eye fa-eye-slash');
  });
  
  // Form submission handling with basic validation
  $('#loginForm').on('submit', function(e) {
    // Reset previous errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    let hasError = false;
    
    // Validate NIP
    const nipValue = $('input[name="nip"]').val().trim();
    if (nipValue === '') {
      showFieldError('nip', 'NIP tidak boleh kosong');
      hasError = true;
    }
    
    // Validate Password
    const passwordValue = $('input[name="password"]').val().trim();
    if (passwordValue === '') {
      showFieldError('password', 'Password tidak boleh kosong');
      hasError = true;
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    $('.btn-login').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
  });
  
  // Function to show field error
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.form-group').append(`<span class="invalid-feedback d-block" role="alert"><strong>${message}</strong></span>`);
  }
  
  // Clear error on input
  $('input[name="nip"], input[name="password"]').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('.invalid-feedback').remove();
  });
});
</script>
</body>
</html>