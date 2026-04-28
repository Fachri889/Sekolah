<?php
ob_start();
$judul_halaman = 'Tambah Informasi';
$halaman_admin = 'informasi';
require_once __DIR__ . '/../includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = escape($koneksi, $_POST['judul']);
    $konten_info = escape($koneksi, $_POST['konten']);
    $kategori = escape($koneksi, $_POST['kategori']);
    $penulis = escape($koneksi, $_POST['penulis']);
    $status = escape($koneksi, $_POST['status']);
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        
        // 1. Tentukan folder tujuan (2 tingkat ke atas dari file ini ke folder 'uploads')
        $target_dir = __DIR__ . '/../../uploads/';
        
        // 2. Cek apakah folder 'uploads' ada, jika tidak, buat otomatis
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $_FILES['gambar']['size'] < 3 * 1024 * 1024) {
            $gambar = 'info_' . time() . '.' . $ext;
            $target_file = $target_dir . $gambar;

            // 3. Pindahkan file
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar = '';
            }
        }
    }

    if (empty($judul) || empty($konten_info)) {
        $_SESSION['flash'] = ['pesan' => 'Judul dan konten wajib diisi!', 'tipe' => 'danger'];
    } else {
        $sql = "INSERT INTO informasi (judul, konten, kategori, penulis, gambar, status) 
                VALUES ('$judul','$konten_info','$kategori','$penulis','$gambar','$status')";
        if (mysqli_query($koneksi, $sql)) redirect(BASE_URL.'admin/informasi/', 'Informasi berhasil ditambahkan!');
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-plus me-2"></i>Tambah Informasi/Berita</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" placeholder="Judul berita atau pengumuman" 
                           value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="berita" <?= (($_POST['kategori']??'')==='berita')?'selected':'' ?>>📰 Berita</option>
                        <option value="pengumuman" <?= (($_POST['kategori']??'')==='pengumuman')?'selected':'' ?>>📣 Pengumuman</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($_POST['penulis'] ?? $_SESSION['nama_lengkap']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif">✅ Aktif</option>
                        <option value="nonaktif">⛔ Non-aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG/PNG/WebP, maks. 3MB</small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <textarea name="konten" class="form-control" rows="10" placeholder="Tulis konten berita atau pengumuman di sini..." required><?= htmlspecialchars($_POST['konten'] ?? '') ?></textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Publikasikan</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
