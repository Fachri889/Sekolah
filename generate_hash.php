<?php
// generate_hash.php - Helper untuk generate password hash
// Jalankan sekali untuk update password di database
// Akses: http://localhost/sekolah/generate_hash.php

require_once 'koneksi.php';

$password = 'password'; // password default
$hash = password_hash($password, PASSWORD_DEFAULT);

// Update hash untuk superadmin dan admin
mysqli_query($koneksi, "UPDATE users SET password='$hash' WHERE username='superadmin'");
mysqli_query($koneksi, "UPDATE users SET password='$hash' WHERE username='admin'");

echo "✅ Password hash berhasil diperbarui!<br>";
echo "Username: <code>superadmin</code> → Password: <code>password</code><br>";
echo "Username: <code>admin</code> → Password: <code>password</code><br>";
echo "<br><strong>⚠️ Hapus file ini setelah selesai!</strong>";
echo "<br><br><a href='login.php'>→ Pergi ke Halaman Login</a>";
