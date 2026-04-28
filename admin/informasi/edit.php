<?php
$judul_halaman = 'Edit Informasi';
$halaman_admin = 'informasi';
require_once __DIR__ . '/../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM informasi WHERE id=$id");
$info = mysqli_fetch_assoc($result);
if (!$info) redirect(BASE_URL.'admin/informasi/', 'Data tidak ditemukan!', 'danger');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = escape($koneksi, $_POST['judul']);
    $konten_info = escape($koneksi, $_POST['konten']);
    $kategori = escape($koneksi, $_POST['kategori']);
    $penulis = escape($koneksi, $_POST['penulis']);
    $status = escape($koneksi, $_POST['status']);
    $gambar = $info['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['gambar']['size'] < 3*1024*1024) {
            $g_baru = 'info_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], UPLOAD_PATH . $g_baru)) {
                if ($info['gambar'] && file_exists(UPLOAD_PATH . $info['gambar'])) unlink(UPLOAD_PATH . $info['gambar']);
                $gambar = $g_baru;
            }
        }
    }

    $sql = "UPDATE informasi SET judul='$judul', konten='$konten_info', kategori='$kategori', penulis='$penulis', gambar='$gambar', status='$status' WHERE id=$id";
    if (mysqli_query($koneksi, $sql)) redirect(BASE_URL.'admin/informasi/', 'Informasi berhasil diperbarui!');
}
?>

<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-edit me-2"></i>Edit Informasi</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($info['judul']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="berita" <?= $info['kategori']==='berita'?'selected':'' ?>>📰 Berita</option>
                        <option value="pengumuman" <?= $info['kategori']==='pengumuman'?'selected':'' ?>>📣 Pengumuman</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($info['penulis'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" <?= $info['status']==='aktif'?'selected':'' ?>>✅ Aktif</option>
                        <option value="nonaktif" <?= $info['status']==='nonaktif'?'selected':'' ?>>⛔ Non-aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Gambar</label>
                    <?php if ($info['gambar'] && file_exists(UPLOAD_PATH.$info['gambar'])): ?>
                    <div class="mb-2">
                        <img src="<?= UPLOAD_URL.htmlspecialchars($info['gambar']) ?>" style="max-height:120px;border-radius:8px;" alt="">
                        <small class="text-muted ms-2">Gambar saat ini</small>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <textarea name="konten" class="form-control" rows="10" required><?= htmlspecialchars($info['konten']) ?></textarea>
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
