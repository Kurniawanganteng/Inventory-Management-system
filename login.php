<?php
require_once __DIR__.'/config/database.php'; require_once __DIR__.'/includes/auth.php';
if (!empty($_SESSION['user'])) { header('Location: index.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $username=trim($_POST['username']??''); $password=$_POST['password']??'';
 $stmt=$pdo->prepare('SELECT id,username,password_hash,role FROM users WHERE username=:username LIMIT 1');
 $stmt->execute(['username'=>$username]); $user=$stmt->fetch();
 if ($user && password_verify($password,$user['password_hash'])) {
   session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$user['id'],'username'=>$user['username'],'role'=>$user['role']];
   header('Location: index.php'); exit;
 } else $error='Username atau password tidak sesuai.';
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login · Stockwise</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-page"><div class="login-card"><a class="brand" href="login.php"><span class="brand-mark">S</span><span>stockwise<small>INVENTORY SYSTEM</small></span></a><h1>Selamat datang</h1><p>Masuk untuk mengelola inventory dan memantau aktivitas stok.</p><?php if($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?><form method="post"><div class="field"><label>Username</label><input name="username" required autocomplete="username" placeholder="Masukkan username"></div><div class="field"><label>Password</label><input type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"></div><button class="btn" type="submit">Masuk ke dashboard →</button></form><div class="login-hint">Demo akun: admin / password atau staff / password<br>Ganti password demo sebelum penggunaan nyata.</div></div></body></html>