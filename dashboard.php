
<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/config/database.php';

$uid = current_user_id();

// ==========================================
// STATISTIK PERSEDIAAN
// ==========================================
$q = $pdo->prepare("
    SELECT
        COUNT(*) AS total,
        COALESCE(SUM(stok), 0) AS units,
        SUM(CASE
            WHEN stok > 0 AND stok <= stok_minimum
            THEN 1 ELSE 0
        END) AS low_count,
        SUM(CASE
            WHEN stok = 0
            THEN 1 ELSE 0
        END) AS empty_count
    FROM products
    WHERE user_id = ?
");
$q->execute([$uid]);
$stats = $q->fetch();

// ==========================================
// BARANG YANG PERLU DIPERHATIKAN
// ==========================================
$q = $pdo->prepare("
    SELECT
        p.nama_barang,
        p.kode_barang,
        p.stok,
        p.satuan,
        p.stok_minimum,
        c.nama_kategori
    FROM products p
    LEFT JOIN categories c
        ON c.id = p.category_id
        AND c.user_id = p.user_id
    WHERE p.user_id = ?
    ORDER BY
        (p.stok = 0) DESC,
        (p.stok <= p.stok_minimum) DESC,
        p.nama_barang
    LIMIT 6
");
$q->execute([$uid]);
$lowItems = $q->fetchAll();

// ==========================================
// TRANSAKSI TERBARU
// ==========================================
$q = $pdo->prepare("
    SELECT
        t.*,
        p.nama_barang
    FROM stock_transactions t
    JOIN products p
        ON p.id = t.product_id
        AND p.user_id = t.user_id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
    LIMIT 6
");
$q->execute([$uid]);
$recent = $q->fetchAll();

// ==========================================
// PERSIAPAN DASHBOARD
// ==========================================
$pageTitle = 'Dashboard';
$active = 'dashboard';

require __DIR__ . '/includes/header.php';
?>

<!-- ======================================
     SAPAAN DASHBOARD
======================================= -->
<div class="welcome-row">
    <div>
        <span class="eyebrow">RINGKASAN USAHA</span>
        <h1>
            Halo, <?= e(explode(' ', $_SESSION['nama_lengkap'] ?? 'Pengguna')[0]) ?> 👋
        </h1>
        <p>Berikut kondisi persediaan barangmu saat ini.</p>
    </div>

    <a class="btn btn-primary" href="products/create.php">
        <i data-lucide="plus"></i>
        Tambah barang
    </a>
</div>


<!-- ======================================
     NOTIFIKASI PERINGATAN STOK
======================================= -->
<?php
$totalPeringatan =
    (int)$stats['low_count'] +
    (int)$stats['empty_count'];
?>

<?php if ($totalPeringatan > 0): ?>

<div class="stock-alert-box">

    <div class="stock-alert-header">

        <div class="stock-alert-icon">
            <i data-lucide="bell-ring"></i>
        </div>

        <div class="stock-alert-heading">
            <h3>Peringatan Persediaan Barang!</h3>

            <p>
                Terdapat
                <strong><?= (int)$stats['low_count'] ?> barang menipis</strong>
                dan
                <strong><?= (int)$stats['empty_count'] ?> barang habis</strong>.
                Segera lakukan restock.
            </p>
        </div>

        <a href="alerts/index.php" class="stock-alert-link">
            Lihat Semua →
        </a>

    </div>

    <div class="stock-alert-list">

        <?php foreach ($lowItems as $barang): ?>

            <?php
            $stok = (int)$barang['stok'];
            $minimum = (int)$barang['stok_minimum'];

            if ($stok > $minimum) {
                continue;
            }

            $status = $stok === 0 ? 'habis' : 'menipis';
            ?>

            <div class="stock-alert-item">

                <div class="stock-alert-product">
                    <strong>
                        <?= e($barang['nama_barang']) ?>
                    </strong>

                    <small>
                        <?= e($barang['kode_barang']) ?>
                    </small>
                </div>

                <div class="stock-alert-info">

                    <span class="stock-alert-quantity">
                        <?= $stok ?> <?= e($barang['satuan']) ?>
                    </span>

                    <span class="stock-alert-minimum">
                        Minimum: <?= $minimum ?>
                    </span>

                </div>

                <span class="stock-alert-status <?= $status ?>">
                    <?= $stok === 0 ? 'Stok Habis' : 'Stok Menipis' ?>
                </span>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php endif; ?>


<!-- ======================================
     KARTU STATISTIK
======================================= -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-top">
            <span>Total jenis barang</span>

            <span class="stat-icon blue">
                <i data-lucide="package"></i>
            </span>
        </div>

        <strong><?= (int)$stats['total'] ?></strong>
        <small>Barang terdaftar</small>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Total stok tersedia</span>

            <span class="stat-icon green">
                <i data-lucide="boxes"></i>
            </span>
        </div>

        <strong>
            <?= number_format((int)$stats['units']) ?>
        </strong>

        <small>Akumulasi seluruh satuan</small>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Stok menipis</span>

            <span class="stat-icon amber">
                <i data-lucide="alert-triangle"></i>
            </span>
        </div>

        <strong>
            <?= (int)$stats['low_count'] ?>
        </strong>

        <small>Perlu diperhatikan</small>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Stok habis</span>

            <span class="stat-icon red">
                <i data-lucide="package-x"></i>
            </span>
        </div>

        <strong>
            <?= (int)$stats['empty_count'] ?>
        </strong>

        <small>Perlu restock</small>
    </div>

</div>


<!-- ======================================
     BARANG & TRANSAKSI TERBARU
======================================= -->
<div class="dashboard-grid">

    <!-- BARANG YANG PERLU DIPERHATIKAN -->
    <section class="panel">

        <div class="panel-heading">

            <div>
                <h3>Barang yang perlu diperhatikan</h3>
                <p>Stok menipis atau sudah habis</p>
            </div>

            <a class="text-link" href="alerts/index.php">
                Lihat semua →
            </a>

        </div>


        <?php if (!$lowItems): ?>

            <div class="empty-state">
                <i data-lucide="package-check"></i>

                <b>Belum ada data barang</b>

                <span>
                    Tambahkan barang untuk mulai memantau persediaan.
                </span>

                <a href="products/create.php" class="btn btn-outline">
                    Tambah barang
                </a>
            </div>

        <?php else: ?>

            <div class="table-wrap">

                <table>

                    <thead>
                        <tr>
                            <th>Nama barang</th>
                            <th>Kategori</th>
                            <th>Stok saat ini</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($lowItems as $item): ?>

                            <?php
                            $status = (int)$item['stok'] === 0
                                ? 'Habis'
                                : (
                                    (int)$item['stok'] <= (int)$item['stok_minimum']
                                    ? 'Menipis'
                                    : 'Aman'
                                );
                            ?>

                            <tr>

                                <td>
                                    <b>
                                        <?= e($item['nama_barang']) ?>
                                    </b>

                                    <small class="sub-cell">
                                        <?= e($item['kode_barang']) ?>
                                    </small>
                                </td>

                                <td>
                                    <?= e($item['nama_kategori'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= (int)$item['stok'] ?>
                                    <?= e($item['satuan']) ?>
                                </td>

                                <td>
                                    <span class="badge
                                        <?= $status === 'Habis'
                                            ? 'badge-red'
                                            : ($status === 'Menipis'
                                                ? 'badge-amber'
                                                : 'badge-green') ?>">

                                        <?= $status ?>

                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>


    <!-- TRANSAKSI TERBARU -->
    <section class="panel">

        <div class="panel-heading">

            <div>
                <h3>Transaksi terbaru</h3>
                <p>Aktivitas persediaan terakhir</p>
            </div>

            <a class="text-link" href="history/index.php">
                Riwayat →
            </a>

        </div>


        <?php if (!$recent): ?>

            <div class="empty-state compact">

                <i data-lucide="clipboard-list"></i>

                <b>Belum ada transaksi</b>

                <span>
                    Transaksi barang masuk dan keluar akan tampil di sini.
                </span>

            </div>

        <?php else: ?>

            <div class="activity-list">

                <?php foreach ($recent as $r): ?>

                    <div class="activity-item">

                        <span class="activity-icon
                            <?= $r['jenis'] === 'masuk' ? 'green' : 'red' ?>">

                            <i data-lucide="<?= $r['jenis'] === 'masuk'
                                ? 'arrow-down-left'
                                : 'arrow-up-right' ?>">
                            </i>

                        </span>

                        <div class="activity-info">

                            <b>
                                <?= e($r['nama_barang']) ?>
                            </b>

                            <small>
                                <?= date(
                                    'd M Y, H:i',
                                    strtotime($r['created_at'])
                                ) ?>
                            </small>

                        </div>

                        <strong class="<?= $r['jenis'] === 'masuk'
                            ? 'positive'
                            : 'negative' ?>">

                            <?= $r['jenis'] === 'masuk' ? '+' : '-' ?>
                            <?= (int)$r['jumlah'] ?>

                        </strong>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>


<!-- ======================================
     AKSI CEPAT
======================================= -->
<div class="quick-actions">

    <a href="transactions/create.php?type=in">

        <span class="quick-icon green">
            <i data-lucide="package-plus"></i>
        </span>

        <span>
            <b>Catat barang masuk</b>
            <small>Tambah jumlah persediaan</small>
        </span>

        <i data-lucide="arrow-up-right"></i>

    </a>


    <a href="transactions/create.php?type=out">

        <span class="quick-icon blue">
            <i data-lucide="package-minus"></i>
        </span>

        <span>
            <b>Catat barang keluar</b>
            <small>Kurangi jumlah persediaan</small>
        </span>

        <i data-lucide="arrow-up-right"></i>

    </a>

</div>


<?php require __DIR__ . '/includes/footer.php'; ?>
