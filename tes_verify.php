<?php
$input = '12345';
$hash = '$2y$10$pvPAOMcwS5pcTzvr.91ohO0J9MPWBN6umXCqyjHQuCg/k6r2n7tUS';
echo password_verify($input, $hash) ? 'COCOK' : 'TIDAK COCOK';
