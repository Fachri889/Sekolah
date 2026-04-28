<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekLogin();

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT foto FROM guru WHERE id=$id");
$guru = mysqli_fetch_assoc($result);

if ($guru) {
    if ($guru['foto'] && file_exists(UPLOAD_PATH.'guru/'.$guru['foto'])) {
        unlink(UPLOAD_PATH.'guru/'.$guru['foto']);
    }
    mysqli_query($koneksi, "DELETE FROM guru WHERE id=$id");
    redirect(BASE_URL.'admin/guru/', 'Data guru berhasil dihapus!');
} else {
    redirect(BASE_URL.'admin/guru/', 'Data tidak ditemukan!', 'danger');
}
