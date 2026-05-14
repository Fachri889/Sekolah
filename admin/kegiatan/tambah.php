<?php
ob_start();
$judul_halaman = 'Tambah Kegiatan';
$halaman_admin = 'kegiatan';
require_once __DIR__ . '/../includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = escape($koneksi, $_POST['judul']);
    $deskripsi = escape($koneksi, $_POST['deskripsi']);
    $tanggal = escape($koneksi, $_POST['tanggal']);
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $target_dir = UPLOAD_PATH;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $_FILES['gambar']['size'] < 3 * 1024 * 1024) {
            $gambar = 'kegiatan_' . time() . '.' . $ext;
            $target_file = $target_dir . $gambar;

            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar = '';
            }
        } else {
            $_SESSION['flash'] = ['pesan' => 'Format gambar tidak valid atau ukuran terlalu besar!', 'tipe' => 'danger'];
            header('Location: tambah.php');
            exit;
        }
    }

    if (empty($judul) || empty($deskripsi) || empty($tanggal)) {
        $_SESSION['flash'] = ['pesan' => 'Judul, Jadwal, dan deskripsi wajib diisi!', 'tipe' => 'danger'];
    } else {
        $sql = "INSERT INTO kegiatan (judul, deskripsi, tanggal, gambar) 
                VALUES ('$judul','$deskripsi','$tanggal','$gambar')";
        if (mysqli_query($koneksi, $sql)) {
            redirect(BASE_URL . 'admin/kegiatan/', 'Kegiatan berhasil ditambahkan!');
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal menambahkan kegiatan: ' . mysqli_error($koneksi), 'tipe' => 'danger'];
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card-admin">
            <div class="card-header-admin">
                <h5><i class="fas fa-plus me-2"></i>Tambah Kegiatan Sekolah</h5>
                <a href="./" class="btn btn-sm btn-light">← Kembali</a>
            </div>
            <div class="p-4">
                <?php flashMessage(); ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Judul Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control"
                                placeholder="Contoh: Lomba 17 Agustus, Study Tour, dll"
                                value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jadwal / Waktu <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="tanggal" class="form-control"
                                placeholder="Contoh: Setiap Hari Jumat"
                                value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Foto Kegiatan</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG/PNG/WebP, maks. 3MB. Rekomendasi rasio
                                lanskap.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi Kegiatan <span
                                    class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control" rows="8"
                                placeholder="Ceritakan detail kegiatan di sini..."
                                required><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Simpan
                            Kegiatan</button>
                        <a href="./" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>