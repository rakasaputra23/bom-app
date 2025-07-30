<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard')</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <!-- Select2 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

  <!-- Perbaikan tampilan select2 agar dropdown tidak terpotong -->
  <style>
    .select2-container {
      z-index: 9999 !important;
    }
    .table-responsive {
      overflow: visible !important;
    }
    .select2-container--bootstrap4 .select2-dropdown {
      z-index: 99999 !important;
    }
    .card-body {
      overflow: visible !important;
    }
  </style>

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('partials.navbar')
  @include('partials.sidebar')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>

  <footer class="main-footer text-center">
    <strong>&copy; 2025 BOM App.</strong> All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Select2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Setup CSRF for AJAX -->
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>

@stack('scripts')

<script>
// Global function untuk refresh halaman setelah permission update
window.refreshUserPermissions = function() {
    // Force reload halaman untuk memastikan permission terbaru
    setTimeout(function() {
        window.location.reload();
    }, 1000);
};

// Function untuk check permission di JavaScript
window.userHasPermission = function(permission) {
    // Implementasi sesuai kebutuhan, bisa via AJAX ke endpoint khusus
    return false; // Default false untuk keamanan
};
</script>

</body>
</html>