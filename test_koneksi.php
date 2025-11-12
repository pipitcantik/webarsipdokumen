<?php
$koneksi = mysqli_connect("localhost", "root", "", "arsip");
if (!$koneksi) {
    die("Gagal konek: " . mysqli_connect_error());
}
echo "Koneksi berhasil!";
