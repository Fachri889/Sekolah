<?php
$judul_halaman = 'Tambah Admin';
$halaman_admin = 'users';
require_once __DIR__ . '/../includes/admin_header.php';
cekSuperAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($koneksi, $_POST['username']);
    $nama_lengkap = escape($koneksi, $_POST['nama_lengkap']);
    $password = $_POST['password'];
    $role = escape($koneksi, $_POST['role']);

    $errors = [];
    if (empty($username)) $errors[] = 'Username wajib diisi';
    if (empty($nama_lengkap)) $errors[] = 'Nama lengkap wajib diisi';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if (!in_array($role, ['admin','superadmin'])) $errors[] = 'Role tidak valid';

    // Cek duplikat username
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'"));
    if ($cek) $errors[] = 'Username sudah digunakan!';

    if (!empty($errors)) {
        $_SESSION['flash'] = ['pesan' => implode('<br>', $errors), 'tipe' => 'danger'];
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username','$hash','$nama_lengkap','$role')";
        if (mysqli_query($koneksi, $sql)) redirect(BASE_URL.'admin/users/', 'Akun admin berhasil ditambahkan!');
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-user-plus me-2"></i>Tambah Akun Admin</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="username_unik" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="admin" <?= (($_POST['role']??'')==='admin')?'selected':'' ?>>🛡️ Admin</option>
                        <option value="superadmin" <?= (($_POST['role']??'')==='superadmin')?'selected':'' ?>>⭐ Super Admin</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap admin" 
                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" id="konfirm" class="form-control" placeholder="Ulangi password">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-kuning btn"><i class="fas fa-save me-2"></i>Buat Akun</button>
                <a href="./" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const pass = document.querySelector('input[name="password"]').value;
    const konfirm = document.getElementById('konfirm').value;
    if (konfirm && pass !== konfirm) {
        e.preventDefault();
        alert('Password dan konfirmasi tidak cocok!');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
