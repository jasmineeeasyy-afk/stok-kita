
<?php
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Dashboard';
$active = $active ?? 'dashboard';
$flashMessage = get_flash();

// Menentukan lokasi utama project secara otomatis
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = rtrim($scriptDir, '/');

$appBase = preg_replace(
    '~/(products|transactions|history|alerts)$~',
    '',
    $scriptDir
);

if ($appBase === '.' || $appBase === '/') {
    $appBase = '';
}

$baseUrl = rtrim($appBase, '/') . '/';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($pageTitle) ?> • STOK KITA</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="<?= e($baseUrl) ?>assets/css/style.css?v=4">

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="app-body">

<div class="app-layout">

    <aside class="sidebar" id="sidebar">

        <a class="brand side-brand" href="<?= e($baseUrl) ?>dashboard.php">
            <span class="brand-mark">SK</span>
            <span>STOK <b>KITA</b></span>
        </a>

        <div class="side-caption">MENU UTAMA</div>

        <nav class="side-nav">

            <a class="<?= $active==='dashboard'?'active':'' ?>"
               href="<?= e($baseUrl) ?>dashboard.php">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <a class="<?= $active==='products'?'active':'' ?>"
               href="<?= e($baseUrl) ?>products/index.php">
                <i data-lucide="package"></i>
                Katalog Barang
            </a>

            <div class="nav-label">TRANSAKSI</div>

            <a class="<?= $active==='in'?'active':'' ?>"
               href="<?= e($baseUrl) ?>transactions/create.php?type=in">
                <i data-lucide="package-plus"></i>
                Barang Masuk
            </a>

            <a class="<?= $active==='out'?'active':'' ?>"
               href="<?= e($baseUrl) ?>transactions/create.php?type=out">
                <i data-lucide="package-minus"></i>
                Barang Keluar
            </a>

            <a class="<?= $active==='history'?'active':'' ?>"
               href="<?= e($baseUrl) ?>history/index.php">
                <i data-lucide="history"></i>
                Riwayat Transaksi
            </a>

            <a class="<?= $active==='alerts'?'active':'' ?>"
               href="<?= e($baseUrl) ?>alerts/index.php">
                <i data-lucide="bell-ring"></i>
                Peringatan Stok
            </a>

            <div class="nav-label">PREFERENSI</div>

            <a class="<?= $active==='settings'?'active':'' ?>"
               href="<?= e($baseUrl) ?>settings.php">
                <i data-lucide="settings"></i>
                Pengaturan Akun
            </a>

        </nav>

        <div class="sidebar-bottom">

            <div class="user-mini">

                <div class="avatar">
                    <?= e(strtoupper(substr($_SESSION['nama_lengkap'] ?? 'U', 0, 1))) ?>
                </div>

                <div>
                    <strong><?= e($_SESSION['nama_lengkap'] ?? 'Pengguna') ?></strong>
                    <small>@<?= e($_SESSION['username'] ?? '') ?></small>
                </div>

            </div>

            <a class="logout-link"
               href="<?= e($baseUrl) ?>auth/logout.php">
                <i data-lucide="log-out"></i>
                Keluar
            </a>

        </div>

    </aside>

    <main class="main-area">

        <header class="topbar">

            <button class="mobile-menu" id="menuToggle" aria-label="Buka menu">
                <i data-lucide="menu"></i>
            </button>

            <div>
                <div class="breadcrumb">
                    STOK KITA <span>/</span> <?= e($pageTitle) ?>
                </div>

                <div class="top-title">
                    <?= e($pageTitle) ?>
                </div>
            </div>

            <div class="top-user">
                <span class="status-dot"></span>

                <span><?= e($_SESSION['nama_lengkap'] ?? '') ?></span>

                <div class="avatar small">
                    <?= e(strtoupper(substr($_SESSION['nama_lengkap'] ?? 'U', 0, 1))) ?>
                </div>
            </div>

        </header>

        <div class="content">

            <?php if ($flashMessage): ?>
                <div class="alert <?= e($flashMessage['type']) ?>">
                    <?= e($flashMessage['message']) ?>
                </div>
            <?php endif; ?>
