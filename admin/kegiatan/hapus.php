<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekLogin();

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT gambar FROM kegiatan WHERE id=$id");
$kegiatan = mysqli_fetch_assoc($result);

if ($kegiatan) {
    // Hapus file gambar jika ada
    if ($kegiatan['gambar'] && file_exists(UPLOAD_PATH.$kegiatan['gambar'])) {
        unlink(UPLOAD_PATH.$kegiatan['gambar']);
    }
    
    // Hapus dari database
    if (mysqli_query($koneksi, "DELETE FROM kegiatan WHERE id=$id")) {
        redirect(BASE_URL.'admin/kegiatan/', 'Kegiatan berhasil dihapus!');
    } else {
        redirect(BASE_URL.'admin/kegiatan/', 'Gagal menghapus kegiatan!', 'danger');
    }
} else {
    redirect(BASE_URL.'admin/kegiatan/', 'Data tidak ditemukan!', 'danger');
}
