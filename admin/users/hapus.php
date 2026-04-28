<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekSuperAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id == $_SESSION['user_id']) {
    redirect(BASE_URL.'admin/users/', 'Tidak bisa menghapus akun sendiri!', 'danger');
}
if ($id && mysqli_query($koneksi, "DELETE FROM users WHERE id=$id")) {
    redirect(BASE_URL.'admin/users/', 'Akun admin berhasil dihapus!');
} else {
    redirect(BASE_URL.'admin/users/', 'Gagal menghapus akun!', 'danger');
}
