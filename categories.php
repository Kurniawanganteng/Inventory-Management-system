<?php
require_once __DIR__.'/config/database.php';require_once __DIR__.'/includes/auth.php';require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'';
 try{
  if($action==='save'){$id=(int)($_POST['id']??0);$name=trim($_POST['name']??'');$desc=trim($_POST['description']??'');if($name==='')throw new Exception('Nama kategori wajib diisi.');
   if($id){$s=$pdo->prepare('UPDATE categories SET name=?,description=? WHERE id=?');$s->execute([$name,$desc,$id]);}else{$s=$pdo->prepare('INSERT INTO categories(name,description) VALUES(?,?)');$s->execute([$name,$desc]);}
   $_SESSION['flash']='Kategori berhasil disimpan.';
  }elseif($action==='delete'){$s=$pdo->prepare('DELETE FROM categories WHERE id=?');$s->execute([(int)$_POST['id']]);$_SESSION['flash']='Kategori berhasil dihapus.';}
 }catch(Throwable $e){$_SESSION['flash']='Tidak dapat menyimpan/menghapus kategori. Pastikan nama unik dan kategori tidak digunakan produk.';}
 header('Location: categories.php');exit;
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM categories WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$rows=$pdo->query('SELECT c.*,COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll();
$pageTitle='Kategori';include __DIR__.'/includes/header.php';?>
<div class="grid-2"><section class="panel"><div class="panel-head"><h2><?= $edit?'Edit kategori':'Tambah kategori' ?></h2></div><form method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><div class="form-grid"><div class="field full"><label>Nama kategori *</label><input name="name" required maxlength="100" value="<?= h($edit['name']??'') ?>"></div><div class="field full"><label>Deskripsi</label><textarea name="description" rows="3"><?= h($edit['description']??'') ?></textarea></div></div><div class="form-actions"><button class="btn">Simpan kategori</button><?php if($edit): ?><a class="btn secondary" href="categories.php">Batal</a><?php endif; ?></div></form></section><section class="panel"><div class="panel-head"><h2>Ringkasan</h2></div><div class="stat-number"><?= count($rows) ?></div><div class="muted">Kategori terdaftar</div><p class="muted">Kategori yang sudah dipakai produk tidak dapat dihapus demi menjaga integritas data.</p></section></div>
<section class="panel"><div class="panel-head"><h2>Daftar Kategori</h2></div><div class="table-wrap"><table><thead><tr><th>NAMA</th><th>DESKRIPSI</th><th>PRODUK</th><th>AKSI</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td class="product-name"><?= h($r['name']) ?></td><td><?= h($r['description']??'—') ?></td><td><?= (int)$r['product_count'] ?></td><td><a class="btn secondary small" href="?edit=<?= (int)$r['id'] ?>">Edit</a> <form method="post" style="display:inline" onsubmit="return confirm('Hapus kategori ini?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn danger small">Hapus</button></form></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php include __DIR__.'/includes/footer.php';?>