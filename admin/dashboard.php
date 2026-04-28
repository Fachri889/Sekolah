<?php
$judul_halaman = 'Dashboard Admin';
$halaman_admin = 'dashboard';
require_once __DIR__ . '/includes/admin_header.php';

// Stats
$stats = [
    'guru' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM guru"))['t'],
    'siswa' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM siswa"))['t'],
    'kelas' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT kelas) as t FROM siswa"))['t'],
    'info' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM informasi WHERE status='aktif'"))['t'],
];

// Data kelas untuk chart
$result_kelas = mysqli_query($koneksi, "SELECT kelas, COUNT(*) as jumlah FROM siswa GROUP BY kelas ORDER BY kelas");
$kelas_labels = []; $kelas_data = [];
while ($row = mysqli_fetch_assoc($result_kelas)) {
    $kelas_labels[] = $row['kelas'];
    $kelas_data[] = $row['jumlah'];
}

// Aktivitas terbaru
$result_terbaru = mysqli_query($koneksi, "SELECT judul, kategori, created_at FROM informasi ORDER BY created_at DESC LIMIT 5");
$result_guru_terbaru = mysqli_query($koneksi, "SELECT nama, mata_pelajaran, created_at FROM guru ORDER BY created_at DESC LIMIT 5");
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="stat-icon-bg bg-biru-soft">👨‍🏫</div>
            <div class="stat-text">
                <div class="number"><?= $stats['guru'] ?></div>
                <div class="label">Total Guru</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="stat-icon-bg bg-kuning-soft">👩‍🎓</div>
            <div class="stat-text">
                <div class="number"><?= $stats['siswa'] ?></div>
                <div class="label">Total Siswa</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="stat-icon-bg bg-hijau-soft">🏫</div>
            <div class="stat-text">
                <div class="number"><?= $stats['kelas'] ?></div>
                <div class="label">Kelas Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="stat-icon-bg bg-merah-soft">📰</div>
            <div class="stat-text">
                <div class="number"><?= $stats['info'] ?></div>
                <div class="label">Informasi Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card-admin mb-4">
    <div class="card-header-admin">
        <h5>⚡ Aksi Cepat</h5>
    </div>
    <div class="p-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>admin/guru/tambah.php" class="btn-kuning btn"><i class="fas fa-plus me-2"></i>Tambah Guru</a>
            <a href="<?= BASE_URL ?>admin/siswa/tambah.php" class="btn-biru btn"><i class="fas fa-plus me-2"></i>Tambah Siswa</a>
            <a href="<?= BASE_URL ?>admin/informasi/tambah.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>Tambah Informasi</a>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
            <a href="<?= BASE_URL ?>admin/users/tambah.php" class="btn btn-warning"><i class="fas fa-user-plus me-2"></i>Tambah Admin</a>
            <a href="<?= BASE_URL ?>admin/pengaturan.php" class="btn btn-secondary"><i class="fas fa-cog me-2"></i>Pengaturan</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-6">
        <div class="card-admin h-100">
            <div class="card-header-admin">
                <h5>📊 Siswa per Kelas</h5>
            </div>
            <div class="p-4">
                <canvas id="kelasChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Informasi Terbaru -->
    <div class="col-lg-6">
        <div class="card-admin h-100">
            <div class="card-header-admin">
                <h5>📰 Informasi Terbaru</h5>
                <a href="<?= BASE_URL ?>admin/informasi/" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <?php while ($info = mysqli_fetch_assoc($result_terbaru)): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold small"><?= htmlspecialchars(substr($info['judul'],0,40)) ?>...</div>
                                <div class="text-muted" style="font-size:0.75rem;">
                                    <span class="badge-kategori badge-<?= $info['kategori'] ?> me-1"><?= $info['kategori'] ?></span>
                                    <?= date('d M Y', strtotime($info['created_at'])) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Guru Terbaru -->
    <div class="col-lg-6">
        <div class="card-admin">
            <div class="card-header-admin">
                <h5>👨‍🏫 Guru Terbaru Ditambah</h5>
                <a href="<?= BASE_URL ?>admin/guru/" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <?php while ($g = mysqli_fetch_assoc($result_guru_terbaru)): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold small"><?= htmlspecialchars($g['nama']) ?></div>
                                <div class="text-muted" style="font-size:0.75rem;"><span class="kelas-badge"><?= htmlspecialchars($g['mata_pelajaran']) ?></span></div>
                            </td>
                            <td class="text-muted text-end pe-3" style="font-size:0.75rem;"><?= date('d M Y', strtotime($g['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Info role -->
    <div class="col-lg-6">
        <div class="card-admin">
            <div class="card-header-admin">
                <h5>👤 Info Akun Anda</h5>
            </div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:60px;height:60px;background:var(--biru-light);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;">
                        <?= $_SESSION['role']==='superadmin' ? '⭐' : '🛡️' ?>
                    </div>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></div>
                        <div class="text-muted small">@<?= htmlspecialchars($_SESSION['username']) ?></div>
                        <?php if ($_SESSION['role']==='superadmin'): ?>
                        <span class="badge" style="background:var(--kuning);color:#333;">Super Administrator</span>
                        <?php else: ?>
                        <span class="badge" style="background:var(--biru-muda);color:#333;">Administrator</span>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
                <div class="small text-muted">
                    <p><strong>Hak Akses Anda:</strong></p>
                    <ul>
                        <li>✅ Kelola data Guru</li>
                        <li>✅ Kelola data Siswa</li>
                        <li>✅ Kelola Informasi & Berita</li>
                        <?php if ($_SESSION['role']==='superadmin'): ?>
                        <li>✅ Manajemen akun Admin</li>
                        <li>✅ Pengaturan Sistem</li>
                        <?php else: ?>
                        <li>❌ Manajemen akun Admin (Super Admin only)</li>
                        <li>❌ Pengaturan Sistem (Super Admin only)</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('kelasChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(fn($k) => '"'.$k.'"', $kelas_labels)) ?>],
        datasets: [{ 
            data: [<?= implode(',', $kelas_data) ?>],
            backgroundColor: ['#FFD700','#87CEEB','#FFB347','#98D8C8','#FFB6C1','#B0E0E6'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
