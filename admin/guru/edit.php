<?php
$judul_halaman = 'Edit Guru';
$halaman_admin = 'guru';
require_once __DIR__ . '/../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM guru WHERE id=$id");
$guru = mysqli_fetch_assoc($result);
if (!$guru) redirect(BASE_URL.'admin/guru/', 'Data tidak ditemukan!', 'danger');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($koneksi, $_POST['nama']);
    $mapel = escape($koneksi, $_POST['mata_pelajaran']);
    $nip = escape($koneksi, $_POST['nip']);
    $email = escape($koneksi, $_POST['email']);
    $telp = escape($koneksi, $_POST['telp']);
    $foto = $guru['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['foto']['size'] < 2*1024*1024) {
            $foto_baru = 'guru_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], UPLOAD_PATH.'guru/'.$foto_baru)) {
                if ($guru['foto'] && file_exists(UPLOAD_PATH.'guru/'.$guru['foto'])) unlink(UPLOAD_PATH.'guru/'.$guru['foto']);
                $foto = $foto_baru;
            }
        }
    }

    $sql = "UPDATE guru SET nama='$nama', mata_pelajaran='$mapel', nip='$nip', email='$email', telp='$telp', foto='$foto' WHERE id=$id";
    if (mysqli_query($koneksi, $sql)) {
        redirect(BASE_URL.'admin/guru/', 'Data guru berhasil diperbarui!');
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-edit me-2"></i>Edit Guru</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($guru['nama']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="mata_pelajaran" class="form-control" value="<?= htmlspecialchars($guru['mata_pelajaran']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIP</label>
                    <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($guru['nip'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guru['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($guru['telp'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Foto Baru (opsional)</label>
                    <?php if ($guru['foto'] && file_exists(UPLOAD_PATH.'guru/'.$guru['foto'])): ?>
                    <div class="mb-2">
                        <img src="<?= UPLOAD_URL ?>guru/<?= htmlspecialchars($guru['foto']) ?>" style="width:70px;height:70px;object-fit:cover;border-radius:50%;border:2px solid var(--biru-muda);">
                        <small class="text-muted ms-2">Foto saat ini</small>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
