<?php
$judul_halaman = 'Kelola Guru';
$halaman_admin = 'guru';
require_once __DIR__ . '/../includes/admin_header.php';

$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';
$where = $search ? "WHERE nama LIKE '%$search%' OR mata_pelajaran LIKE '%$search%'" : '';
$result = mysqli_query($koneksi, "SELECT * FROM guru $where ORDER BY nama");
$total = mysqli_num_rows($result);
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5>👨‍🏫 Daftar Guru (<?= $total ?>)</h5>
        <a href="tambah.php" class="btn btn-sm btn-kuning"><i class="fas fa-plus me-1"></i>Tambah Guru</a>
    </div>
    <div class="p-3">
        <form method="GET" class="d-flex gap-2 mb-3">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau mata pelajaran..." 
                   value="<?= htmlspecialchars($search) ?>" style="max-width:300px; border-color:var(--biru-muda);">
            <button type="submit" class="btn-biru btn">Cari</button>
            <?php if ($search): ?><a href="." class="btn btn-outline-secondary">Reset</a><?php endif; ?>
        </form>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Foto</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($guru = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if ($guru['foto'] && file_exists(UPLOAD_PATH.'guru/'.$guru['foto'])): ?>
                            <img src="<?= UPLOAD_URL ?>guru/<?= htmlspecialchars($guru['foto']) ?>" 
                                 width="50" height="50" style="object-fit:cover;border-radius:50%;border:2px solid var(--biru-muda);">
                            <?php else: ?>
                            <div style="width:50px;height:50px;border-radius:50%;background:var(--biru-light);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">👤</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($guru['nama']) ?></strong></td>
                        <td><span class="kelas-badge"><?= htmlspecialchars($guru['mata_pelajaran']) ?></span></td>
                        <td class="text-muted small"><?= htmlspecialchars($guru['nip'] ?: '-') ?></td>
                        <td class="text-muted small"><?= htmlspecialchars($guru['email'] ?: '-') ?></td>
                        <td>
                            <a href="edit.php?id=<?= $guru['id'] ?>" class="btn btn-sm btn-biru" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="hapus.php?id=<?= $guru['id'] ?>" class="btn btn-sm btn-danger btn-hapus" title="Hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($total === 0): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data guru</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
