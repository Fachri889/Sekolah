<?php
$judul_halaman = 'Informasi & Berita';
$halaman_aktif = 'informasi';
require_once 'includes/header.php';

// Detail view
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM informasi WHERE id=$id AND status='aktif'");
    $detail = mysqli_fetch_assoc($result);
    if (!$detail) {
        header('Location: informasi.php');
        exit;
    }
}

// Filter kategori
$filter = isset($_GET['kategori']) ? escape($koneksi, $_GET['kategori']) : '';
$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';

$where = "WHERE status='aktif'";
if ($filter && in_array($filter, ['berita','pengumuman'])) $where .= " AND kategori='$filter'";
if ($search) $where .= " AND (judul LIKE '%$search%' OR konten LIKE '%$search%')";

$result_info = mysqli_query($koneksi, "SELECT * FROM informasi $where ORDER BY created_at DESC");
?>

<!-- PAGE HEADER -->
<div style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); padding: 50px 0 30px;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">📰 Informasi & Berita</h1>
        <p class="text-muted">Berita terkini dan pengumuman resmi dari sekolah</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <?php if (isset($detail)): ?>
        <!-- DETAIL ARTIKEL -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <a href="informasi.php" class="btn btn-sm btn-biru mb-4">← Kembali</a>
                <div class="tabel-wrapper p-4">
                    <span class="badge-kategori badge-<?= $detail['kategori'] ?> mb-3 d-inline-block">
                        <?= $detail['kategori'] === 'berita' ? '📰 Berita' : '📣 Pengumuman' ?>
                    </span>
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($detail['judul']) ?></h2>
                    <div class="d-flex gap-3 text-muted small mb-4 pb-3" style="border-bottom:1px solid #eee;">
                        <span><i class="fas fa-user me-1"></i><?= htmlspecialchars($detail['penulis']) ?></span>
                        <span><i class="fas fa-calendar me-1"></i><?= date('d F Y', strtotime($detail['created_at'])) ?></span>
                    </div>
                    <?php if ($detail['gambar'] && file_exists(UPLOAD_PATH . $detail['gambar'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($detail['gambar']) ?>" class="img-fluid rounded-3 mb-4 w-100" style="max-height:400px;object-fit:cover;" alt="">
                    <?php endif; ?>
                    <div style="line-height:1.9; color:var(--teks-gelap);"><?= nl2br(htmlspecialchars($detail['konten'])) ?></div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- DAFTAR INFORMASI -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 flex-wrap">
                <a href="informasi.php" class="kelas-btn <?= !$filter ? 'active' : '' ?>">Semua</a>
                <a href="?kategori=berita" class="kelas-btn <?= $filter==='berita' ? 'active' : '' ?>">📰 Berita</a>
                <a href="?kategori=pengumuman" class="kelas-btn <?= $filter==='pengumuman' ? 'active' : '' ?>">📣 Pengumuman</a>
            </div>
            <form method="GET" class="d-flex gap-2">
                <?php if ($filter): ?><input type="hidden" name="kategori" value="<?= $filter ?>"><?php endif; ?>
                <input type="text" name="search" class="form-control" placeholder="Cari informasi..." value="<?= htmlspecialchars($search) ?>" style="border-color:var(--biru-muda);">
                <button type="submit" class="btn-biru btn">🔍</button>
                <?php if ($search): ?><a href="informasi.php" class="btn btn-outline-secondary">✕</a><?php endif; ?>
            </form>
        </div>

        <div class="row g-4">
            <?php $count = 0; while ($info = mysqli_fetch_assoc($result_info)): $count++; ?>
            <div class="col-lg-4 col-md-6">
                <div class="info-card">
                    <?php if ($info['gambar'] && file_exists(UPLOAD_PATH . $info['gambar'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($info['gambar']) ?>" class="w-100" style="height:180px;object-fit:cover;" alt="">
                    <?php else: ?>
                    <div style="height:160px; background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); display:flex; align-items:center; justify-content:center; font-size:4rem;">
                        <?= $info['kategori'] === 'berita' ? '📰' : '📣' ?>
                    </div>
                    <?php endif; ?>
                    <div class="p-4">
                        <span class="badge-kategori badge-<?= $info['kategori'] ?> mb-2 d-inline-block">
                            <?= $info['kategori'] === 'berita' ? '📰 Berita' : '📣 Pengumuman' ?>
                        </span>
                        <h5 class="card-title"><?= htmlspecialchars($info['judul']) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars(substr($info['konten'], 0, 120)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i><?= date('d M Y', strtotime($info['created_at'])) ?></small>
                            <a href="?id=<?= $info['id'] ?>" class="btn btn-sm btn-biru">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php if ($count === 0): ?>
            <div class="col-12 text-center py-5">
                <div style="font-size:4rem;">😔</div>
                <h4>Tidak ada informasi ditemukan</h4>
                <a href="informasi.php" class="btn-biru btn mt-2">Lihat Semua</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
