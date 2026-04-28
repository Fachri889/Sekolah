<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($koneksi, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];

            header('Location: ' . BASE_URL . 'admin/dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}

$nama_sekolah = getSetting($koneksi, 'nama_sekolah');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= htmlspecialchars($nama_sekolah) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="login-logo">🏫</div>
            <h2>Panel Admin</h2>
            <p><?= htmlspecialchars($nama_sekolah) ?></p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--biru-light); border-color:var(--biru-muda);">
                            <i class="fas fa-user" style="color:var(--biru-dark);"></i>
                        </span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" 
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               style="border-color:var(--biru-muda);" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--biru-light); border-color:var(--biru-muda);">
                            <i class="fas fa-lock" style="color:var(--biru-dark);"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput" class="form-control" 
                               placeholder="Masukkan password" style="border-color:var(--biru-muda);" required>
                        <button type="button" class="input-group-text" onclick="togglePassword()" 
                                style="background:var(--biru-light); border-color:var(--biru-muda); cursor:pointer;">
                            <i class="fas fa-eye" id="eyeIcon" style="color:var(--biru-dark);"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-biru btn w-100 py-2 fs-6">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Panel Admin
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>index.php" class="text-muted small">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Halaman Utama
                </a>
            </div>

            <!-- Hint kredensial default -->
            <!-- <div class="mt-4 p-3 rounded-3" style="background:var(--kuning-light); border:1px solid var(--kuning);">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1" style="color:var(--kuning-dark);"></i>
                    <strong>Default login:</strong><br>
                    Super Admin: <code>superadmin</code> / <code>password</code><br>
                    Admin: <code>admin</code> / <code>password</code>
                </small>
            </div> -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
</body>
</html>
