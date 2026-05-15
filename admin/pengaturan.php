<?php
$judul_halaman = 'Pengaturan Sistem';
$halaman_admin = 'pengaturan';
require_once __DIR__ . '/includes/admin_header.php';
cekSuperAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle upload logo
    if (isset($_FILES['logo_sekolah']) && $_FILES['logo_sekolah']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $file_type = $_FILES['logo_sekolah']['type'];
        $file_size = $_FILES['logo_sekolah']['size'];

        if (!in_array($file_type, $allowed_types)) {
            redirect(BASE_URL.'admin/pengaturan.php', 'Format logo tidak didukung! Gunakan JPG, PNG, GIF, WebP, atau SVG.', 'danger');
        } elseif ($file_size > 2 * 1024 * 1024) {
            redirect(BASE_URL.'admin/pengaturan.php', 'Ukuran logo terlalu besar! Maksimal 2MB.', 'danger');
        } else {
            $ext = pathinfo($_FILES['logo_sekolah']['name'], PATHINFO_EXTENSION);
            $new_filename = 'logo_sekolah_' . time() . '.' . $ext;
            $upload_dest = UPLOAD_PATH . $new_filename;

            if (move_uploaded_file($_FILES['logo_sekolah']['tmp_name'], $upload_dest)) {
                // Hapus logo lama jika ada
                $old_logo = getSetting($koneksi, 'logo_sekolah');
                if (!empty($old_logo) && file_exists(UPLOAD_PATH . $old_logo)) {
                    unlink(UPLOAD_PATH . $old_logo);
                }
                $val = escape($koneksi, $new_filename);
                $check = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM pengaturan WHERE kunci='logo_sekolah'"));
                if ($check) {
                    mysqli_query($koneksi, "UPDATE pengaturan SET nilai='$val' WHERE kunci='logo_sekolah'");
                } else {
                    mysqli_query($koneksi, "INSERT INTO pengaturan (kunci, nilai, keterangan) VALUES ('logo_sekolah','$val','Logo Sekolah')");
                }
            } else {
                redirect(BASE_URL.'admin/pengaturan.php', 'Gagal mengupload logo. Pastikan folder uploads/ writable.', 'danger');
            }
        }
    }

    // Handle hapus logo
    if (isset($_POST['hapus_logo'])) {
        $old_logo = getSetting($koneksi, 'logo_sekolah');
        if (!empty($old_logo) && file_exists(UPLOAD_PATH . $old_logo)) {
            unlink(UPLOAD_PATH . $old_logo);
        }
        mysqli_query($koneksi, "UPDATE pengaturan SET nilai='' WHERE kunci='logo_sekolah'");
        redirect(BASE_URL.'admin/pengaturan.php', 'Logo sekolah berhasil dihapus!');
    }

    // Handle upload foto kepala sekolah
    if (isset($_FILES['foto_kepala_sekolah']) && $_FILES['foto_kepala_sekolah']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['foto_kepala_sekolah']['type'];
        $file_size = $_FILES['foto_kepala_sekolah']['size'];

        if (!in_array($file_type, $allowed_types)) {
            redirect(BASE_URL.'admin/pengaturan.php', 'Format foto tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.', 'danger');
        } elseif ($file_size > 2 * 1024 * 1024) {
            redirect(BASE_URL.'admin/pengaturan.php', 'Ukuran foto terlalu besar! Maksimal 2MB.', 'danger');
        } else {
            $ext = pathinfo($_FILES['foto_kepala_sekolah']['name'], PATHINFO_EXTENSION);
            $new_filename = 'foto_kepsek_' . time() . '.' . $ext;
            $upload_dest = UPLOAD_PATH . $new_filename;

            if (move_uploaded_file($_FILES['foto_kepala_sekolah']['tmp_name'], $upload_dest)) {
                $old_foto = getSetting($koneksi, 'foto_kepala_sekolah');
                if (!empty($old_foto) && file_exists(UPLOAD_PATH . $old_foto)) {
                    unlink(UPLOAD_PATH . $old_foto);
                }
                $val = escape($koneksi, $new_filename);
                $check = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM pengaturan WHERE kunci='foto_kepala_sekolah'"));
                if ($check) {
                    mysqli_query($koneksi, "UPDATE pengaturan SET nilai='$val' WHERE kunci='foto_kepala_sekolah'");
                } else {
                    mysqli_query($koneksi, "INSERT INTO pengaturan (kunci, nilai, keterangan) VALUES ('foto_kepala_sekolah','$val','Foto Kepala Sekolah')");
                }
            } else {
                redirect(BASE_URL.'admin/pengaturan.php', 'Gagal mengupload foto kepala sekolah.', 'danger');
            }
        }
    }

    // Handle hapus foto kepsek
    if (isset($_POST['hapus_foto_kepsek'])) {
        $old_foto = getSetting($koneksi, 'foto_kepala_sekolah');
        if (!empty($old_foto) && file_exists(UPLOAD_PATH . $old_foto)) {
            unlink(UPLOAD_PATH . $old_foto);
        }
        mysqli_query($koneksi, "UPDATE pengaturan SET nilai='' WHERE kunci='foto_kepala_sekolah'");
        redirect(BASE_URL.'admin/pengaturan.php', 'Foto kepala sekolah berhasil dihapus!');
    }

    $fields = ['nama_sekolah', 'alamat', 'telp', 'email', 'maps_embed', 'visi', 'misi', 'kepala_sekolah', 'deskripsi_kepala_sekolah'];
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
$logo_sekolah = $settings['logo_sekolah'] ?? '';
$foto_kepala_sekolah = $settings['foto_kepala_sekolah'] ?? '';
$deskripsi_kepala_sekolah = $settings['deskripsi_kepala_sekolah'] ?? 'Memimpin dengan dedikasi tinggi untuk kemajuan pendidikan';
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-cog me-2"></i>Pengaturan Sistem</h5>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>

        <!-- SECTION: Upload Logo -->
        <h6 class="fw-bold mb-3" style="color:var(--biru-dark);"><i class="fas fa-image me-2"></i>Logo Sekolah</h6>
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-3 text-center">
                <?php if (!empty($logo_sekolah)): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($logo_sekolah) ?>" alt="Logo Sekolah"
                         id="logoPreview"
                         style="width:130px;height:130px;object-fit:cover;border-radius:50%;border:4px solid var(--kuning);box-shadow:0 4px 16px rgba(255,215,0,0.4);">
                <?php else: ?>
                    <div id="logoPreview" style="width:130px;height:130px;border:3px dashed #ccc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:#f8f9fa;">
                        <i class="fas fa-school fa-3x text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-9">
                <form method="POST" enctype="multipart/form-data" class="d-inline">
                    <label class="form-label fw-semibold">Upload Logo Baru</label>
                    <div class="input-group mb-2">
                        <input type="file" name="logo_sekolah" id="logoInput" class="form-control"
                               accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               onchange="previewLogo(this)">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Upload</button>
                    </div>
                    <small class="text-muted">Format: JPG, PNG, GIF, WebP, SVG. Maks: 2MB</small>
                </form>
                <?php if (!empty($logo_sekolah)): ?>
                <form method="POST" class="d-inline ms-2" onsubmit="return confirm('Hapus logo sekolah?')">
                    <input type="hidden" name="hapus_logo" value="1">
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash me-1"></i>Hapus Logo</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <hr>

        <!-- SECTION: Upload Foto Kepala Sekolah -->
        <h6 class="fw-bold mb-3" style="color:var(--biru-dark);"><i class="fas fa-user-tie me-2"></i>Foto Kepala Sekolah</h6>
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-3 text-center">
                <?php if (!empty($foto_kepala_sekolah)): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($foto_kepala_sekolah) ?>" alt="Foto Kepala Sekolah"
                         id="fotoKepsekPreview"
                         style="width:130px;height:130px;object-fit:cover;border-radius:50%;border:4px solid var(--biru-muda);box-shadow:0 4px 16px rgba(0,0,0,0.1);">
                <?php else: ?>
                    <div id="fotoKepsekPreview" style="width:130px;height:130px;border:3px dashed #ccc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:#f8f9fa;">
                        <span style="font-size:3rem;">👨‍💼</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-9">
                <form method="POST" enctype="multipart/form-data" class="d-inline">
                    <label class="form-label fw-semibold">Upload Foto Kepala Sekolah</label>
                    <div class="input-group mb-2">
                        <input type="file" name="foto_kepala_sekolah" id="fotoKepsekInput" class="form-control"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               onchange="previewFotoKepsek(this)">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Upload</button>
                    </div>
                    <small class="text-muted">Format: JPG, PNG, GIF, WebP. Maks: 2MB</small>
                </form>
                <?php if (!empty($foto_kepala_sekolah)): ?>
                <form method="POST" class="d-inline ms-2" onsubmit="return confirm('Hapus foto kepala sekolah?')">
                    <input type="hidden" name="hapus_foto_kepsek" value="1">
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash me-1"></i>Hapus Foto</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <hr>
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
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi Kepala Sekolah</label>
                    <textarea name="deskripsi_kepala_sekolah" class="form-control" rows="2"><?= htmlspecialchars($deskripsi_kepala_sekolah) ?></textarea>
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

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('logoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Ganti div placeholder dengan img
                var img = document.createElement('img');
                img.id = 'logoPreview';
                img.src = e.target.result;
                img.alt = 'Preview Logo';
                img.style.cssText = 'width:130px;height:130px;object-fit:cover;border-radius:50%;border:4px solid var(--kuning);box-shadow:0 4px 16px rgba(255,215,0,0.4);';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFotoKepsek(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('fotoKepsekPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Ganti div placeholder dengan img
                var img = document.createElement('img');
                img.id = 'fotoKepsekPreview';
                img.src = e.target.result;
                img.alt = 'Foto Kepala Sekolah';
                img.style.cssText = 'width:130px;height:130px;object-fit:cover;border-radius:50%;border:4px solid var(--biru-muda);box-shadow:0 4px 16px rgba(0,0,0,0.1);';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
