<?php
$judul_halaman = 'Tambah Guru';
$halaman_admin = 'guru';

// 1. Load koneksi dan session tanpa output HTML
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../koneksi.php'; 
cekLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($koneksi, $_POST['nama']);
    $mapel = escape($koneksi, $_POST['mata_pelajaran']);
    $nip = escape($koneksi, $_POST['nip']);
    $email = escape($koneksi, $_POST['email']);
    $telp = escape($koneksi, $_POST['telp']);
    $foto = '';

    // Upload foto
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        
        // Perbaikan Path: Pastikan folder 'uploads/guru/' tersedia
        $target_dir = UPLOAD_PATH . 'guru/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (in_array($ext, $allowed) && $_FILES['foto']['size'] < 2*1024*1024) {
            $foto = 'guru_' . time() . '_' . rand(100,999) . '.' . $ext;
            $tujuan = $target_dir . $foto;
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) $foto = '';
        }
    }

    if (empty($nama) || empty($mapel)) {
        $_SESSION['flash'] = ['pesan' => 'Nama dan mata pelajaran wajib diisi!', 'tipe' => 'danger'];
    } else {
        $sql = "INSERT INTO guru (nama, mata_pelajaran, nip, email, telp, foto) 
                VALUES ('$nama','$mapel','$nip','$email','$telp','$foto')";
        if (mysqli_query($koneksi, $sql)) {
            redirect(BASE_URL.'admin/guru/', 'Guru berhasil ditambahkan!');
            exit; // Pastikan berhenti di sini agar redirect bekerja
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal menyimpan: ' . mysqli_error($koneksi), 'tipe' => 'danger'];
        }
    }
}

// 2. Baru panggil header setelah semua logika redirect selesai
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-plus me-2"></i>Tambah Guru Baru</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso, S.Pd" 
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika" 
                           value="<?= htmlspecialchars($_POST['mata_pelajaran'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIP</label>
                    <input type="text" name="nip" class="form-control" placeholder="Nomor Induk Pegawai" 
                           value="<?= htmlspecialchars($_POST['nip'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@sekolah.sch.id" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telp" class="form-control" placeholder="08xx-xxxx-xxxx" 
                           value="<?= htmlspecialchars($_POST['telp'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted">Format: JPG/PNG/WebP, maks. 2MB</small>
                    <div id="fotoPreview" class="mt-2" style="display:none;">
                        <img id="previewImg" src="" style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:2px solid var(--biru-muda);">
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Simpan Guru</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
