<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekLogin();
$id = (int)($_GET['id'] ?? 0);
if ($id && mysqli_query($koneksi, "DELETE FROM siswa WHERE id=$id")) {
    redirect(BASE_URL.'admin/siswa/', 'Siswa berhasil dihapus!');
} else {
    redirect(BASE_URL.'admin/siswa/', 'Gagal menghapus data!', 'danger');
}
