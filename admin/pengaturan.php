<?php
$judul_halaman = 'Pengaturan Sistem';
$halaman_admin = 'pengaturan';
require_once __DIR__ . '/includes/admin_header.php';
cekSuperAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['nama_sekolah', 'alamat', 'telp', 'email', 'maps_embed', 'visi', 'misi', 'kepala_sekolah'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $val = escape($koneksi, $_POST[$field]);
            $check = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM pengaturan WHERE kunci='$field'"));
            if ($check) {
                mysqli_query($koneksi, "UPDATE pengaturan SET nilai='$val' WHERE kunci='$field'");
            } else {
                mysqli_query($koneksi, "INSERT INTO pengaturan (kunci, nilai) VALUES ('$field','$val')");
            }
        }
    }
    redirect(BASE_URL.'admin/pengaturan.php', 'Pengaturan berhasil disimpan!');
}

// Ambil semua pengaturan
$settings = [];
$result = mysqli_query($koneksi, "SELECT kunci, nilai FROM pengaturan");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['kunci']] = $row['nilai'];
}
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-cog me-2"></i>Pengaturan Sistem</h5>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST">
            <h6 class="fw-bold mb-3" style="color:var(--biru-dark);"><i class="fas fa-school me-2"></i>Informasi Sekolah</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" value="<?= htmlspecialchars($settings['nama_sekolah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah" class="form-control" value="<?= htmlspecialchars($settings['kepala_sekolah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($settings['telp'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($settings['email'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($settings['alamat'] ?? '') ?></textarea>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3 mt-4" style="color:var(--biru-dark);"><i class="fas fa-map me-2"></i>Lokasi & Peta</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Google Maps Embed URL</label>
                <textarea name="maps_embed" class="form-control" rows="3" placeholder="Salin URL dari Google Maps > Bagikan > Sematkan Peta"><?= htmlspecialchars($settings['maps_embed'] ?? '') ?></textarea>
                <small class="text-muted">Buka Google Maps → Cari lokasi sekolah → Bagikan → Sematkan peta → Salin src="..." dari kode iframe</small>
            </div>

            <hr>
            <h6 class="fw-bold mb-3 mt-4" style="color:var(--biru-dark);"><i class="fas fa-star me-2"></i>Visi & Misi</h6>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold">Visi</label>
                    <textarea name="visi" class="form-control" rows="3"><?= htmlspecialchars($settings['visi'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Misi</label>
                    <textarea name="misi" class="form-control" rows="3"><?= htmlspecialchars($settings['misi'] ?? '') ?></textarea>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Simpan Semua Pengaturan</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
