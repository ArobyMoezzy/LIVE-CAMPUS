
<?php require_once __DIR__.'/../config.php'; ?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= e($page_title ?? 'LIVE Campus') ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link href="assets/style.css" rel="stylesheet">

</head>

<body>

<?php
// Navbar dashboard tidak ditampilkan di halaman depan
$isLanding = basename($_SERVER['PHP_SELF']) === 'index.php';
?>

<?php if (!$isLanding): ?>

<nav class="navbar navbar-expand-lg navbar-dark topbar">

    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold" href="dashboard.php">

            <span class="brand-mark">L</span>

            LIVE <span class="brand-light">CAMPUS</span>

        </a>

        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#nav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                <?php if (user()): ?>

                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="jadwal.php">
                        Jadwal
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="materi.php">
                        Materi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="tugas.php">
                        Tugas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="pengumuman.php">
                        Pengumuman
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="profil.php">
                        Profil
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php">
                        Keluar
                    </a>
                </li>

                <?php else: ?>

                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        Masuk
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-primary btn-sm" href="register.php">
                        Daftar
                    </a>
                </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>

<?php endif; ?>

<main class="container-fluid px-4 py-4">

<?php show_flash(); ?>
