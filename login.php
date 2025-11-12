<?php 
session_start(); 
require 'functions.php';

if (!$koneksi) {
  die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {
  echo "<pre>DEBUG: Koneksi ke database berhasil</pre>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  echo "<pre>DEBUG: POST dikirim</pre>";
  print_r($_POST);
}

// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
  $id = $_COOKIE['id'];
  $key = $_COOKIE['key'];

  $result = mysqli_query($koneksi, "SELECT username FROM user WHERE id_user = $id");
  if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if ($key === hash('sha256', $row['username'])) {
      $_SESSION['login'] = true;
    }
  }
}

// Jika tombol login ditekan
if (isset($_POST["login"])) {
  $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
  $password = $_POST["password"];

  $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");

  if (!$result) {
    echo "<pre>DEBUG: Query gagal - " . mysqli_error($koneksi) . "</pre>";
  } else {
    echo "<pre>DEBUG: Jumlah baris ditemukan = " . mysqli_num_rows($result) . "</pre>";
  }

  // cek username 
  if (mysqli_num_rows($result) === 1) {

    $row = mysqli_fetch_assoc($result);

    echo "<pre>";
    echo "Password diketik: " . $password . "\n";
    echo "Hash di DB: " . $row['password'] . "\n";
    echo "Cocok? " . (password_verify($password, $row['password']) ? 'YA' : 'TIDAK');
    echo "</pre>";

    if (password_verify($password, $row["password"])) {
      // set session
      $_SESSION["username"] = $row["username"];
      $_SESSION["nip"] = $row["nip"] ?? '';
      $_SESSION["nama"] = $row["nama"] ?? '';
      $_SESSION["akses"] = $row["akses"] ?? '';
      $_SESSION["id_user"] = $row["id_user"];
      $_SESSION["login"] = true;

      // cek remember me
      if (isset($_POST["remember_me"])) {
        setcookie('id', $row['id_user'], time() + 60 * 60 * 24 * 3);
        setcookie('key', hash('sha256', $row['username']), time() + 60 * 60 * 24 * 3);
      }

      header("Location: index.php");
      exit;
    } else {
      $error = true;
    }
  } else {
    $error = true;
  }
}

// kalau sudah login langsung arahkan ke index
if (isset($_SESSION["login"])) {
  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="vendor/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/style.css">
    <link rel="shortcut icon" type="text/css" href="img/mec.png">
    <title>Login</title>
  </head>
  <body class="background-img">
    <div class="container mt-4 mb-3 rounded">
      <div class="row justify-content-center">
        <div class="col-lg-4 bg-white p-4 login">
          <h3 class="card-text text-center">Aplikasi Arsip Dokumen</h3>
          <div class="card text-center border-0">
            <img src="img/mec.png" class="card-img-top mx-auto">
            <div class="card-body">
              <h4 class="card-text">PT. MATRA ENGINEERING AND CONTRUCTIONS</h4>
            </div>
          </div>
          <?php if (isset($error)) : ?>
            <p class="alert alert-danger"> Username / Password yg Anda Masukkan SALAH !</p>
          <?php endif; ?>
          <form action="" method="post">
            <div class="form-group">
              <label for="username"><img src="img/baseline_account_circle_black_18dp.png"> Username</label>
              <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password"><img src="img/baseline_https_black_18dp.png"> Password</label>
              <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
              <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Submit</button>
            <p style="font-family: arial; font-size: 12px; margin-top: 10%; font-weight: bold; text-align: center;">
              COPYRIGHT &copy; <?= date('Y'); ?> SAFITRI
            </p>
          </form>
        </div>
      </div>
    </div>
    <script src="vendor/jquery-3.3.1.slim.min.js"></script>
    <script src="vendor/popper.min.js"></script>
    <script src="vendor/bootstrap.min.js"></script>
  </body>
</html>
