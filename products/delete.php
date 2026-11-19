<?php
require_once __DIR__.'/../includes/auth_check.php';
require_once __DIR__.'/../config/database.php';
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('index.php');
verify_csrf();
$id=(int)($_POST['id']??0);
$check=$pdo->prepare('SELECT id FROM products WHERE id=? AND user_id=?');
$check->execute([$id,current_user_id()]);
if(!$check->fetch()) { flash('error','Barang tidak ditemukan.'); redirect('index.php'); }
$tx=$pdo->prepare('SELECT id FROM stock_transactions WHERE product_id=? AND user_id=? LIMIT 1');
$tx->execute([$id,current_user_id()]);
if($tx->fetch()) { flash('error','Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi.'); redirect('index.php'); }
$del=$pdo->prepare('DELETE FROM products WHERE id=? AND user_id=?');
$del->execute([$id,current_user_id()]);
flash('success','Barang berhasil dihapus.');
redirect('index.php');
