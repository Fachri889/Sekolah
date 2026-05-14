<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../koneksi.php';

$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
$logo_sekolah  = getSetting($koneksi, 'logo_sekolah');
$halaman_aktif = $halaman_aktif ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul_halaman ?? $nama_sekolah ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-sekolah navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php">
            <div class="logo-icon" style="width:52px;height:52px;min-width:52px;max-width:52px;border-radius:50%;overflow:hidden;background:var(--kuning);border:3px solid #fff;box-shadow:0 3px 12px rgba(255,215,0,0.6);flex-shrink:0;">
                <?php if (!empty($logo_sekolah)): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($logo_sekolah) ?>" alt="Logo <?= htmlspecialchars($nama_sekolah) ?>" style="width:100%;height:100%;object-fit:cover;display:block;">
                <?php else: ?>
                    <i class="fas fa-school" style="font-size:1.6rem;color:#2c5282;"></i>
                <?php endif; ?>
            </div>
            <div>
                <div><?= htmlspecialchars($nama_sekolah) ?></div>
                <small style="font-size:0.65rem;font-weight:400;color:rgba(44,62,80,0.7);">Unggul &middot; Berkarakter &middot; Berprestasi</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?= $halaman_aktif==='home'?'active':'' ?>" href="<?= BASE_URL ?>index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link <?= $halaman_aktif==='guru'?'active':'' ?>" href="<?= BASE_URL ?>guru.php"><i class="fas fa-chalkboard-teacher me-1"></i>Guru</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $halaman_aktif==='informasi'?'active':'' ?>" href="<?= BASE_URL ?>informasi.php" id="dropdownInformasi" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-newspaper me-1"></i>Informasi
                    </a>
                    <ul class="dropdown-menu dropdown-menu-sekolah" aria-labelledby="dropdownInformasi">
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>informasi.php?kategori=berita">
                                <i class="fas fa-newspaper me-2" style="color:var(--biru-dark);"></i>Berita
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>informasi.php?kategori=pengumuman">
                                <i class="fas fa-bullhorn me-2" style="color:#856404;"></i>Pengumuman
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?= $halaman_aktif==='kegiatan'?'active':'' ?>" href="<?= BASE_URL ?>kegiatan.php"><i class="fas fa-camera-retro me-1"></i>Kegiatan</a></li>
                <li class="nav-item"><a class="nav-link <?= $halaman_aktif==='siswa'?'active':'' ?>" href="<?= BASE_URL ?>siswa.php"><i class="fas fa-users me-1"></i>Siswa</a></li>
                <li class="nav-item"><a class="nav-link <?= $halaman_aktif==='lokasi'?'active':'' ?>" href="<?= BASE_URL ?>lokasi.php"><i class="fas fa-map-marker-alt me-1"></i>Lokasi</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard.php"><i class="fas fa-cog me-1"></i>Admin</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link btn-kuning ms-2 px-3" href="<?= BASE_URL ?>login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
