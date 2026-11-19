<?php
require_once __DIR__.'/../includes/auth_check.php'; require_once __DIR__.'/../config/database.php';
$uid=current_user_id(); $search=trim($_GET['q']??'');
$sql="SELECT p.*, c.nama_kategori FROM products p LEFT JOIN categories c ON c.id=p.category_id AND c.user_id=p.user_id WHERE p.user_id=?"; $args=[$uid];
if($search!==''){ $sql.=" AND (p.nama_barang LIKE ? OR p.kode_barang LIKE ?)"; $args[]="%$search%"; $args[]="%$search%"; }
$sql.=" ORDER BY p.created_at DESC"; $st=$pdo->prepare($sql);$st->execute($args);$items=$st->fetchAll();
$pageTitle='Katalog Barang';$active='products';require __DIR__.'/../includes/header.php';
?>
<div class="page-actions"><div><span class="eyebrow">PENGELOLAAN PERSEDIAAN</span><h1>Master katalog barang</h1><p>Kelola daftar barang dan pantau jumlah persediaan.</p></div><a class="btn btn-primary" href="create.php"><i data-lucide="plus"></i> Tambah barang</a></div>
<section class="panel"><form class="toolbar" method="get"><div class="search-box"><i data-lucide="search"></i><input name="q" value="<?= e($search) ?>" placeholder="Cari nama atau kode barang..."></div><button class="btn btn-outline" type="submit">Cari</button></form>
<div class="table-wrap"><table><thead><tr><th>Kode barang</th><th>Nama barang</th><th>Kategori</th><th>Stok</th><th>Stok minimum</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($items as $it):$s=(int)$it['stok']===0?'Habis':((int)$it['stok']<=(int)$it['stok_minimum']?'Menipis':'Aman'); ?><tr><td><?= e($it['kode_barang']) ?></td><td><b><?= e($it['nama_barang']) ?></b><small class="sub-cell"><?= e($it['satuan']) ?></small></td><td><?= e($it['nama_kategori']??'-') ?></td><td><?= (int)$it['stok'] ?></td><td><?= (int)$it['stok_minimum'] ?></td><td><span class="badge <?= $s==='Habis'?'badge-red':($s==='Menipis'?'badge-amber':'badge-green') ?>"><?= $s ?></span></td><td class="actions"><a href="edit.php?id=<?= (int)$it['id'] ?>" title="Edit"><i data-lucide="pencil"></i></a><form method="post" action="delete.php" onsubmit="return confirm('Hapus barang ini?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$it['id'] ?>"><button title="Hapus"><i data-lucide="trash-2"></i></button></form></td></tr><?php endforeach; ?>
<?php if(!$items): ?><tr><td colspan="7"><div class="empty-inline">Belum ada barang. <a href="create.php">Tambahkan barang pertama</a>.</div></td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
