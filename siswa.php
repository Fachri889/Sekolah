<?php
$judul_halaman = 'Data Siswa';
$halaman_aktif = 'siswa';
require_once 'includes/header.php';

// Ambil daftar kelas
$result_kelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");
$daftar_kelas = [];
while ($row = mysqli_fetch_assoc($result_kelas)) {
    $daftar_kelas[] = $row['kelas'];
}

// Filter
$kelas_filter = isset($_GET['kelas']) ? escape($koneksi, $_GET['kelas']) : '';
$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';

$where = "WHERE 1=1";
if ($kelas_filter && in_array($kelas_filter, $daftar_kelas)) $where .= " AND kelas='$kelas_filter'";
if ($search) $where .= " AND (nama LIKE '%$search%' OR nis LIKE '%$search%')";

// Pagination
$per_page = 15;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

$total_result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa $where");
$total_siswa = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_siswa / $per_page);

$result_siswa = mysqli_query($koneksi, "SELECT * FROM siswa $where ORDER BY kelas, nama LIMIT $per_page OFFSET $offset");
?>

<!-- PAGE HEADER -->
<div style="background: linear-gradient(135deg, var(--biru-light), var(--kuning-light)); padding: 50px 0 30px;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Data Siswa</h1>
        <p class="text-muted">Daftar lengkap siswa aktif per kelas</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <!-- Filter Kelas -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="kelas-filter">
                <a href="siswa.php" class="kelas-btn <?= !$kelas_filter ? 'active' : '' ?>">Semua Kelas</a>
                <?php foreach ($daftar_kelas as $kls): ?>
                <a href="?kelas=<?= urlencode($kls) ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                   class="kelas-btn <?= $kelas_filter===$kls ? 'active' : '' ?>"><?= htmlspecialchars($kls) ?></a>
                <?php endforeach; ?>
            </div>
            <form method="GET" class="d-flex gap-2">
                <?php if ($kelas_filter): ?><input type="hidden" name="kelas" value="<?= htmlspecialchars($kelas_filter) ?>"><?php endif; ?>
                <input type="text" name="search" class="form-control" placeholder="Cari nama / NIS..." value="<?= htmlspecialchars($search) ?>" style="border-color:var(--biru-muda);">
                <button type="submit" class="btn-biru btn">🔍</button>
                <?php if ($search): ?><a href="siswa.php<?= $kelas_filter ? '?kelas='.$kelas_filter : '' ?>" class="btn btn-outline-secondary">✕</a><?php endif; ?>
            </form>
        </div>

        <!-- Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted mb-0">
                Menampilkan <strong><?= $total_siswa ?></strong> siswa
                <?= $kelas_filter ? "di kelas <strong>$kelas_filter</strong>" : '' ?>
            </p>
        </div>

        <!-- Tabel -->
        <div class="tabel-wrapper">
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $offset + 1;
                        $count = 0;
                        while ($siswa = mysqli_fetch_assoc($result_siswa)):
                            $count++;
                        ?>
                        <tr>
                            <td class="text-muted"><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($siswa['nis']) ?></code></td>
                            <td><strong><?= htmlspecialchars($siswa['nama']) ?></strong></td>
                            <td><span class="kelas-badge"><?= htmlspecialchars($siswa['kelas']) ?></span></td>
                            <td>
                                <span class="badge badge-<?= strtolower($siswa['jenis_kelamin']) ?>" 
                                      style="padding:4px 12px;border-radius:12px;font-size:0.8rem;
                                             background:<?= $siswa['jenis_kelamin']==='L' ? 'var(--biru-light)' : '#fce4ec' ?>;
                                             color:<?= $siswa['jenis_kelamin']==='L' ? 'var(--biru-dark)' : '#c2185b' ?>;">
                                    <?= $siswa['jenis_kelamin']==='L' ? ' Laki-laki' : ' Perempuan' ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= $siswa['alamat'] ? htmlspecialchars(substr($siswa['alamat'],0,40)).'...' : '-' ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($count === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div style="font-size:3rem;">😔</div>
                                <p class="text-muted">Tidak ada data siswa</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page<=1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page-1 ?><?= $kelas_filter ? '&kelas='.$kelas_filter : '' ?><?= $search ? '&search='.$search : '' ?>">← Prev</a>
                </li>
                <?php for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
                <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $kelas_filter ? '&kelas='.$kelas_filter : '' ?><?= $search ? '&search='.$search : '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page>=$total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page+1 ?><?= $kelas_filter ? '&kelas='.$kelas_filter : '' ?><?= $search ? '&search='.$search : '' ?>">Next →</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
