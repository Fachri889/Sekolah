<?php
$judul_halaman = 'Kelola Kegiatan';
$halaman_admin = 'kegiatan';
require_once __DIR__ . '/../includes/admin_header.php';

$result = mysqli_query($koneksi, "SELECT * FROM kegiatan ORDER BY tanggal DESC, created_at DESC");
$total = mysqli_num_rows($result);
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5>📸 Data Kegiatan (<?= $total ?>)</h5>
        <a href="tambah.php" class="btn btn-sm btn-kuning"><i class="fas fa-plus me-1"></i>Tambah Kegiatan</a>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr><th>No</th><th>Foto</th><th>Judul Kegiatan</th><th>Jadwal / Waktu</th><th>Dibuat Pada</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $no=1; while ($kegiatan = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if ($kegiatan['gambar'] && file_exists(UPLOAD_PATH.$kegiatan['gambar'])): ?>
                            <img src="<?= UPLOAD_URL.$kegiatan['gambar'] ?>" alt="Foto" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                            <div style="width:50px;height:50px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999;"><i class="fas fa-camera"></i></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars(substr($kegiatan['judul'],0,50)) ?><?= strlen($kegiatan['judul'])>50?'...':'' ?></strong>
                            <div class="small text-muted mt-1"><?= htmlspecialchars(substr($kegiatan['deskripsi'],0,50)) ?>...</div>
                        </td>
                        <td><?= htmlspecialchars($kegiatan['tanggal']) ?></td>
                        <td class="text-muted small"><?= date('d M Y H:i', strtotime($kegiatan['created_at'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $kegiatan['id'] ?>" class="btn btn-sm btn-biru"><i class="fas fa-edit"></i></a>
                            <a href="hapus.php?id=<?= $kegiatan['id'] ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if ($total === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kegiatan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
