<?php
$judul_halaman = 'Kelola Siswa';
$halaman_admin = 'siswa';
require_once __DIR__ . '/../includes/admin_header.php';

$search = isset($_GET['search']) ? escape($koneksi, $_GET['search']) : '';
$kelas_filter = isset($_GET['kelas']) ? escape($koneksi, $_GET['kelas']) : '';
$result_kelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");
$daftar_kelas = [];
while ($row = mysqli_fetch_assoc($result_kelas)) $daftar_kelas[] = $row['kelas'];

$where = "WHERE 1=1";
if ($search) $where .= " AND (nama LIKE '%$search%' OR nis LIKE '%$search%')";
if ($kelas_filter && in_array($kelas_filter, $daftar_kelas)) $where .= " AND kelas='$kelas_filter'";

$per_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;
$total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM siswa $where"))['t'];
$total_pages = ceil($total / $per_page);
$result = mysqli_query($koneksi, "SELECT * FROM siswa $where ORDER BY kelas, nama LIMIT $per_page OFFSET $offset");
?>

<div class="card-admin">
    <div class="card-header-admin">
        <h5>Daftar Siswa (<?= $total ?>)</h5>
        <a href="tambah.php" class="btn btn-sm btn-kuning"><i class="fas fa-plus me-1"></i>Tambah Siswa</a>
    </div>
    <div class="p-3">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <form method="GET" class="d-flex gap-2">
                <select name="kelas" class="form-select" style="width:auto; border-color:var(--biru-muda);" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($daftar_kelas as $kls): ?>
                    <option value="<?= $kls ?>" <?= $kelas_filter===$kls?'selected':'' ?>><?= $kls ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search" class="form-control" placeholder="Cari nama/NIS..." value="<?= htmlspecialchars($search) ?>" style="max-width:220px; border-color:var(--biru-muda);">
                <button type="submit" class="btn-biru btn">Cari</button>
                <?php if ($search || $kelas_filter): ?><a href="." class="btn btn-outline-secondary">Reset</a><?php endif; ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>J/K</th><th>Alamat</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=$offset+1; while ($s = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><code><?= htmlspecialchars($s['nis']) ?></code></td>
                        <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                        <td><span class="kelas-badge"><?= htmlspecialchars($s['kelas']) ?></span></td>
                        <td><span style="padding:3px 10px;border-radius:12px;font-size:0.8rem;background:<?= $s['jenis_kelamin']==='L'?'var(--biru-light)':'#fce4ec' ?>;color:<?= $s['jenis_kelamin']==='L'?'var(--biru-dark)':'#c2185b' ?>;"><?= $s['jenis_kelamin']==='L'?'👦 L':'👧 P' ?></span></td>
                        <td class="text-muted small"><?= $s['alamat'] ? htmlspecialchars(substr($s['alamat'],0,35)).'...' : '-' ?></td>
                        <td>
                            <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-biru"><i class="fas fa-edit"></i></a>
                            <a href="hapus.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total_pages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination pagination-sm justify-content-center mb-0">
                <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?= $i==$page?'active':'' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $kelas_filter?'&kelas='.$kelas_filter:'' ?><?= $search?'&search='.$search:'' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
