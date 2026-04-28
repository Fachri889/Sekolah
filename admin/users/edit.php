<?php
$judul_halaman = 'Edit Admin';
$halaman_admin = 'users';
require_once __DIR__ . '/../includes/admin_header.php';
cekSuperAdmin();

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
if (!$user) redirect(BASE_URL.'admin/users/', 'Akun tidak ditemukan!', 'danger');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($koneksi, $_POST['username']);
    $nama_lengkap = escape($koneksi, $_POST['nama_lengkap']);
    $role = escape($koneksi, $_POST['role']);
    $password = $_POST['password'];

    // Cek duplikat
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username' AND id!=$id"));
    if ($cek) {
        $_SESSION['flash'] = ['pesan' => 'Username sudah digunakan!', 'tipe' => 'danger'];
    } else {
        if (!empty($password) && strlen($password) >= 6) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', role='$role', password='$hash' WHERE id=$id";
        } else {
            $sql = "UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', role='$role' WHERE id=$id";
        }
        if (mysqli_query($koneksi, $sql)) {
            // Update session jika mengubah akun sendiri
            if ($id == $_SESSION['user_id']) {
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
            }
            redirect(BASE_URL.'admin/users/', 'Akun berhasil diperbarui!');
        }
    }
}
?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header-admin">
        <h5><i class="fas fa-user-edit me-2"></i>Edit Akun Admin</h5>
        <a href="./" class="btn btn-sm btn-light">← Kembali</a>
    </div>
    <div class="p-4">
        <?php flashMessage(); ?>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select">
                        <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>🛡️ Admin</option>
                        <option value="superadmin" <?= $user['role']==='superadmin'?'selected':'' ?>>⭐ Super Admin</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    <small class="text-muted">Min. 6 karakter, kosongkan jika tidak ingin mengubah password</small>
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
