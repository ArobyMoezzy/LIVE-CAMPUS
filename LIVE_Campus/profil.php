<?php
require_once 'config.php';login_required();$id = user()['id'] ?? 0;
$st = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$st->execute([$id]);
$u = $st->fetch();

if (!$u) {
    // Session mungkin masih menyimpan ID akun lama yang sudah tidak ada di database.
    unset($_SESSION['user']);
    flash('Data akun tidak ditemukan. Silakan login kembali.', 'danger');
    redirect('login.php');
}
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  $foto=upload_file('foto','profiles',['jpg','jpeg','png','webp'],3);
  $sql="UPDATE users SET nama=?,nim=?,prodi=?,kelas=?,semester=?,no_hp=?,alamat=?".($foto?",foto=?":"")." WHERE id=?";
  $args=[trim($_POST['nama']),trim($_POST['nim'])?:null,trim($_POST['prodi']),trim($_POST['kelas']),$_POST['semester']?:null,trim($_POST['no_hp']),trim($_POST['alamat'])];
  if($foto)$args[]=$foto;$args[]=$id;$pdo->prepare($sql)->execute($args);
  $_SESSION['user']['nama']=trim($_POST['nama']);flash('Profil berhasil diperbarui.');redirect('profil.php');
 }catch(Throwable $e){$error=$e->getMessage();}
}
$page_title='Profil';require 'includes/header.php'; ?>
<div class="row g-4"><div class="col-lg-4"><div class="panel text-center"><img class="avatar mb-3" src="<?= e($u['foto']&&$u['foto']!=='default-avatar.png'?'uploads/'.$u['foto']:'https://ui-avatars.com/api/?name='.urlencode($u['nama']).'&background=243b63&color=fff') ?>"><h4><?= e($u['nama']) ?></h4><p class="muted mb-2"><?= e($u['nim']??'NIM belum diisi') ?></p><span class="badge badge-soft"><?= strtoupper(e($u['role'])) ?></span><hr><div class="text-start"><p><span class="muted">Email</span><br><?= e($u['email']) ?></p><p><span class="muted">Program studi</span><br><?= e($u['prodi']) ?></p></div></div></div>
<div class="col-lg-8"><div class="form-card"><h3>Edit profil</h3><p class="muted">Lengkapi data diri dan foto profil.</p><?php if(!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3">
<div class="col-md-6"><label class="form-label">Nama lengkap</label><input class="form-control" name="nama" value="<?= e($u['nama']) ?>" required></div><div class="col-md-6"><label class="form-label">NIM</label><input class="form-control" name="nim" value="<?= e($u['nim']) ?>"></div>
<div class="col-md-6"><label class="form-label">Program studi</label><input class="form-control" name="prodi" value="<?= e($u['prodi']) ?>"></div><div class="col-md-6"><label class="form-label">Kelas</label><input class="form-control" name="kelas" value="<?= e($u['kelas']) ?>"></div>
<div class="col-md-4"><label class="form-label">Semester</label><input type="number" min="1" max="14" class="form-control" name="semester" value="<?= e($u['semester']) ?>"></div><div class="col-md-8"><label class="form-label">Nomor HP</label><input class="form-control" name="no_hp" value="<?= e($u['no_hp']) ?>"></div>
<div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2"><?= e($u['alamat']) ?></textarea></div><div class="col-12"><label class="form-label">Foto profil (JPG/PNG/WEBP, maks. 3 MB)</label><input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png,.webp"></div>
<div class="col-12"><button class="btn btn-primary">Simpan perubahan</button></div></form></div></div></div>
<?php require 'includes/footer.php'; ?>