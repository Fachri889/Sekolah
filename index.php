<?php
$judul_halaman = 'Beranda - Website Sekolah';
$halaman_aktif = 'home';
require_once 'includes/header.php';

// Ambil data statistik
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru"))['total'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa"))['total'];
$total_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT kelas) as total FROM siswa"))['total'];
$total_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM informasi WHERE status='aktif'"))['total'];

// Ambil data kelas untuk visualisasi
$result_kelas = mysqli_query($koneksi, "SELECT kelas, COUNT(*) as jumlah FROM siswa GROUP BY kelas ORDER BY kelas");
$data_kelas = [];
while ($row = mysqli_fetch_assoc($result_kelas)) {
    $data_kelas[] = $row;
}

// Ambil informasi terbaru
$result_info = mysqli_query($koneksi, "SELECT * FROM informasi WHERE status='aktif' ORDER BY created_at DESC LIMIT 3");

$visi = getSetting($koneksi, 'visi');
$misi = getSetting($koneksi, 'misi');
$kepala_sekolah = getSetting($koneksi, 'kepala_sekolah');
$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-badge">🏆 Sekolah Unggulan Terbaik</span>
                <h1 class="hero-title">Selamat Datang di <span><?= htmlspecialchars($nama_sekolah) ?></span></h1>
                <p class="hero-subtitle">Kami berkomitmen untuk menghasilkan lulusan yang cerdas, berkarakter, dan siap menghadapi tantangan masa depan. Bergabunglah bersama ribuan siswa berprestasi kami.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="guru.php" class="btn-kuning btn">👨‍🏫 Kenali Guru Kami</a>
                    <a href="informasi.php" class="btn-biru btn">📰 Baca Berita</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card kuning">
                            <div class="stat-icon">👨‍🏫</div>
                            <div class="stat-number" data-target="<?= $total_guru ?>"><?= $total_guru ?></div>
                            <div class="stat-label">Tenaga Pengajar</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card biru">
                            <div class="stat-icon">👩‍🎓</div>
                            <div class="stat-number"><?= $total_siswa ?></div>
                            <div class="stat-label">Total Siswa</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card biru">
                            <div class="stat-icon">🏫</div>
                            <div class="stat-number"><?= $total_kelas ?></div>
                            <div class="stat-label">Kelas Aktif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card kuning">
                            <div class="stat-icon">📰</div>
                            <div class="stat-number"><?= $total_info ?></div>
                            <div class="stat-label">Informasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VISUALISASI KELAS -->
<section class="py-5" style="background: var(--abu);">
    <div class="container">
        <div class="section-title">
            <h2>📊 Distribusi Siswa per Kelas</h2>
            <div class="title-line"></div>
            <p>Visualisasi jumlah siswa di setiap kelas tahun ajaran ini</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="tabel-wrapper p-4">
                    <div class="row g-3 mb-4">
                        <?php foreach ($data_kelas as $kelas): 
                            $persentase = $total_siswa > 0 ? round(($kelas['jumlah'] / $total_siswa) * 100) : 0;
                            $warna = ['#FFD700','#87CEEB','#FFB347','#98D8C8','#FFB6C1','#B0E0E6'];
                            $idx = array_search($kelas, $data_kelas);
                            $color = $warna[$idx % count($warna)];
                        ?>
                        <div class="col-md-4 col-6">
                            <div class="bg-white rounded-3 p-3 shadow-sm h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold"><?= htmlspecialchars($kelas['kelas']) ?></span>
                                    <span class="fw-bold fs-5"><?= $kelas['jumlah'] ?></span>
                                </div>
                                <div class="progress" style="height:10px; border-radius:5px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $persentase ?>%; background: <?= $color ?>; border-radius:5px;"
                                         aria-valuenow="<?= $persentase ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted"><?= $persentase ?>% dari total siswa</small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <canvas id="kelasChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PROFIL SINGKAT -->
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>🏫 Profil Sekolah</h2>
            <div class="title-line"></div>
        </div>
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-4">
                <div class="h-100 p-4 rounded-3 text-center" style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); border: 2px solid var(--biru-muda);">
                    <div style="font-size:5rem; margin-bottom:15px;">👨‍💼</div>
                    <h5 style="color:var(--biru-dark);">Kepala Sekolah</h5>
                    <h4 class="fw-bold"><?= htmlspecialchars($kepala_sekolah) ?></h4>
                    <p class="text-muted small">Memimpin dengan dedikasi tinggi untuk kemajuan pendidikan</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="h-100 p-4 rounded-3" style="background: var(--putih); border: 2px solid var(--kuning); box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="font-size:2rem;">🎯</div>
                        <h5 class="mb-0 fw-bold" style="color:var(--kuning-dark);">Visi</h5>
                    </div>
                    <p style="line-height:1.8; color:var(--teks-gelap);"><?= htmlspecialchars($visi) ?></p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="h-100 p-4 rounded-3" style="background: var(--putih); border: 2px solid var(--biru-muda); box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="font-size:2rem;">📋</div>
                        <h5 class="mb-0 fw-bold" style="color:var(--biru-dark);">Misi</h5>
                    </div>
                    <p style="line-height:1.8; color:var(--teks-gelap);"><?= htmlspecialchars($misi) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- INFORMASI TERBARU -->
<section class="py-5" style="background: var(--abu);">
    <div class="container">
        <div class="section-title">
            <h2>📢 Informasi Terbaru</h2>
            <div class="title-line"></div>
            <p>Berita dan pengumuman terkini dari sekolah</p>
        </div>
        <div class="row g-4">
            <?php while ($info = mysqli_fetch_assoc($result_info)): ?>
            <div class="col-md-4">
                <div class="info-card">
                    <div class="p-4">
                        <span class="badge-kategori badge-<?= $info['kategori'] ?> mb-3 d-inline-block">
                            <?= $info['kategori'] === 'berita' ? '📰 Berita' : '📣 Pengumuman' ?>
                        </span>
                        <h5 class="card-title"><?= htmlspecialchars($info['judul']) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars(substr($info['konten'], 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i><?= date('d M Y', strtotime($info['created_at'])) ?></small>
                            <a href="informasi.php?id=<?= $info['id'] ?>" class="btn btn-sm btn-biru">Baca →</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="informasi.php" class="btn-biru btn">Lihat Semua Informasi</a>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Chart Kelas
const ctx = document.getElementById('kelasChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($k) => '"'.$k['kelas'].'"', $data_kelas)) ?>],
        datasets: [{
            label: 'Jumlah Siswa',
            data: [<?= implode(',', array_column($data_kelas, 'jumlah')) ?>],
            backgroundColor: ['#FFD700','#87CEEB','#FFB347','#98D8C8','#FFB6C1','#B0E0E6'],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Jumlah Siswa per Kelas', font: { size: 14, weight: 'bold' }, color: '#2c3e50' }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
