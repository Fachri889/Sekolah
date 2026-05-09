<?php
// tambah.php - Tambah Siswa
$judul_halaman = 'Tambah Siswa';
$halaman_admin = 'siswa';
require_once __DIR__ . '/../includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($koneksi, $_POST['nama']);
    $kelas = escape($koneksi, $_POST['kelas']);
    $jk = escape($koneksi, $_POST['jenis_kelamin']);
    $alamat = escape($koneksi, $_POST['alamat']);
    $telp = escape($koneksi, $_POST['telp']);

    if (empty($nama) || empty($kelas)) {
        $_SESSION['flash'] = ['pesan' => 'Nama dan Kelas wajib diisi!', 'tipe' => 'danger'];
    } else {
        $sql = "INSERT INTO siswa (nama, kelas, jenis_kelamin, alamat, telp) VALUES ('$nama','$kelas','$jk','$alamat','$telp')";
        if (mysqli_query($koneksi, $sql)) redirect(BASE_URL.'admin/siswa/', 'Siswa berhasil ditambahkan!');
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-plus me-2"></i>Tambah Siswa Baru</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelas_list = ['Kelas 1','Kelas 2','Kelas 3','Kelas 4','Kelas 5','Kelas 6'];
                        foreach ($kelas_list as $k):
                        ?>
                        <option value="<?= $k ?>" <?= (($_POST['kelas']??'')===$k)?'selected':'' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" <?= (($_POST['jenis_kelamin']??'')==='L')?'selected':'' ?>>👦 Laki-laki</option>
                        <option value="P" <?= (($_POST['jenis_kelamin']??'')==='P')?'selected':'' ?>>👧 Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($_POST['telp'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap siswa"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Simpan Siswa</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
