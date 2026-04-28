<?php
$judul_halaman = 'Kelola Admin';
$halaman_admin = 'users';
require_once __DIR__ . '/../includes/admin_header.php';
cekSuperAdmin();

$result = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role DESC, nama_lengkap ASC");
$total = mysqli_num_rows($result);
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5>👥 Manajemen Akun Admin (<?= $total ?>)</h5>
        <a href="tambah.php" class="btn btn-sm btn-kuning"><i class="fas fa-user-plus me-1"></i>Tambah Admin</a>
    </div>
    <div class="p-0">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr><th>No</th><th>Username</th><th>Nama Lengkap</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $no=1; while ($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><code><?= htmlspecialchars($user['username']) ?></code></td>
                    <td><strong><?= htmlspecialchars($user['nama_lengkap']) ?></strong></td>
                    <td>
                        <?php if ($user['role']==='superadmin'): ?>
                        <span class="badge" style="background:var(--kuning);color:#333;">⭐ Super Admin</span>
                        <?php else: ?>
                        <span class="badge" style="background:var(--biru-muda);color:#333;">🛡️ Admin</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-biru"><i class="fas fa-edit"></i></a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="hapus.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i></a>
                        <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled title="Tidak bisa hapus akun sendiri"><i class="fas fa-ban"></i></button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 p-3 rounded-3" style="background:var(--kuning-light);border:1px solid var(--kuning);">
    <small><i class="fas fa-info-circle me-1"></i> <strong>Catatan:</strong> Anda tidak dapat menghapus akun yang sedang Anda gunakan. Pastikan selalu ada minimal 1 akun Super Admin aktif.</small>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
