<?php
require_once __DIR__ . '/includes/guest_check.php';
$message = get_flash();
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk • STOK KITA</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css">
</head><body class="auth-body">
<div class="auth-shell"><section class="auth-brand"><a class="brand" href="index.php"><span class="brand-mark">SK</span><span>STOK <b>KITA</b></span></a><div class="brand-copy"><span class="eyebrow">INVENTORY MANAGEMENT</span><h1>Kelola stok,<br><span>lebih terarah.</span></h1><p>Satu sistem untuk membantu usaha mencatat barang masuk, barang keluar, dan memantau persediaan.</p><div class="brand-points"><div><span class="point-icon">✓</span> Pencatatan lebih teratur</div><div><span class="point-icon">✓</span> Pantau stok kapan saja</div><div><span class="point-icon">✓</span> Riwayat tersimpan rapi</div></div></div><div class="brand-footer">STOK KITA · Kelola persediaan dengan mudah</div></section>
<section class="auth-panel"><div class="auth-card"><div class="mobile-brand"><span class="brand-mark">SK</span><b>STOK KITA</b></div><div class="auth-heading"><span class="eyebrow">SELAMAT DATANG KEMBALI</span><h2>Masuk ke akunmu</h2><p>Masukkan username dan password untuk melanjutkan.</p></div>
<?php if ($message): ?><div class="alert <?= e($message['type']) ?>"><?= e($message['message']) ?></div><?php endif; ?>
<form method="post" action="auth/login.php" class="form-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Username<input name="username" type="text" placeholder="Masukkan username" autocomplete="username" required autofocus></label>
<label>Password<div class="password-wrap"><input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required><button type="button" class="toggle-pass" data-toggle="password">Lihat</button></div></label>
<button class="btn btn-primary btn-full" type="submit">Masuk <span>→</span></button>
</form><p class="auth-switch">Belum memiliki akun? <a href="register.php">Daftar sekarang</a></p><p class="auth-legal">Dengan masuk, kamu dapat mengelola persediaan usaha secara mandiri.</p></div></section></div><script src="assets/js/app.js"></script></body></html>
