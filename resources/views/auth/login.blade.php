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
  
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  
  <!-- AdminLTE 3 -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&family=Pacifico&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body, html {
      height: 100vh;
      width: 100vw;
      margin: 0;
      padding: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f4f6f9;
      overflow: hidden;
    }

    .login-page {
      height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: row;
      align-items: stretch;
      background-color: #f4f6f9;
    }

    /* Left Side - Illustration */
    .login-left {
      flex: 2;
      min-width: 65%;
      width: 65%;
      height: 100vh;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      box-sizing: border-box;
      background-image: url('{{ asset("dist/img/login-bg.png") }}');
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
    }

    .login-left::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.2); /* Overlay lebih ringan */
      z-index: 1;
    }

    .illustration-content {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: 400px;
      padding: 2rem;
    }

    .logo-illustration-large {
      width: 200px;
      height: 200px;
      margin: 0 auto;
      background: transparent; /* Hapus background putih */
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      /* Hapus box-shadow dan backdrop-filter */
    }

    .logo-illustration-large img {
      height: 150px;
      width: auto;
      max-width: 90%;
      filter: none;
      /* Logo PNG transparan akan terlihat natural */
    }

    .welcome-text {
      color: white;
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      margin-top: 1.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .welcome-subtitle {
      color: white;
      font-size: 1rem;
      opacity: 0.9;
      margin-bottom: 1rem;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .welcome-description {
      color: white;
      font-size: 0.9rem;
      opacity: 0.8;
      line-height: 1.5;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    /* Right Side - Login Form */
    .login-right {
      flex: 1;
      min-width: 35%;
      width: 35%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background: #ffffff; /* Background putih sepenuhnya */
      box-sizing: border-box;
    }

    .login-box {
      width: 100%;
      max-width: 350px;
      animation: fadeInRight 0.8s ease-out;
    }

    .card {
      background: white;
      backdrop-filter: blur(15px);
      border: none;
      border-radius: 15px;
      box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
      overflow: hidden;
      width: 100%;
      max-width: 400px;
    }

    .card-header {
      background: linear-gradient(135deg, #007bff, #0056b3);
      border: none;
      text-align: center;
      padding: 2.5rem 1.5rem 2rem;
      position: relative;
      border-radius: 15px 15px 0 0;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><path d="M0,0 C150,100 350,0 500,50 C650,100 850,0 1000,50 L1000,100 L0,100 Z"/></svg>');
      background-size: cover;
    }

    .login-logo {
      position: relative;
      z-index: 1;
      margin-bottom: 0;
    }

    .login-logo img {
      height: 60px;
      margin-bottom: 0.75rem;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
      max-width: 100%;
    }

    .brand-text {
      color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      display: block;
    }

    .brand-bom {
      font-family: 'Source Sans Pro', sans-serif;
      font-size: 1.75rem;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .brand-system {
      font-family: 'Pacifico', cursive;
      font-size: 1rem;
      font-weight: 400;
      opacity: 0.9;
    }

    .card-body {
      padding: 2rem 1.5rem 1.5rem;
    }

    .login-box-msg {
      margin: 0 0 1.5rem 0;
      text-align: center;
      color: #6c757d;
      font-weight: 400;
      font-size: 0.95rem;
    }

    .input-group {
      margin-bottom: 1rem;
      position: relative;
    }

    .input-group:last-of-type {
      margin-bottom: 1.5rem;
    }

    .form-control {
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      background-color: #fff;
      transition: all 0.15s ease-in-out;
      height: calc(2.25rem + 2px);
      font-size: 0.9rem;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group-text {
      background-color: #e9ecef;
      border-color: #ced4da;
      color: #495057;
      border-radius: 0.25rem;
      width: 45px;
      justify-content: center;
    }

    .password-toggle-btn {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .password-toggle-btn:hover {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      border-radius: 0.25rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0.75rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .icheck-primary {
      margin-right: 0.5rem;
    }

    .forgot-password {
      color: #007bff;
      text-decoration: none;
      font-size: 0.875rem;
      transition: color 0.3s ease;
      font-weight: 500;
    }

    .forgot-password:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    /* Alert Styles */
    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }

    .alert-danger {
      background: linear-gradient(135deg, #dc3545, #bd2130);
      color: white;
    }

    .alert-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .alert .close {
      color: white;
      text-shadow: none;
      opacity: 0.8;
    }

    .alert .close:hover {
      opacity: 1;
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .is-invalid {
      border-color: #dc3545;
    }

    .is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Loading Animation */
    .btn-loading {
      position: relative;
      color: transparent;
    }

    .btn-loading::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid #ffffff;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .login-page {
        flex-direction: column;
      }
      
      .login-left {
        min-height: 200px;
        min-width: auto;
        width: 100%;
        flex: none;
      }
      
      .login-right {
        flex: 1;
        min-width: auto;
        width: 100%;
        height: auto;
        padding: 1.5rem;
      }
      
      .illustration-content {
        padding: 1rem;
        max-width: 300px;
      }
      
      .logo-illustration-large {
        width: 100px;
        height: 100px;
        margin-bottom: 1rem;
      }
      
      .logo-illustration-large img {
        height: 60px;
      }
      
      .welcome-text {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .login-box {
        width: 95%;
        max-width: 350px;
        margin: 1rem;
      }
      
      .card-body, .card-header {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .brand-bom {
        font-size: 1.5rem;
      }
      
      .login-left {
        min-height: 150px;
      }
      
      .logo-illustration-large {
        width: 80px;
        height: 80px;
      }
      
      .logo-illustration-large img {
        height: 50px;
      }
    }

    /* Animation */
    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .illustration-content {
      animation: fadeInLeft 0.8s ease-out;
    }

    /* Additional AdminLTE-like enhancements */
    .form-control::placeholder {
      color: #adb5bd;
      opacity: 1;
    }

    .input-group-text {
      font-size: 0.9rem;
    }

    .card-footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      text-align: center;
      font-size: 0.85rem;
      color: #6c757d;
    }

    /* Additional focus styles */
    .input-group:focus-within .input-group-text {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .input-group:focus-within .form-control {
      border-color: #007bff;
    }

    /* Ripple effect */
    .btn-primary {
      position: relative;
      overflow: hidden;
    }

    .ripple {
      position: absolute;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      animation: ripple 0.6s linear;
      pointer-events: none;
    }

    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-page">
  <!-- Left Side - Illustration -->
  <div class="login-left">
    <div class="illustration-content">
      <div class="logo-illustration-large">
        <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA">
      </div>
      <div class="welcome-text">Welcome to BOM System</div>
      <div class="welcome-subtitle">PT. INKA Multi Solusi</div>
      <div class="welcome-description">
        Manage your Bill of Materials efficiently and securely with our comprehensive system solution.
      </div>
    </div>
  </div>
    

  <!-- Right Side - Login Form -->
  <div class="login-right">
    <div class="login-box">
      <div class="card">
        <div class="card-header">
          <div class="login-logo">
            <a href="{{ url('/') }}" style="text-decoration: none;">
              <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA">
              <div class="brand-text">
                <div class="brand-bom">BOM</div>
                <div class="brand-system">System</div>
              </div>
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <p class="login-box-msg">Sign in to start your session</p>

          <!-- Error/Success Messages -->
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h6><i class="icon fas fa-ban"></i> Error!</h6>
              <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h6><i class="icon fas fa-ban"></i> Login Gagal!</h6>
              {{ session('error') }}
            </div>
          @endif

          @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h6><i class="icon fas fa-check"></i> Berhasil!</h6>
              {{ session('success') }}
            </div>
          @endif

          <!-- Login Form -->
          <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            
            <!-- NIP Field -->
            <div class="input-group mb-3">
              <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                     placeholder="NIP" value="{{ old('nip') }}" required autofocus>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            @error('nip')
              <div class="invalid-feedback d-block mb-2">
                <strong>{{ $message }}</strong>
              </div>
            @enderror

            <!-- Password Field -->
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                     placeholder="Password" required id="passwordField">
              <div class="input-group-append">
                <div class="input-group-text password-toggle-btn" id="togglePassword">
                  <span class="fas fa-eye"></span>
                </div>
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            @error('password')
              <div class="invalid-feedback d-block mb-2">
                <strong>{{ $message }}</strong>
              </div>
            @enderror

            <div class="row mb-3">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="col-4">
                <a href="{{ route('password.request') }}" class="forgot-password text-right d-block">
                  Forgot?
                </a>
              </div>
            </div>

            <!-- Login Button -->
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                  SIGN IN
                </button>
              </div>
            </div>
          </form>

        </div>
        
        <!-- Optional: Card Footer -->
        <div class="card-footer text-muted">
          <small>&copy; {{ date('Y') }} BOM System. All rights reserved.</small>
        </div>
      </div>
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
  // Focus on NIP field when page loads
  setTimeout(function() {
    $('input[name="nip"]').focus();
  }, 500);
  
  // Password toggle functionality
  $('#togglePassword').on('click', function() {
    const passwordField = $('#passwordField');
    const icon = $(this).find('span');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    icon.toggleClass('fa-eye fa-eye-slash');
  });
  
  // Form submission handling with validation
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
    } else if (nipValue.length < 5) {
      showFieldError('nip', 'NIP minimal 5 karakter');
      hasError = true;
    }
    
    // Validate Password
    const passwordValue = $('input[name="password"]').val().trim();
    if (passwordValue === '') {
      showFieldError('password', 'Password tidak boleh kosong');
      hasError = true;
    } else if (passwordValue.length < 6) {
      showFieldError('password', 'Password minimal 6 karakter');
      hasError = true;
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const loginBtn = $('#loginBtn');
    loginBtn.addClass('btn-loading').prop('disabled', true);
    loginBtn.text('Processing...');
  });
  
  // Function to show field error
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.input-group').after(`<div class="invalid-feedback d-block"><strong>${message}</strong></div>`);
  }
  
  // Clear error on input
  $('input[name="nip"], input[name="password"]').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.input-group').next('.invalid-feedback').remove();
    
    // Reset login button if it was in loading state
    const loginBtn = $('#loginBtn');
    if (loginBtn.hasClass('btn-loading')) {
      loginBtn.removeClass('btn-loading').prop('disabled', false);
      loginBtn.text('SIGN IN');
    }
  });
  
  // Auto-hide alerts after 5 seconds
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 5000);
  
  // Add ripple effect to button
  $('.btn-primary').on('click', function(e) {
    const ripple = $('<span class="ripple"></span>');
    $(this).append(ripple);
    
    setTimeout(() => {
      ripple.remove();
    }, 600);
  });
});

// Add some interactive enhancements
$(document).ready(function() {
  // Smooth form interactions
  $('.form-control').on('focus', function() {
    $(this).closest('.input-group').addClass('focused');
  });
  
  $('.form-control').on('blur', function() {
    $(this).closest('.input-group').removeClass('focused');
  });
  
  // Enhanced validation feedback
  $('.form-control').on('keyup', function() {
    if ($(this).hasClass('is-invalid') && $(this).val().length > 0) {
      $(this).removeClass('is-invalid');
      $(this).closest('.input-group').next('.invalid-feedback').fadeOut();
    }
  });
});
</script>

</body>
</html>