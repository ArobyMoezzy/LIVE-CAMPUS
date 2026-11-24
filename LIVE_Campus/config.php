<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

$host = 'sql212.infinityfree.com';
$db   = 'if0_43455264_live_campus';
$user = 'if0_43455264';
$pass = 'lupa12345678910';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal. Pastikan MySQL aktif dan database live_campus sudah diimpor.');
}

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function user() { return $_SESSION['user'] ?? null; }
function login_required() {
    if (!user()) { header('Location: login.php'); exit; }
}
function role_required($roles) {
    login_required();
    if (!in_array(user()['role'], (array)$roles, true)) {
        http_response_code(403); exit('Akses ditolak.');
    }
}
function redirect($url) { header("Location: $url"); exit; }
function flash($message, $type='success') { $_SESSION['flash'] = [$message, $type]; }
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        [$m,$t] = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="alert alert-'.e($t).'">'.e($m).'</div>';
    }
}
function upload_file($field, $folder, $allowed, $maxMb=10) {
    if (empty($_FILES[$field]['name'])) return null;
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload gagal.');
    if ($_FILES[$field]['size'] > $maxMb*1024*1024) throw new RuntimeException('Ukuran file maksimal '.$maxMb.' MB.');
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) throw new RuntimeException('Format file tidak diizinkan.');
    $name = bin2hex(random_bytes(12)).'.'.$ext;
    $dir = __DIR__.'/uploads/'.$folder;
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir.'/'.$name)) throw new RuntimeException('Tidak dapat menyimpan file.');
    return $folder.'/'.$name;
}
