<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../register.php');
verify_csrf();

$name = trim($_POST['nama_lengkap'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['konfirmasi_password'] ?? '';

if ($name === '' || $username === '' || $password === '' || $confirm === '') {
    flash('error', 'Semua kolom wajib diisi.');
    redirect('../register.php');
}
if (mb_strlen($name) > 100 || !preg_match('/^[a-zA-Z0-9_.-]{4,30}$/', $username)) {
    flash('error', 'Nama terlalu panjang atau format username tidak valid (4–30 karakter, huruf/angka/._-).');
    redirect('../register.php');
}
if (strlen($password) < 8) {
    flash('error', 'Password minimal 8 karakter.');
    redirect('../register.php');
}
if ($password !== $confirm) {
    flash('error', 'Konfirmasi password tidak sama.');
    redirect('../register.php');
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    flash('error', 'Username sudah digunakan. Silakan pilih username lain.');
    redirect('../register.php');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)');
$stmt->execute([$name, $username, $hash]);
flash('success', 'Akun berhasil dibuat. Silakan masuk menggunakan akun baru.');
redirect('../login.php');
