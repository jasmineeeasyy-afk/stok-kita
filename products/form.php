<?php
require_once __DIR__.'/../includes/auth_check.php';require_once __DIR__.'/../config/database.php';
$uid=current_user_id();$id=(int)($_GET['id']??0);$editing=$id>0;$item=['kode_barang'=>'','nama_barang'=>'','category_id'=>'','satuan'=>'pcs','stok'=>0,'stok_minimum'=>5,'deskripsi'=>''];
if($editing){$s=$pdo->prepare('SELECT * FROM products WHERE id=? AND user_id=?');$s->execute([$id,$uid]);$item=$s->fetch();if(!$item){flash('error','Barang tidak ditemukan.');redirect('index.php');}}
$c=$pdo->prepare('SELECT id,nama_kategori FROM categories WHERE user_id=? ORDER BY nama_kategori');$c->execute([$uid]);$cats=$c->fetchAll();
$pageTitle=$editing?'Edit Barang':'Tambah Barang';$active='products';require __DIR__.'/../includes/header.php';
?>
<div class="page-actions"><div><span class="eyebrow">KATALOG BARANG</span><h1><?= $editing?'Edit data barang':'Tambah barang baru' ?></h1><p>Lengkapi informasi barang yang akan dikelola.</p></div><a class="btn btn-outline" href="index.php">← Kembali</a></div>
<section class="panel form-panel"><form method="post" action="save.php" class="data-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $id ?>">
<div class="form-grid"><label>Kode barang<input name="kode_barang" value="<?= e($item['kode_barang']) ?>" placeholder="Contoh: BRG-001" required maxlength="40"></label><label>Nama barang<input name="nama_barang" value="<?= e($item['nama_barang']) ?>" placeholder="Nama barang" required maxlength="120"></label>
<label>Kategori<select name="category_id"><option value="">Tanpa kategori</option><?php foreach($cats as $cat): ?><option value="<?= $cat['id'] ?>" <?= (string)$item['category_id']===(string)$cat['id']?'selected':'' ?>><?= e($cat['nama_kategori']) ?></option><?php endforeach; ?></select></label><label>Satuan<input name="satuan" value="<?= e($item['satuan']) ?>" placeholder="pcs, kg, liter" required></label>
<label>Stok awal / saat ini<input type="number" name="stok" min="0" value="<?= (int)$item['stok'] ?>" required></label><label>Batas minimum stok<input type="number" name="stok_minimum" min="0" value="<?= (int)$item['stok_minimum'] ?>" required></label><label class="full">Deskripsi (opsional)<textarea name="deskripsi" rows="3" placeholder="Keterangan barang..."><?= e($item['deskripsi']) ?></textarea></label></div>
<div class="form-actions"><a class="btn btn-outline" href="index.php">Batal</a><button class="btn btn-primary" type="submit">Simpan barang</button></div></form></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
