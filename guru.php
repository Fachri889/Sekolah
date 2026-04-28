<?php
$judul_halaman = 'Daftar Guru';
$halaman_aktif = 'guru';
require_once 'includes/header.php';

$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';
$where = $search ? "WHERE nama LIKE '%$search%' OR mata_pelajaran LIKE '%$search%'" : '';
$result_guru = mysqli_query($koneksi, "SELECT * FROM guru $where ORDER BY nama ASC");
$total = mysqli_num_rows($result_guru);
?>

<!-- PAGE HEADER -->
<div style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); padding: 50px 0 30px;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">👨‍🏫 Daftar Guru</h1>
        <p class="text-muted">Tenaga pengajar profesional dan berpengalaman</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <!-- Search & Info -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <p class="mb-0 text-muted">Menampilkan <strong><?= $total ?></strong> guru</p>
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau mata pelajaran..." value="<?= htmlspecialchars($search) ?>" style="min-width:250px; border-color:var(--biru-muda);">
                <button type="submit" class="btn-biru btn">🔍 Cari</button>
                <?php if ($search): ?><a href="guru.php" class="btn btn-outline-secondary">✕</a><?php endif; ?>
            </form>
        </div>

        <!-- Guru Cards -->
        <div class="row g-4">
            <?php if ($total === 0): ?>
            <div class="col-12 text-center py-5">
                <div style="font-size:4rem;">😔</div>
                <h4>Tidak ada guru ditemukan</h4>
                <a href="guru.php" class="btn-biru btn mt-2">Lihat Semua Guru</a>
            </div>
            <?php else: ?>
            <?php while ($guru = mysqli_fetch_assoc($result_guru)): ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="guru-card">
                    <div class="card-foto">
                        <?php if ($guru['foto'] && file_exists(UPLOAD_PATH . 'guru/' . $guru['foto'])): ?>
                            <img src="<?= UPLOAD_URL ?>guru/<?= htmlspecialchars($guru['foto']) ?>" alt="<?= htmlspecialchars($guru['nama']) ?>">
                        <?php else: ?>
                            <div class="foto-placeholder">👤</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="card-nama"><?= htmlspecialchars($guru['nama']) ?></div>
                        <div class="card-mapel mt-1"><?= htmlspecialchars($guru['mata_pelajaran']) ?></div>
                        <?php if ($guru['nip']): ?>
                        <div class="mt-2 text-muted" style="font-size:0.8rem;"><i class="fas fa-id-card me-1"></i><?= htmlspecialchars($guru['nip']) ?></div>
                        <?php endif; ?>
                        <?php if ($guru['email']): ?>
                        <div class="mt-1 text-muted" style="font-size:0.8rem;"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($guru['email']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
