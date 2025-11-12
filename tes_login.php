<?php
$koneksi = mysqli_connect("localhost", "root", "", "arsip");

$username = 'adminarsip';
$password_input = '12345';

$result = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);

if ($row) {
    if (password_verify($password_input, $row['password'])) {
        echo "✅ Password cocok!";
    } else {
        echo "❌ Password SALAH!";
    }
} else {
    echo "❌ Username tidak ditemukan!";
}
