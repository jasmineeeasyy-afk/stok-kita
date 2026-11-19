<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../login.php');
verify_csrf();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
if ($username === '' || $password === '') {
    flash('error', 'Username dan password wajib diisi.');
    redirect('../login.php');
}

$stmt = $pdo->prepare('SELECT id, nama_lengkap, username, password FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    flash('error', 'Username atau password yang kamu masukkan salah.');
    redirect('../login.php');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['nama_lengkap'] = $user['nama_lengkap'];
$_SESSION['username'] = $user['username'];
redirect('../dashboard.php');
