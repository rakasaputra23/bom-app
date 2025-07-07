<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header text-center">Reset Password</div>
        <div class="card-body">
          <form action="#" method="POST">
            <div class="form-group">
              <label for="email">Masukkan Email Anda</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Email terdaftar">
            </div>
            <button type="submit" class="btn btn-warning btn-block">Kirim Link Reset</button>
          </form>
        </div>
        <div class="card-footer text-center">
          <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
