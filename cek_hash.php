<?php
$hash = '$2y$10$dXJubvVGHuIK7GkWrKMZbOSdtWTx7eI17cF8B4w/hNjV1uFC6Fv2e';
if (password_verify('12345', $hash)) {
    echo "✅ Hash cocok, password benar";
} else {
    echo "❌ Hash tidak cocok";
}
