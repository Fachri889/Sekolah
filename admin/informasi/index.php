<?php
$judul_halaman = 'Kelola Informasi';
$halaman_admin = 'informasi';
require_once __DIR__ . '/../includes/admin_header.php';

$filter = isset($_GET['kategori']) ? escape($koneksi, $_GET['kategori']) : '';
$where = $filter && in_array($filter, ['berita','pengumuman']) ? "WHERE kategori='$filter'" : '';
$result = mysqli_query($koneksi, "SELECT * FROM informasi $where ORDER BY created_at DESC");
$total = mysqli_num_rows($result);
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5>📰 Informasi & Berita (<?= $total ?>)</h5>
        <a href="tambah.php" class="btn btn-sm btn-kuning"><i class="fas fa-plus me-1"></i>Tambah</a>
    </div>
    <div class="p-3">
        <div class="d-flex gap-2 mb-3">
            <a href="." class="kelas-btn <?= !$filter?'active':'' ?>">Semua</a>
            <a href="?kategori=berita" class="kelas-btn <?= $filter==='berita'?'active':'' ?>">📰 Berita</a>
            <a href="?kategori=pengumuman" class="kelas-btn <?= $filter==='pengumuman'?'active':'' ?>">📣 Pengumuman</a>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr><th>No</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $no=1; while ($info = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars(substr($info['judul'],0,50)) ?><?= strlen($info['judul'])>50?'...':'' ?></strong></td>
                        <td><span class="badge-kategori badge-<?= $info['kategori'] ?>"><?= $info['kategori'] ?></span></td>
                        <td class="text-muted small"><?= htmlspecialchars($info['penulis'] ?: '-') ?></td>
                        <td>
                            <?php if ($info['status']==='aktif'): ?>
                            <span class="badge bg-success">✅ Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">⛔ Non-aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($info['created_at'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $info['id'] ?>" class="btn btn-sm btn-biru"><i class="fas fa-edit"></i></a>
                            <a href="hapus.php?id=<?= $info['id'] ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
