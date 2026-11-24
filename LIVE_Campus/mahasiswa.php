<?php
require_once 'config.php';role_required(['admin','dosen']);
if($_SERVER['REQUEST_METHOD']==='POST'&&user()['role']==='admin'&&isset($_POST['ubah_role'])){
 $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$_POST['role'],(int)$_POST['id']]);flash('Role pengguna diperbarui.');redirect('mahasiswa.php');
}
$rows=$pdo->query("SELECT id,nama,nim,email,prodi,kelas,semester,role,foto FROM users ORDER BY nama")->fetchAll();
$page_title='Data mahasiswa';require 'includes/header.php'; ?>
<div class="panel"><div class="d-flex justify-content-between align-items-center mb-3"><div><h3>Data pengguna & mahasiswa</h3><p class="muted mb-0">Daftar akun yang terdaftar pada sistem.</p></div><span class="badge badge-soft"><?= count($rows) ?> pengguna</span></div>
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Mahasiswa</th><th>NIM</th><th>Prodi / Kelas</th><th>Semester</th><th>Role</th><?php if(user()['role']==='admin'): ?><th>Ubah role</th><?php endif; ?></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><div class="d-flex align-items-center gap-2"><img class="avatar-sm" src="<?= e($r['foto']&&$r['foto']!=='default-avatar.png'?'uploads/'.$r['foto']:'https://ui-avatars.com/api/?name='.urlencode($r['nama'])) ?>"><div><?= e($r['nama']) ?><div class="muted small"><?= e($r['email']) ?></div></div></div></td><td><?= e($r['nim']) ?></td><td><?= e($r['prodi']) ?><div class="muted small"><?= e($r['kelas']) ?></div></td><td><?= e($r['semester']) ?></td><td><span class="badge badge-soft"><?= e($r['role']) ?></span></td>
<?php if(user()['role']==='admin'): ?><td><form method="post" class="d-flex gap-1"><input type="hidden" name="id" value="<?= $r['id'] ?>"><select class="form-select form-select-sm" name="role"><?php foreach(['mahasiswa','dosen','admin'] as $role): ?><option <?= $r['role']===$role?'selected':'' ?>><?= $role ?></option><?php endforeach; ?></select><button name="ubah_role" class="btn btn-sm btn-primary">OK</button></form></td><?php endif; ?></tr><?php endforeach; ?>
</tbody></table></div></div><?php require 'includes/footer.php'; ?>