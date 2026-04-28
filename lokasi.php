<?php
$judul_halaman = 'Lokasi Sekolah';
$halaman_aktif = 'lokasi';
require_once 'includes/header.php';

$alamat = getSetting($koneksi, 'alamat');
$telp = getSetting($koneksi, 'telp');
$email_sk = getSetting($koneksi, 'email');
$maps_embed = getSetting($koneksi, 'maps_embed');
$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
?>

<!-- PAGE HEADER -->
<div style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); padding: 50px 0 30px;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">📍 Lokasi Sekolah</h1>
        <p class="text-muted">Temukan kami dengan mudah</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-start">
            <!-- Google Maps -->
            <div class="col-lg-7">
                <h4 class="fw-bold mb-3">🗺️ Peta Lokasi</h4>
                <div class="maps-container">
                    <iframe 
                        src="<?= htmlspecialchars($maps_embed) ?>" 
                        width="100%" 
                        height="450" 
                        style="border:0; display:block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="mt-3 p-3 rounded-3" style="background: var(--biru-light);">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Klik pada peta untuk melihat rute atau buka di Google Maps untuk navigasi langsung.</small>
                </div>
            </div>

            <!-- Info Alamat -->
            <div class="col-lg-5">
                <h4 class="fw-bold mb-3">📋 Informasi Kontak</h4>
                <div class="alamat-card">
                    <div class="alamat-item">
                        <div class="alamat-icon">🏫</div>
                        <div>
                            <div class="fw-bold mb-1"><?= htmlspecialchars($nama_sekolah) ?></div>
                            <div class="text-muted small">Nama Institusi</div>
                        </div>
                    </div>
                    <div class="alamat-item">
                        <div class="alamat-icon">📍</div>
                        <div>
                            <div class="fw-bold mb-1">Alamat Sekolah</div>
                            <div class="text-muted"><?= htmlspecialchars($alamat) ?></div>
                        </div>
                    </div>
                    <div class="alamat-item">
                        <div class="alamat-icon">📞</div>
                        <div>
                            <div class="fw-bold mb-1">Telepon</div>
                            <div class="text-muted"><a href="tel:<?= $telp ?>"><?= htmlspecialchars($telp) ?></a></div>
                        </div>
                    </div>
                    <div class="alamat-item">
                        <div class="alamat-icon">✉️</div>
                        <div>
                            <div class="fw-bold mb-1">Email</div>
                            <div class="text-muted"><a href="mailto:<?= $email_sk ?>"><?= htmlspecialchars($email_sk) ?></a></div>
                        </div>
                    </div>
                    <div class="alamat-item">
                        <div class="alamat-icon">⏰</div>
                        <div>
                            <div class="fw-bold mb-1">Jam Operasional</div>
                            <div class="text-muted">Senin – Jumat: 07.00 – 15.00 WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="mt-4 d-grid gap-2">
                    <a href="https://www.google.com/maps?q=<?= urlencode($alamat) ?>" target="_blank" class="btn-biru btn">
                        <i class="fas fa-directions me-2"></i>Buka di Google Maps
                    </a>
                    <a href="https://waze.com/ul?q=<?= urlencode($nama_sekolah) ?>" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-map me-2"></i>Buka di Waze
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
