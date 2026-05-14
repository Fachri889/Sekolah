<?php
ob_start();
$judul_halaman = 'Edit Kegiatan';
$halaman_admin = 'kegiatan';
require_once __DIR__ . '/../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id=$id");
$kegiatan = mysqli_fetch_assoc($result);

if (!$kegiatan) {
    redirect(BASE_URL.'admin/kegiatan/', 'Data tidak ditemukan!', 'danger');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = escape($koneksi, $_POST['judul']);
    $deskripsi = escape($koneksi, $_POST['deskripsi']);
    $tanggal = escape($koneksi, $_POST['tanggal']);
    $gambar = $kegiatan['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $target_dir = UPLOAD_PATH;
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $_FILES['gambar']['size'] < 3 * 1024 * 1024) {
            $gambar_baru = 'kegiatan_' . time() . '.' . $ext;
            $target_file = $target_dir . $gambar_baru;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // Hapus gambar lama jika ada
                if ($gambar && file_exists(UPLOAD_PATH.$gambar)) {
                    unlink(UPLOAD_PATH.$gambar);
                }
                $gambar = $gambar_baru;
            }
        } else {
            $_SESSION['flash'] = ['pesan' => 'Format gambar tidak valid atau ukuran terlalu besar!', 'tipe' => 'danger'];
        }
    }

    if (empty($judul) || empty($deskripsi) || empty($tanggal)) {
        $_SESSION['flash'] = ['pesan' => 'Judul, deskripsi, dan tanggal wajib diisi!', 'tipe' => 'danger'];
    } else {
        $sql = "UPDATE kegiatan SET 
                judul='$judul', 
                deskripsi='$deskripsi', 
                tanggal='$tanggal', 
                gambar='$gambar' 
                WHERE id=$id";
        
        if (mysqli_query($koneksi, $sql)) {
            redirect(BASE_URL.'admin/kegiatan/', 'Kegiatan berhasil diperbarui!');
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal memperbarui kegiatan: ' . mysqli_error($koneksi), 'tipe' => 'danger'];
        }
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-edit me-2"></i>Edit Kegiatan</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" 
                           value="<?= htmlspecialchars($_POST['judul'] ?? $kegiatan['judul']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jadwal / Waktu <span class="text-danger">*</span></label>
                    <input type="text" name="tanggal" class="form-control" placeholder="Contoh: Setiap Hari Jumat"
                           value="<?= htmlspecialchars($_POST['tanggal'] ?? $kegiatan['tanggal']) ?>" required>
                </div>
                
                <div class="col-12">
                    <label class="form-label fw-semibold">Foto Kegiatan (Biarkan kosong jika tidak ingin mengubah)</label>
                    <?php if ($kegiatan['gambar'] && file_exists(UPLOAD_PATH.$kegiatan['gambar'])): ?>
                    <div class="mb-2">
                        <img src="<?= UPLOAD_URL.$kegiatan['gambar'] ?>" alt="Preview" style="height:100px;border-radius:8px;object-fit:cover;">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG/PNG/WebP, maks. 3MB.</small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control" rows="8" required><?= htmlspecialchars($_POST['deskripsi'] ?? $kegiatan['deskripsi']) ?></textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-biru btn"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
