<?php
// includes/footer.php
$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
$alamat = getSetting($koneksi, 'alamat');
$telp = getSetting($koneksi, 'telp');
$email_sk = getSetting($koneksi, 'email');
?>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5><i class="fas fa-school me-2"></i><?= htmlspecialchars($nama_sekolah) ?></h5>
                <p style="font-size:0.9rem;line-height:1.7;"><?= htmlspecialchars($alamat) ?></p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-warning fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-warning fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-warning fs-5"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-warning fs-5"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <h5>Menu</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>index.php">🏠 Home</a></li>
                    <li><a href="<?= BASE_URL ?>guru.php">👨‍🏫 Guru</a></li>
                    <li><a href="<?= BASE_URL ?>informasi.php">📰 Informasi</a></li>
                    <li><a href="<?= BASE_URL ?>siswa.php">👩‍🎓 Siswa</a></li>
                    <li><a href="<?= BASE_URL ?>lokasi.php">📍 Lokasi</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-sm-6">
                <h5>Informasi</h5>
                <ul class="list-unstyled" style="font-size:0.9rem;">
                    <li class="mb-2"><i class="fas fa-phone text-warning me-2"></i><?= htmlspecialchars($telp) ?></li>
                    <li class="mb-2"><i class="fas fa-envelope text-warning me-2"></i><?= htmlspecialchars($email_sk) ?></li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt text-warning me-2"></i><?= htmlspecialchars(substr($alamat,0,60)) ?>...</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Jam Operasional</h5>
                <ul class="list-unstyled" style="font-size:0.9rem;">
                    <li class="mb-1">📅 Senin – Jumat</li>
                    <li class="mb-1">⏰ 07.00 – 15.00 WIB</li>
                    <li class="mb-1 text-warning">✅ Aktif Melayani</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?= date('Y') ?> <?= htmlspecialchars($nama_sekolah) ?>. Dibuat dengan ❤️ menggunakan PHP & Bootstrap 5.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        const bs = bootstrap.Alert.getOrCreateInstance(a);
        bs.close();
    });
}, 4000);
</script>
</body>
</html>
