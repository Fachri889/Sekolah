<?php
$judul_halaman = 'Edit Siswa';
$halaman_admin = 'siswa';
require_once __DIR__ . '/../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id=$id");
$siswa = mysqli_fetch_assoc($result);
if (!$siswa) redirect(BASE_URL.'admin/siswa/', 'Data tidak ditemukan!', 'danger');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($koneksi, $_POST['nama']);
    $nis = escape($koneksi, $_POST['nis']);
    $kelas = escape($koneksi, $_POST['kelas']);
    $jk = escape($koneksi, $_POST['jenis_kelamin']);
    $alamat = escape($koneksi, $_POST['alamat']);
    $telp = escape($koneksi, $_POST['telp']);

    // Cek duplikat NIS (kecuali diri sendiri)
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM siswa WHERE nis='$nis' AND id!=$id"));
    if ($cek) {
        $_SESSION['flash'] = ['pesan' => 'NIS sudah digunakan siswa lain!', 'tipe' => 'danger'];
    } else {
        $sql = "UPDATE siswa SET nama='$nama', nis='$nis', kelas='$kelas', jenis_kelamin='$jk', alamat='$alamat', telp='$telp' WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) redirect(BASE_URL.'admin/siswa/', 'Data siswa berhasil diperbarui!');
    }
}

$kelas_list = ['Kelas 1','Kelas 2','Kelas 3','Kelas 4','Kelas 5','Kelas 6'];
// tambahkan kelas yang ada di DB tapi tidak di list
$kelas_db_res = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");
while ($row = mysqli_fetch_assoc($kelas_db_res)) {
    if (!in_array($row['kelas'], $kelas_list)) $kelas_list[] = $row['kelas'];
}
sort($kelas_list);
?>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-edit me-2"></i>Edit Siswa</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($siswa['nama']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($siswa['nis']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" class="form-select" required>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k ?>" <?= $siswa['kelas']===$k?'selected':'' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L" <?= $siswa['jenis_kelamin']==='L'?'selected':'' ?>>👦 Laki-laki</option>
                        <option value="P" <?= $siswa['jenis_kelamin']==='P'?'selected':'' ?>>👧 Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($siswa['telp'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
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
