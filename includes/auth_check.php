<?php
require_once __DIR__ . '/functions.php';
if (empty($_SESSION['user_id'])) {
    flash('error', 'Silakan masuk terlebih dahulu.');
    redirect('/stok-kita/login.php');
}
