<?php
$judul_halaman = 'Kegiatan Sekolah';
$halaman_aktif = 'kegiatan';
require_once 'includes/header.php';

// Detail view
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id=$id");
    $detail = mysqli_fetch_assoc($result);
    if (!$detail) {
        header('Location: kegiatan.php');
        exit;
    }
}

$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';
$where = "";
if ($search) $where = "WHERE (judul LIKE '%$search%' OR deskripsi LIKE '%$search%')";

$result_kegiatan = mysqli_query($koneksi, "SELECT * FROM kegiatan $where ORDER BY tanggal DESC, created_at DESC");
?>

<!-- PAGE HEADER -->
<div style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); padding: 50px 0 30px;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">📸 Kegiatan Sekolah</h1>
        <p class="text-muted">Galeri dan rekam jejak kegiatan siswa-siswi dan guru</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <?php if (isset($detail)): ?>
        <!-- DETAIL KEGIATAN -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <a href="kegiatan.php" class="btn btn-sm btn-biru mb-4">← Kembali</a>
                <div class="tabel-wrapper p-4">
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($detail['judul']) ?></h2>
                    <div class="d-flex gap-3 text-muted small mb-4 pb-3" style="border-bottom:1px solid #eee;">
                        <span><i class="fas fa-clock me-1"></i>Jadwal / Waktu: <?= htmlspecialchars($detail['tanggal']) ?></span>
                    </div>
                    <?php if ($detail['gambar'] && file_exists(UPLOAD_PATH . $detail['gambar'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($detail['gambar']) ?>" class="img-fluid rounded-3 mb-4 w-100" style="max-height:500px;object-fit:cover;" alt="">
                    <?php endif; ?>
                    <div style="line-height:1.9; color:var(--teks-gelap);"><?= nl2br(htmlspecialchars($detail['deskripsi'])) ?></div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- DAFTAR KEGIATAN -->
        <div class="d-flex flex-wrap justify-content-end align-items-center mb-4 gap-3">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari kegiatan..." value="<?= htmlspecialchars($search) ?>" style="border-color:var(--biru-muda);">
                <button type="submit" class="btn-biru btn"><i class="fas fa-search"></i></button>
                <?php if ($search): ?><a href="kegiatan.php" class="btn btn-outline-secondary">✕</a><?php endif; ?>
            </form>
        </div>

        <div class="row g-4">
            <?php $count = 0; while ($kegiatan = mysqli_fetch_assoc($result_kegiatan)): $count++; ?>
            <div class="col-lg-4 col-md-6">
                <div class="info-card h-100 d-flex flex-column" style="background:#fff; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.05); overflow:hidden; transition:transform 0.3s ease;">
                    <?php if ($kegiatan['gambar'] && file_exists(UPLOAD_PATH . $kegiatan['gambar'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($kegiatan['gambar']) ?>" class="w-100" style="height:220px;object-fit:cover;" alt="">
                    <?php else: ?>
                    <div style="height:220px; background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); display:flex; align-items:center; justify-content:center; font-size:4rem;">
                        📸
                    </div>
                    <?php endif; ?>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="text-muted small mb-2"><i class="fas fa-clock me-1"></i><?= htmlspecialchars($kegiatan['tanggal']) ?></div>
                        <h5 class="fw-bold mb-3" style="color:var(--biru-dark);">
                            <a href="?id=<?= $kegiatan['id'] ?>" class="text-decoration-none text-inherit" style="color:inherit;"><?= htmlspecialchars($kegiatan['judul']) ?></a>
                        </h5>
                        <p class="text-muted small flex-grow-1"><?= htmlspecialchars(substr($kegiatan['deskripsi'], 0, 100)) ?>...</p>
                        <div class="mt-3">
                            <a href="?id=<?= $kegiatan['id'] ?>" class="btn btn-sm btn-outline-biru">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if ($count === 0): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-camera-retro fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data kegiatan<?= $search ? ' untuk pencarian "'.htmlspecialchars($search).'"' : '' ?>.</h5>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
