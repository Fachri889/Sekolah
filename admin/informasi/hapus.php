<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekLogin();
$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT gambar FROM informasi WHERE id=$id");
$info = mysqli_fetch_assoc($result);
if ($info) {
    if ($info['gambar'] && file_exists(UPLOAD_PATH.$info['gambar'])) unlink(UPLOAD_PATH.$info['gambar']);
    mysqli_query($koneksi, "DELETE FROM informasi WHERE id=$id");
    redirect(BASE_URL.'admin/informasi/', 'Informasi berhasil dihapus!');
} else {
    redirect(BASE_URL.'admin/informasi/', 'Data tidak ditemukan!', 'danger');
}
