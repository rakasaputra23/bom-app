<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password - BOM System</title>
  
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

  <!-- Custom CSS - Same as login page -->
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      height: 100vh;
      width: 100vw;
      margin: 0;
      padding: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f4f6f9;
      overflow-x: hidden;
    }

    .login-page {
      min-height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: row;
      align-items: stretch;
      background-color: #f4f6f9;
    }

    .login-left {
      flex: 2.2;
      min-width: 65%;
      width: 65%;
      min-height: 100vh;
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
      background: rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .illustration-content {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: 500px;
      padding: 2rem;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .logo-illustration-large {
      position: fixed !important;
      top: 15px !important;
      right: calc(35% + 15px) !important;
      z-index: 9999 !important;
      background: rgba(255, 255, 255, 0.15) !important;
      backdrop-filter: blur(10px) !important;
      border-radius: 12px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      box-shadow: 0 6px 20px rgba(0,0,0,0.25) !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      padding: 8px 12px !important;
      margin: 0 !important;
      transform: none !important;
      width: auto !important;
      height: auto !important;
      min-width: 70px !important;
      min-height: 45px !important;
    }

    .logo-illustration-large img {
      height: 42px !important;
      width: auto !important;
      max-width: none !important;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3)) !important;
      margin: 0 !important;
      display: block !important;
      object-fit: contain !important;
    }

    .welcome-text {
      color: white;
      font-size: clamp(1.5rem, 4vw, 2.2rem);
      font-weight: 600;
      margin-bottom: 0.5rem;
      margin-top: 1.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .welcome-subtitle {
      color: white;
      font-size: clamp(1rem, 2.5vw, 1.2rem);
      opacity: 0.9;
      margin-bottom: 1rem;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
      font-weight: 500;
    }

    .welcome-description {
      color: white;
      font-size: clamp(0.85rem, 2vw, 1rem);
      opacity: 0.8;
      line-height: 1.6;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .login-right {
      flex: 1;
      min-width: 35%;
      width: 35%;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      background: #ffffff;
      box-sizing: border-box;
      position: relative;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
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
      max-width: 100%;
    }

    .card-header {
      background: linear-gradient(135deg, #dc3545, #b02a37);
      border: none;
      text-align: center;
      padding: 2rem 1.5rem 1.8rem;
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
      height: 55px;
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
      font-size: clamp(1.2rem, 3vw, 1.6rem);
      font-weight: 700;
      letter-spacing: 2px;
    }

    .brand-system {
      font-family: 'Pacifico', cursive;
      font-size: clamp(0.75rem, 2vw, 0.9rem);
      font-weight: 400;
      opacity: 0.9;
    }

    .card-body {
      padding: 1.8rem 1.5rem 1.2rem;
    }

    .login-box-msg {
      margin: 0 0 1.5rem 0;
      text-align: center;
      color: #6c757d;
      font-weight: 400;
      font-size: 0.9rem;
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
      height: calc(2.5rem + 2px);
      font-size: clamp(0.85rem, 2vw, 0.9rem);
    }

    .form-control:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .input-group-text {
      background-color: #e9ecef;
      border-color: #ced4da;
      color: #495057;
      border-radius: 0.25rem;
      width: 45px;
      justify-content: center;
      font-size: clamp(0.8rem, 1.5vw, 0.9rem);
    }

    .input-group:focus-within .input-group-text {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
    }

    .input-group:focus-within .form-control {
      border-color: #dc3545;
    }

    .btn-primary {
      background-color: #dc3545;
      border-color: #dc3545;
      border-radius: 0.25rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0.8rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      font-size: clamp(0.85rem, 2vw, 0.9rem);
    }

    .btn-primary:hover {
      background-color: #b02a37;
      border-color: #b02a37;
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      border-radius: 0.25rem;
      font-weight: 500;
      padding: 0.6rem 1rem;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      font-size: clamp(0.8rem, 1.8vw, 0.85rem);
    }

    .btn-secondary:hover {
      background-color: #545b62;
      border-color: #545b62;
      color: white;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
    }

    .alert-danger {
      background: linear-gradient(135deg, #dc3545, #bd2130);
      color: white;
    }

    .alert-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .alert-info {
      background: linear-gradient(135deg, #17a2b8, #117a8b);
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
      font-size: 0.8rem;
      font-weight: 500;
    }

    .is-invalid {
      border-color: #dc3545;
    }

    .is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

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

    .card-footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      text-align: center;
      font-size: 0.75rem;
      color: #6c757d;
    }

    /* Responsive styles - same as login */
    @media (max-width: 1400px) {
      .login-left {
        min-width: 62%;
        width: 62%;
      }
      
      .login-right {
        min-width: 38%;
        width: 38%;
      }

      .logo-illustration-large {
        right: calc(38% + 18px) !important;
      }
    }

    @media (max-width: 1200px) {
      .login-left {
        min-width: 60%;
        width: 60%;
      }
      
      .login-right {
        min-width: 40%;
        width: 40%;
      }

      .logo-illustration-large {
        right: calc(40% + 16px) !important;
      }
    }

    @media (max-width: 992px) {
      .login-left {
        min-width: 55%;
        width: 55%;
      }
      
      .login-right {
        min-width: 45%;
        width: 45%;
      }

      .logo-illustration-large {
        right: calc(45% + 15px) !important;
      }
    }

    @media (max-width: 768px) {
      .login-page {
        flex-direction: column;
      }
      
      .login-left {
        min-height: 40vh;
        min-width: 100%;
        width: 100%;
        flex: none;
      }
      
      .login-right {
        flex: 1;
        min-width: 100%;
        width: 100%;
        min-height: 60vh;
        padding: 1.5rem;
      }

      .logo-illustration-large {
        position: absolute !important;
        top: 12px !important;
        right: 12px !important;
      }
    }

    @media (max-width: 576px) {
      .login-left {
        min-height: 35vh;
      }
      
      .login-right {
        min-height: 65vh;
        padding: 1rem;
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

    .logo-illustration-large {
      animation: fadeInRight 1s ease-out 0.3s both;
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-page">
  <!-- Left Side - Illustration -->
  <div class="login-left">
    <div class="logo-illustration-large">
      <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA">
    </div>
    
    <div class="illustration-content">
      <div class="welcome-text">Reset Your Password</div>
      <div class="welcome-subtitle">BOM System Recovery</div>
      <div class="welcome-description">
        Enter your NIP and we'll send you a link to reset your password securely.
      </div>
    </div>
  </div>

  <!-- Right Side - Reset Password Form -->
  <div class="login-right">
    <div class="login-box">
      <div class="card">
        <div class="card-header">
          <div class="login-logo">
            <a href="{{ route('login') }}" style="text-decoration: none;">
              <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA">
              <div class="brand-text">
                <div class="brand-bom">RESET</div>
                <div class="brand-system">Password</div>
              </div>
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <p class="login-box-msg">Enter your NIP to receive reset instructions</p>

          <!-- Messages -->
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

          @if(session('status'))
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h6><i class="icon fas fa-check"></i> Email Sent!</h6>
              {{ session('status') }}
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h6><i class="icon fas fa-exclamation-triangle"></i> Failed!</h6>
              {{ session('error') }}
            </div>
          @endif

          <!-- Reset Password Form -->
          <form action="{{ route('password.email') }}" method="POST" id="resetForm">
            @csrf
            
            <!-- NIP Field -->
            <div class="input-group mb-3">
              <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                     placeholder="Enter your NIP" value="{{ old('nip') }}" required autofocus>
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

            <!-- Info Box -->
            <div class="alert alert-info">
              <h6><i class="icon fas fa-info"></i> Information</h6>
              <small>
                We'll send a secure reset link to the email address associated with your NIP. 
                The link will expire in 60 minutes for security purposes.
              </small>
            </div>

            <!-- Submit Button -->
            <div class="row mb-3">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block" id="resetBtn">
                  <i class="fas fa-paper-plane mr-2"></i>SEND RESET LINK
                </button>
              </div>
            </div>

            <!-- Back to Login -->
            <div class="row">
              <div class="col-12 text-center">
                <a href="{{ route('login') }}" class="btn btn-secondary">
                  <i class="fas fa-arrow-left mr-2"></i>Back to Login
                </a>
              </div>
            </div>
          </form>

        </div>
        
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

<script>
$(document).ready(function() {
  // Focus on NIP field
  setTimeout(function() {
    $('input[name="nip"]').focus();
  }, 500);
  
  // Form submission handling
  $('#resetForm').on('submit', function(e) {
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    let hasError = false;
    
    const nipValue = $('input[name="nip"]').val().trim();
    if (nipValue === '') {
      showFieldError('nip', 'NIP tidak boleh kosong');
      hasError = true;
    } else if (nipValue.length < 5) {
      showFieldError('nip', 'NIP minimal 5 karakter');
      hasError = true;
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const resetBtn = $('#resetBtn');
    resetBtn.addClass('btn-loading').prop('disabled', true);
    resetBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>SENDING...');
  });
  
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.input-group').after(`<div class="invalid-feedback d-block"><strong>${message}</strong></div>`);
  }
  
  // Clear error on input
  $('input[name="nip"]').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.input-group').next('.invalid-feedback').remove();
    
    const resetBtn = $('#resetBtn');
    if (resetBtn.hasClass('btn-loading')) {
      resetBtn.removeClass('btn-loading').prop('disabled', false);
      resetBtn.html('<i class="fas fa-paper-plane mr-2"></i>SEND RESET LINK');
    }
  });
  
  // Auto-hide alerts
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 8000);
});
</script>

</body>
</html>