<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header text-center">Register</div>
        <div class="card-body">
          <form action="#" method="POST">
            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Nama lengkap">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
            </div>
            <button type="submit" class="btn btn-success btn-block">Register</button>
          </form>
        </div>
        <div class="card-footer text-center">
          Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
