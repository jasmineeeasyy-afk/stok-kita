<?php
require_once __DIR__ . '/includes/guest_check.php';
$message = get_flash();
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar • STOK KITA</title><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css">
</head><body class="auth-body"><div class="auth-shell"><section class="auth-brand"><a class="brand" href="index.php"><span class="brand-mark">SK</span><span>STOK <b>KITA</b></span></a><div class="brand-copy"><span class="eyebrow">MULAI LEBIH TERATUR</span><h1>Usahamu,<br><span>dalam kendali.</span></h1><p>Buat akun STOK KITA dan mulai kelola persediaan dalam satu tempat.</p><div class="brand-points"><div><span class="point-icon">✓</span> Akun milikmu sendiri</div><div><span class="point-icon">✓</span> Data persediaan terpisah</div><div><span class="point-icon">✓</span> Akses kapan saja</div></div></div><div class="brand-footer">STOK KITA · Kelola persediaan dengan mudah</div></section>
<section class="auth-panel"><div class="auth-card"><div class="mobile-brand"><span class="brand-mark">SK</span><b>STOK KITA</b></div><div class="auth-heading"><span class="eyebrow">BUAT AKUN BARU</span><h2>Daftar STOK KITA</h2><p>Isi data berikut untuk membuat akun pribadimu.</p></div>
<?php if ($message): ?><div class="alert <?= e($message['type']) ?>"><?= e($message['message']) ?></div><?php endif; ?>
<form method="post" action="auth/register.php" class="form-stack" id="registerForm">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Nama lengkap<input name="nama_lengkap" type="text" placeholder="Nama lengkap" maxlength="100" required></label>
<label>Username<input name="username" type="text" placeholder="Buat username (4–30 karakter)" minlength="4" maxlength="30" pattern="[A-Za-z0-9_.-]+" required><small>Gunakan huruf, angka, titik, garis bawah, atau tanda hubung.</small></label>
<label>Password<div class="password-wrap"><input id="password" name="password" type="password" placeholder="Minimal 8 karakter" minlength="8" required><button type="button" class="toggle-pass" data-toggle="password">Lihat</button></div></label>
<label>Konfirmasi password<div class="password-wrap"><input id="confirmPassword" name="konfirmasi_password" type="password" placeholder="Ulangi password" minlength="8" required><button type="button" class="toggle-pass" data-toggle="confirmPassword">Lihat</button></div></label>
<button class="btn btn-primary btn-full" type="submit">Buat akun <span>→</span></button>
</form><p class="auth-switch">Sudah memiliki akun? <a href="login.php">Masuk sekarang</a></p><p class="auth-legal">Password dienkripsi sebelum disimpan ke database.</p></div></section></div><script src="assets/js/app.js"></script></body></html>
