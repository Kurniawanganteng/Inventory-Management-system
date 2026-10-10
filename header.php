<?php
require_once __DIR__ . '/auth.php';
$pageTitle = $pageTitle ?? 'Inventory';
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?> · Stockwise</title>
<link rel="stylesheet" href="assets/style.css">
</head><body>
<div class="app-shell">
<aside class="sidebar">
  <a class="brand" href="index.php"><span class="brand-mark">S</span><span>stockwise<small>INVENTORY SYSTEM</small></span></a>
  <div class="nav-label">WORKSPACE</div>
  <a class="nav-link <?= basename($_SERVER['PHP_SELF'])==='index.php'?'active':'' ?>" href="index.php">▦ <span>Dashboard</span></a>
  <a class="nav-link" href="products.php">▤ <span>Produk</span></a>
  <a class="nav-link" href="categories.php">▧ <span>Kategori</span></a>
  <a class="nav-link" href="suppliers.php">♧ <span>Supplier</span></a>
  <div class="nav-label">INVENTORY</div>
  <a class="nav-link" href="stock.php?type=IN">↙ <span>Barang Masuk</span></a>
  <a class="nav-link" href="stock.php?type=OUT">↗ <span>Barang Keluar</span></a>
  <a class="nav-link" href="reports.php">▥ <span>Laporan Stok</span></a>
  <div class="sidebar-bottom"><div class="avatar"><?= strtoupper(substr($_SESSION['user']['username'] ?? 'U',0,1)) ?></div><div class="user-meta"><b><?= h($_SESSION['user']['username'] ?? 'User') ?></b><small><?= h(ucfirst($_SESSION['user']['role'] ?? '')) ?></small></div><a class="logout" href="logout.php" title="Logout">↪</a></div>
</aside>
<main class="main-area">
<header class="topbar"><div><div class="eyebrow">INVENTORY MANAGEMENT</div><h1><?= h($pageTitle) ?></h1></div><div class="topbar-right"><span class="status-dot"></span><span>System online</span><span class="date-pill"><?= date('d M Y') ?></span></div></header>
<div class="content">
<?php if (!empty($_SESSION['flash'])): ?><div class="notice"><?= h($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
