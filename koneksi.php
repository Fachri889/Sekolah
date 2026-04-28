<?php
// ============================================
// KONFIGURASI DATABASE
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_sekolah_sdn03');

// Koneksi MySQLi
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$koneksi) {
    die("<div style='font-family:sans-serif;color:red;padding:20px;'>
        <h3>Koneksi Database Gagal!</h3>
        <p>Error: " . mysqli_connect_error() . "</p>
        <p>Pastikan MySQL berjalan dan konfigurasi di <code>koneksi.php</code> sudah benar.</p>
    </div>");
}

mysqli_set_charset($koneksi, 'utf8mb4');

// ============================================
// FUNGSI HELPER
// ============================================

/**
 * Escape string untuk keamanan SQL
 */
function escape($koneksi, $data) {
    return mysqli_real_escape_string($koneksi, trim($data));
}

/**
 * Ambil pengaturan sistem
 */
function getSetting($koneksi, $kunci) {
    $kunci = escape($koneksi, $kunci);
    $result = mysqli_query($koneksi, "SELECT nilai FROM pengaturan WHERE kunci='$kunci'");
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['nilai'];
    }
    return '';
}

/**
 * Redirect dengan pesan
 */
function redirect($url, $pesan = '', $tipe = 'success') {
    if ($pesan) {
        $_SESSION['flash'] = ['pesan' => $pesan, 'tipe' => $tipe];
    }
    header("Location: $url");
    exit;
}

/**
 * Tampilkan flash message
 */
function flashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $tipe = $flash['tipe'] === 'success' ? 'success' : 'danger';
        echo "<div class='alert alert-{$tipe} alert-dismissible fade show' role='alert'>
                {$flash['pesan']}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        unset($_SESSION['flash']);
    }
}

/**
 * Cek login
 */
function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

/**
 * Cek Super Admin
 */
function cekSuperAdmin() {
    cekLogin();
    if ($_SESSION['role'] !== 'superadmin') {
        header('Location: ' . BASE_URL . 'admin/dashboard.php');
        exit;
    }
}

define('BASE_URL', '/sekolah/');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');
