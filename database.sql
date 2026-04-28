-- ============================================
-- DATABASE WEBSITE SEKOLAH
-- ============================================

CREATE DATABASE IF NOT EXISTS db_sekolah_sdn03 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_sekolah_sdn03;

-- Tabel Users (Admin & Super Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Guru
CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    mata_pelajaran VARCHAR(100) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    nip VARCHAR(30) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    telp VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Siswa
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nis VARCHAR(20) NOT NULL UNIQUE,
    kelas VARCHAR(20) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT DEFAULT NULL,
    telp VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Informasi (Berita & Pengumuman)
CREATE TABLE IF NOT EXISTS informasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    kategori ENUM('berita', 'pengumuman') NOT NULL DEFAULT 'berita',
    gambar VARCHAR(255) DEFAULT NULL,
    penulis VARCHAR(100) DEFAULT NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Pengaturan Sistem
CREATE TABLE IF NOT EXISTS pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kunci VARCHAR(50) NOT NULL UNIQUE,
    nilai TEXT,
    keterangan VARCHAR(200)
);

-- ============================================
-- DATA AWAL (SEED)
-- ============================================

-- Super Admin default (password: superadmin123)
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'superadmin'),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');
-- Default password untuk keduanya: "password"
-- Gunakan password_hash() di PHP untuk generate hash baru

-- Data Guru Contoh
INSERT INTO guru (nama, mata_pelajaran, nip, email) VALUES

-- Data Siswa Contoh
INSERT INTO siswa (nama, nis, kelas, jenis_kelamin, alamat) VALUES

-- Data Informasi Contoh
INSERT INTO informasi (judul, konten, kategori, penulis, status) VALUES

-- Pengaturan Sistem
INSERT INTO pengaturan (kunci, nilai, keterangan) VALUES

