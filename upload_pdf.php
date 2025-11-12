<?php
session_start();
require 'functions.php';

if (!isset($_SESSION["login"])) {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'];

if (isset($_POST['submit'])) {
  $namaFile = $_FILES['file_pdf']['name'];
  $tmpName = $_FILES['file_pdf']['tmp_name'];
  $error = $_FILES['file_pdf']['error'];
  $ukuran = $_FILES['file_pdf']['size'];

  // Validasi file
  $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
  if ($ekstensi !== 'pdf') {
    echo "<script>alert('Hanya file PDF yang diperbolehkan!');</script>";
  } elseif ($ukuran > 5 * 1024 * 1024) { // max 5 MB
    echo "<script>alert('Ukuran file terlalu besar (maks 5MB)!');</script>";
  } else {
    $namaBaru = uniqid() . '_' . $namaFile;
    move_uploaded_file($tmpName, 'upload/' . $namaBaru);

    // Simpan nama file ke database
    $query = "UPDATE arsip_dokumen SET file_pdf = '$namaBaru', status_arsip = 'Sudah Upload' WHERE id_arsip_dokumen = $id";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
      echo "<script>
              alert('PDF berhasil diupload!');
              document.location.href = 'index.php';
            </script>";
    } else {
      echo "<script>alert('Gagal upload PDF.');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload PDF Arsip</title>
  <link rel="stylesheet" href="vendor/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h3 class="text-center mb-4">Upload Dokumen PDF Arsip</h3>
    <form action="" method="post" enctype="multipart/form-data" class="p-4 bg-white rounded shadow-sm">
      <div class="form-group">
        <label for="file_pdf">Pilih Dokumen PDF:</label>
        <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept="application/pdf" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary mt-3">Upload</button>
      <a href="index.php" class="btn btn-secondary mt-3">Batal</a>
    </form>
  </div>
</body>
</html>
