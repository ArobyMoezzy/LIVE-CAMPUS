<?php
require_once 'config.php';
if(user()) redirect('dashboard.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
 $st=$pdo->prepare("SELECT * FROM users WHERE email=?");$st->execute([trim($_POST['email'])]);$u=$st->fetch();
 if($u&&password_verify($_POST['password'],$u['password'])){unset($u['password']);$_SESSION['user']=$u;redirect('dashboard.php');}
 $error='Email atau password salah.';
}
$page_title='Masuk';require 'includes/header.php'; ?>
<div class="login-wrap form-card"><div class="text-center mb-4"><div class="brand-mark mx-auto mb-3" style="width:48px;height:48px;font-size:1.4rem">A</div><h2>Selamat datang</h2><p class="muted">Masuk ke akun LIVE Campus kamu.</p></div>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div><div class="mb-4"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div><button class="btn btn-primary w-100">Masuk</button></form><p class="muted text-center mt-3">Belum punya akun? <a href="register.php">Daftar</a></p></div>
<?php require 'includes/footer.php'; ?>