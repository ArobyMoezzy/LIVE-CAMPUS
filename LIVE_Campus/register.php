<?php
require_once 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  $nama=trim($_POST['nama']);$email=trim($_POST['email']);$nim=trim($_POST['nim']);
  if(!$nama||!filter_var($email,FILTER_VALIDATE_EMAIL)) throw new Exception('Nama dan email wajib diisi dengan benar.');
  if($_POST['password']!==$_POST['konfirmasi']) throw new Exception('Konfirmasi password tidak sama.');
  $st=$pdo->prepare("INSERT INTO users(nama,nim,email,password,role,prodi,kelas,semester) VALUES(?,?,?,?, 'mahasiswa',?,?,?)");
  $st->execute([$nama,$nim?:null,$email,password_hash($_POST['password'],PASSWORD_DEFAULT),$_POST['prodi'],$_POST['kelas'],$_POST['semester']?:null]);
  flash('Pendaftaran berhasil. Silakan masuk.');redirect('login.php');
 }catch(Throwable $e){$error=$e instanceof PDOException?'Email atau NIM sudah digunakan.':$e->getMessage();}
}
$page_title='Daftar';require 'includes/header.php'; ?>
<div class="login-wrap form-card"><h2>Buat akun mahasiswa</h2><p class="muted">Daftar untuk mulai menggunakan LIVE Campus.</p>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="row g-3">
<div class="col-12"><label class="form-label">Nama lengkap</label><input class="form-control" name="nama" required></div>
<div class="col-md-6"><label class="form-label">NIM</label><input class="form-control" name="nim"></div><div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
<div class="col-md-6"><label class="form-label">Program studi</label><input class="form-control" name="prodi" value="Teknik Informatika"></div><div class="col-md-6"><label class="form-label">Kelas</label><input class="form-control" name="kelas" placeholder="Contoh: TI-3A"></div>
<div class="col-12"><label class="form-label">Semester</label><select class="form-select" name="semester"><option value="">Pilih semester</option><?php for($i=1;$i<=14;$i++): ?><option><?= $i ?></option><?php endfor; ?></select></div>
<div class="col-md-6"><label class="form-label">Password</label><input type="password" class="form-control" name="password" minlength="6" required></div><div class="col-md-6"><label class="form-label">Ulangi password</label><input type="password" class="form-control" name="konfirmasi" required></div>
<div class="col-12"><button class="btn btn-primary w-100">Daftar sekarang</button></div></form><p class="muted mt-3 mb-0">Sudah punya akun? <a href="login.php">Masuk</a></p></div>
<?php require 'includes/footer.php'; ?>