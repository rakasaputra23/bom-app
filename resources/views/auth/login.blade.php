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

    /* Left Side - Illustration - Responsif */
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

    /* FIXED: Logo di Left Side - BENAR-BENAR Independent dari semua konten */
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

    /* Enhanced Responsive Design - Logo positioning untuk SEMUA ukuran layar */
    
    /* Large Desktop */
    @media (min-width: 1401px) {
      .logo-illustration-large {
        top: 20px !important;
        right: calc(35% + 20px) !important;
        padding: 10px 15px !important;
      }

      .logo-illustration-large img {
        height: 50px !important;
      }
    }

    /* Medium Desktop */
    @media (max-width: 1400px) and (min-width: 1201px) {
      .login-left {
        width: 62%;
      }
      
      .login-right {
        width: 38%;
      }

      .logo-illustration-large {
        top: 18px !important;
        right: calc(38% + 18px) !important;
        padding: 9px 13px !important;
      }

      .logo-illustration-large img {
        height: 48px !important;
      }
    }

    /* Small Desktop - CRITICAL untuk 1006px */
    @media (max-width: 1200px) and (min-width: 993px) {
      .login-left {
        width: 60%;
      }
      
      .login-right {
        width: 40%;
      }

      .logo-illustration-large {
        top: 16px !important;
        right: calc(40% + 16px) !important;
        padding: 8px 12px !important;
      }

      .logo-illustration-large img {
        height: 46px !important;
      }
    }

    /* Tablet */
    @media (max-width: 992px) and (min-width: 769px) {
      .login-left {
        width: 55%;
      }
      
      .login-right {
        width: 45%;
      }

      .logo-illustration-large {
        top: 15px !important;
        right: calc(45% + 15px) !important;
        padding: 8px 12px !important;
      }

      .logo-illustration-large img {
        height: 45px !important;
      }
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

    /* Right Side - Login Form - Responsif */
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
      background: linear-gradient(135deg, #007bff, #0056b3);
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
      font-size: clamp(0.8rem, 1.5vw, 0.9rem);
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
      padding: 0.8rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      font-size: clamp(0.85rem, 2vw, 0.9rem);
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
      font-size: clamp(0.75rem, 1.5vw, 0.85rem);
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

    .card-footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      text-align: center;
      font-size: 0.75rem;
      color: #6c757d;
    }

    /* Enhanced Responsive Design */
    @media (max-width: 1400px) {
      .login-left {
        min-width: 62%;
        width: 62%;
      }
      
      .login-right {
        min-width: 38%;
        width: 38%;
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
      
      .illustration-content {
        padding: 1.5rem;
        max-width: 400px;
      }
      
      .login-box {
        max-width: 350px;
      }
      
      .card-body, .card-header {
        padding-left: 1.3rem;
        padding-right: 1.3rem;
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
      
      .illustration-content {
        padding: 1rem;
        max-width: 100%;
      }
      
      /* Logo untuk mobile - kembali ke absolute karena layout berubah */
      .logo-illustration-large {
        position: absolute !important;
        top: 12px !important;
        right: 12px !important;
        padding: 6px 10px !important;
      }

      .logo-illustration-large img {
        height: 40px !important;
      }
      
      .login-box {
        max-width: 100%;
        width: 100%;
      }
      
      .form-control {
        height: calc(2.75rem + 2px);
        font-size: 16px; /* Prevents zoom on iOS */
      }
      
      .input-group-text {
        width: 50px;
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
      
      .login-box {
        width: 95%;
        max-width: 320px;
        margin: 1rem auto;
        margin-top: 70px;
      }
      
      .card-body, .card-header {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .card-header {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
      }
      
      /* Logo untuk small mobile */
      .logo-illustration-large {
        position: absolute !important;
        top: 10px !important;
        right: 10px !important;
        padding: 5px 8px !important;
      }
      
      .logo-illustration-large img {
        height: 35px !important;
      }
      
      .illustration-content {
        padding: 0.75rem;
      }
      
      .btn-primary {
        padding: 1rem 0.7rem;
      }
    }

    @media (max-width: 480px) {
      .login-left {
        min-height: 30vh;
      }
      
      .login-right {
        min-height: 70vh;
        padding: 0.5rem;
      }
      
      .login-box {
        margin: 0.5rem auto;
        width: calc(100% - 1rem);
      }

      /* Logo untuk very small mobile */
      .logo-illustration-large {
        position: absolute !important;
        top: 8px !important;
        right: 8px !important;
        padding: 4px 6px !important;
      }

      .logo-illustration-large img {
        height: 30px !important;
      }
    }

    @media (max-width: 360px) {
      .card-body {
        padding: 1rem 0.8rem;
      }
      
      .card-header {
        padding: 1.2rem 0.8rem 1rem;
      }
      
      .illustration-content {
        padding: 0.5rem;
      }

      /* Logo untuk extra small */
      .logo-illustration-large {
        position: absolute !important;
        top: 6px !important;
        right: 6px !important;
        padding: 3px 5px !important;
      }

      .logo-illustration-large img {
        height: 28px !important;
      }
    }

    /* Landscape orientation for mobile devices */
    @media (max-height: 500px) and (orientation: landscape) {
      .login-page {
        flex-direction: row;
      }
      
      .login-left {
        min-width: 45%;
        width: 45%;
        min-height: 100vh;
      }
      
      .login-right {
        min-width: 55%;
        width: 55%;
        min-height: 100vh;
        padding: 1rem;
      }
      
      .illustration-content {
        padding: 0.5rem;
      }
      
      /* Logo untuk landscape */
      .logo-illustration-large {
        position: absolute !important;
        top: 10px !important;
        right: 10px !important;
        padding: 5px 8px !important;
      }
      
      .logo-illustration-large img {
        height: 32px !important;
      }
      
      .welcome-text {
        font-size: 1.1rem;
        margin-top: 0.5rem;
        margin-bottom: 0.25rem;
      }
      
      .welcome-subtitle {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
      }
      
      .welcome-description {
        font-size: 0.7rem;
      }
      
      .card-header {
        padding: 1rem 1.5rem 0.8rem;
      }
      
      .card-body {
        padding: 1rem 1.5rem 0.8rem;
      }
      
      .login-box {
        margin-top: 0;
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

    /* Additional AdminLTE-like enhancements */
    .form-control::placeholder {
      color: #adb5bd;
      opacity: 1;
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

    /* Print styles */
    @media print {
      .login-page {
        display: none;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-page">
  <!-- Left Side - Illustration -->
  <div class="login-left">
    <!-- Logo Independent - MOVED OUTSIDE illustration-content -->
    <div class="logo-illustration-large">
      <img src="{{ asset('dist/img/logo-login.png') }}" alt="Logo INKA">
    </div>
    
    <div class="illustration-content">
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