<?php
require_once __DIR__.'/config/database.php'; require_once __DIR__.'/includes/auth.php'; require_login();
$pageTitle='Dashboard';
$products=(int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categories=(int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$stockValue=(float)$pdo->query("SELECT COALESCE(SUM(stock*price),0) FROM products")->fetchColumn();
$low=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE stock <= minimum_stock")->fetchColumn();
$recent=$pdo->query("SELECT sm.*,p.name product_name,p.sku,u.username FROM stock_movements sm JOIN products p ON p.id=sm.product_id JOIN users u ON u.id=sm.user_id ORDER BY sm.id DESC LIMIT 7")->fetchAll();
$lowProducts=$pdo->query("SELECT p.*,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id WHERE p.stock <= p.minimum_stock ORDER BY p.stock ASC LIMIT 6")->fetchAll();
$monthly=$pdo->query("SELECT DATE_FORMAT(created_at,'%b') month_label, SUM(type='IN') total_in, SUM(type='OUT') total_out FROM stock_movements WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY YEAR(created_at),MONTH(created_at),DATE_FORMAT(created_at,'%b') ORDER BY YEAR(created_at),MONTH(created_at)")->fetchAll();
include __DIR__.'/includes/header.php';
?>
<section class="welcome"><div><h2>Selamat datang, <?= h($_SESSION['user']['username']) ?> 👋</h2><p>Pantau persediaan dan aktivitas gudang dari satu tempat.</p></div><div class="welcome-tag">✦ Inventory overview</div></section>
<div class="stats">
 <div class="stat-card"><div class="stat-top">Total Produk <span class="stat-icon">▤</span></div><div class="stat-number"><?= number_format($products) ?></div><div class="stat-foot">Produk terdaftar</div></div>
 <div class="stat-card"><div class="stat-top">Kategori <span class="stat-icon">▧</span></div><div class="stat-number"><?= number_format($categories) ?></div><div class="stat-foot">Kategori aktif</div></div>
 <div class="stat-card"><div class="stat-top">Nilai Inventory <span class="stat-icon">◇</span></div><div class="stat-number" style="font-size:23px">Rp <?= number_format($stockValue,0,',','.') ?></div><div class="stat-foot">Total stok × harga</div></div>
 <div class="stat-card"><div class="stat-top">Stok Rendah <span class="stat-icon">⚑</span></div><div class="stat-number"><?= number_format($low) ?></div><div class="stat-foot"><?= $low ? '<span style="color:#d88b1d">Perlu diperiksa</span>' : '<span class="positive">Semua stok aman</span>' ?></div></div>
</div>
<div class="grid-2">
 <section class="panel"><div class="panel-head"><div><h2>Pergerakan Stok</h2><p>Ringkasan transaksi masuk dan keluar</p></div><a class="btn secondary small" href="reports.php">Lihat laporan ↗</a></div>
 <div class="chart-bars"><?php
 $months=$monthly; if(!$months){$months=[['month_label'=>'—','total_in'=>0,'total_out'=>0]];}
 $max=1; foreach($months as $m){$max=max($max,(int)$m['total_in'],(int)$m['total_out']);}
 foreach($months as $m): ?><div class="chart-col"><div class="bar-pair"><div class="bar" style="height:<?= max(4,(int)$m['total_in']/$max*125) ?>px" title="Masuk: <?= (int)$m['total_in'] ?>"></div><div class="bar out" style="height:<?= max(4,(int)$m['total_out']/$max*125) ?>px" title="Keluar: <?= (int)$m['total_out'] ?>"></div></div><span><?= h($m['month_label']) ?></span></div><?php endforeach; ?></div>
 <div class="legend"><span><i></i> Barang masuk</span><span><i class="out"></i> Barang keluar</span></div></section>
 <section class="panel"><div class="panel-head"><div><h2>Perlu Perhatian</h2><p>Produk yang mencapai batas minimum</p></div><span class="badge orange"><?= count($lowProducts) ?> item</span></div>
 <?php if(!$lowProducts): ?><div class="empty">Semua stok berada di atas batas minimum.</div><?php else: foreach($lowProducts as $p): ?><div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f0f2f7"><div class="stat-icon">▤</div><div style="flex:1"><div class="product-name"><?= h($p['name']) ?></div><small class="muted"><?= h($p['sku']) ?> · <?= h($p['category_name']) ?></small></div><div style="text-align:right"><b style="color:#d88b1d"><?= (int)$p['stock'] ?></b><div class="muted" style="font-size:10px">Min. <?= (int)$p['minimum_stock'] ?></div></div></div><?php endforeach; endif; ?></section>
</div>
<section class="panel"><div class="panel-head"><div><h2>Aktivitas Stok Terbaru</h2><p>Riwayat pergerakan persediaan terakhir</p></div><a class="btn secondary small" href="reports.php">Lihat semua</a></div><div class="table-wrap"><table><thead><tr><th>PRODUK</th><th>JENIS</th><th>JUMLAH</th><th>PENGGUNA</th><th>WAKTU</th></tr></thead><tbody><?php if(!$recent): ?><tr><td colspan="5" class="empty">Belum ada transaksi stok.</td></tr><?php else: foreach($recent as $r): ?><tr><td class="product-name"><?= h($r['product_name']) ?><div class="muted"><?= h($r['sku']) ?></div></td><td><span class="badge <?= $r['type']==='IN'?'green':'purple' ?>"><?= $r['type']==='IN'?'Barang Masuk':'Barang Keluar' ?></span></td><td><b><?= (int)$r['quantity'] ?> unit</b></td><td><?= h($r['username']) ?></td><td><?= date('d M Y H:i',strtotime($r['created_at'])) ?></td></tr><?php endforeach; endif; ?></tbody></table></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
