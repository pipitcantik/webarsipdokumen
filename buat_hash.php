<?php
$pass = '12345';
$hash = password_hash($pass, PASSWORD_DEFAULT);

echo "<pre>";
echo "Password: $pass\n";
echo "Hash: $hash\n";
echo "Verifikasi: " . (password_verify($pass, $hash) ? "COCOK" : "TIDAK COCOK");
echo "</pre>";
