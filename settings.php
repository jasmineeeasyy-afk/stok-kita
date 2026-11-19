<?php
require_once __DIR__.'/includes/auth_check.php';require_once __DIR__.'/config/database.php';$uid=current_user_id();$s=$pdo->prepare('SELECT nama_lengkap,username FROM users WHERE id=?');$s->execute([$uid]);$user=$s->fetch();
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=trim($_POST['nama_lengkap']??'');$username=trim($_POST['username']??'');$new=$_POST['password_baru']??'';$confirm=$_POST['konfirmasi_password']??'';
if($name===''||!preg_match('/^[a-zA-Z0-9_.-]{4,30}$/',$username)){flash('error','Nama dan format username wajib valid.');redirect('settings.php');}
$check=$pdo->prepare('SELECT id FROM users WHERE username=? AND id<>?');$check->execute([$username,$uid]);if($check->fetch()){flash('error','Username sudah digunakan.');redirect('settings.php');}
if($new!==''&&strlen($new)<8){flash('error','Password baru minimal 8 karakter.');redirect('settings.php');}
if($new!==$confirm){flash('error','Konfirmasi password baru tidak sama.');redirect('settings.php');}
if($new!==''){$u=$pdo->prepare('UPDATE users SET nama_lengkap=?,username=?,password=? WHERE id=?');$u->execute([$name,$username,password_hash($new,PASSWORD_DEFAULT),$uid]);}else{$u=$pdo->prepare('UPDATE users SET nama_lengkap=?,username=? WHERE id=?');$u->execute([$name,$username,$uid]);}
$_SESSION['nama_lengkap']=$name;$_SESSION['username']=$username;flash('success','Pengaturan akun berhasil diperbarui.');redirect('settings.php');}
$pageTitle='Pengaturan Akun';$active='settings';require 'includes/header.php';
?>
<div class="page-actions"><div><span class="eyebrow">PREFERENSI AKUN</span><h1>Pengaturan akun</h1><p>Perbarui informasi profil dan keamanan akunmu.</p></div></div>
<section class="panel form-panel narrow"><form method="post" class="data-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="section-title"><h3>Informasi profil</h3><p>Informasi ini digunakan pada akun STOK KITA.</p></div><label>Nama lengkap<input name="nama_lengkap" value="<?= e($user['nama_lengkap']) ?>" required></label><label>Username<input name="username" value="<?= e($user['username']) ?>" minlength="4" maxlength="30" required></label><div class="section-title separated"><h3>Ubah password</h3><p>Kosongkan jika tidak ingin mengganti password.</p></div><label>Password baru<input type="password" name="password_baru" minlength="8" placeholder="Minimal 8 karakter"></label><label>Konfirmasi password baru<input type="password" name="konfirmasi_password" placeholder="Ulangi password baru"></label><div class="form-actions"><button class="btn btn-primary">Simpan perubahan</button></div></form></section>
<?php require 'includes/footer.php'; ?>
