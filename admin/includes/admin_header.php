<?php
// admin/includes/admin_header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php';
cekLogin();

$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
$role = $_SESSION['role'];
$halaman_admin = $halaman_admin ?? '';

// Statistik untuk sidebar
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM guru"))['t'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM siswa"))['t'];
$total_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM informasi"))['t'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul_halaman ?? 'Admin Panel' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="admin-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🏫</div>
        <span><?= htmlspecialchars(substr($nama_sekolah, 0, 30)) ?></span>
    </div>

    <div class="nav-menu">
        <div class="nav-section">Utama</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/dashboard.php" class="<?= $halaman_admin==='dashboard'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard
            </a>
        </div>

        <div class="nav-section">Kelola Data</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/guru/" class="<?= $halaman_admin==='guru'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span> Guru
                <span class="ms-auto badge" style="background:var(--biru-muda);color:#333;font-size:0.7rem;"><?= $total_guru ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/siswa/" class="<?= $halaman_admin==='siswa'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-users"></i></span> Siswa
                <span class="ms-auto badge" style="background:var(--kuning);color:#333;font-size:0.7rem;"><?= $total_siswa ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/informasi/" class="<?= $halaman_admin==='informasi'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-newspaper"></i></span> Informasi
                <span class="ms-auto badge" style="background:var(--biru-muda);color:#333;font-size:0.7rem;"><?= $total_info ?></span>
            </a>
        </div>

        <?php if ($role === 'superadmin'): ?>
        <div class="nav-section">Super Admin</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/users/" class="<?= $halaman_admin==='users'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-user-shield"></i></span> Kelola Admin
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/pengaturan.php" class="<?= $halaman_admin==='pengaturan'?'active':'' ?>">
                <span class="nav-icon"><i class="fas fa-cog"></i></span> Pengaturan Sistem
            </a>
        </div>
        <?php endif; ?>

        <div class="nav-section">Navigasi</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>index.php" target="_blank">
                <span class="nav-icon"><i class="fas fa-globe"></i></span> Lihat Website
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>admin/logout.php">
                <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span> Logout
            </a>
        </div>
    </div>
</div>

<!-- MAIN AREA -->
<div class="admin-main">
    <!-- TOPBAR -->
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-lg-none" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <h6 class="mb-0 fw-bold"><?= $judul_halaman ?? 'Dashboard' ?></h6>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></div>
                <div class="text-muted" style="font-size:0.75rem;">
                    <?php if ($role === 'superadmin'): ?>
                    <span class="badge" style="background:var(--kuning);color:#333;">⭐ Super Admin</span>
                    <?php else: ?>
                    <span class="badge" style="background:var(--biru-muda);color:#333;">🛡️ Admin</span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="admin-content">
        <?php flashMessage(); ?>
