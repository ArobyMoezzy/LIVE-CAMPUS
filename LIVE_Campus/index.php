
<?php
require_once 'config.php';
$page_title = 'LIVE Campus - Learning in Virtual Environment';
require 'includes/header.php';
?>

<style>
body.landing-page {
    background: #f4f7fb;
    color: #17243b;
}

.landing-nav {
    background: #19b9d6 !important;
    border: 0;
}

.landing-nav .navbar-brand {
    color: #fff !important;
    font-size: 1.2rem;
    letter-spacing: .02em;
}

.landing-nav .nav-link {
    color: #fff !important;
    font-size: .78rem;
    text-transform: uppercase;
    font-weight: 600;
    transition: .3s ease;
}

.landing-nav .nav-link:hover {
    opacity: .8;
    transform: translateY(-3px);
}

.live-logo {
    font-size: 2rem;
    font-weight: 900;
    letter-spacing: -.09em;
    background: linear-gradient(
        90deg,
        #ff9d32,
        #ef4eaa,
        #6858d8,
        #19b9d6
    );
    color: transparent;
    background-clip: text;
}

.site-top {
    background: #fff;
    padding: 12px 4%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BACKGROUND FOTO KAMPUS */
.hero-live {
    min-height: 560px;
    position: relative;
    display: flex;
    align-items: center;

    background:
        linear-gradient(
            90deg,
            rgba(5, 15, 30, .82),
            rgba(5, 15, 30, .25)
        ),
        url('assets/images/hero-campus.jpg');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.hero-content {
    max-width: 650px;
    padding: 70px 7%;
    color: #fff;
}

.hero-content h1 {
    font-size: clamp(2.2rem, 5vw, 4.4rem);
    font-weight: 800;
    letter-spacing: -.06em;
}

.hero-content p {
    font-size: 1.08rem;
    max-width: 550px;
    color: #e8f5fb;
}

.hero-actions .btn {
    border-radius: 5px;
    font-size: .78rem;
    font-weight: 700;
    padding: 12px 20px;
    margin: 5px 8px 5px 0;
}

.btn-live {
    background: #18bfdc;
    color: #fff;
    border: 0;
    transition: all .3s ease;
    position: relative;
    overflow: hidden;
}

.btn-live:hover {
    background: #079dbb;
    color: white;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 12px 25px rgba(24,191,220,.4);
}

.btn-live:active {
    transform: scale(.95);
}

/* EFEK KILAUAN TOMBOL */
.btn-live::before {
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 70%;
    height: 100%;
    background: linear-gradient(
        110deg,
        transparent,
        rgba(255,255,255,.4),
        transparent
    );
    transform: skewX(-25deg);
    transition: left .6s ease;
    pointer-events: none;
}

.btn-live:hover::before {
    left: 150%;
}

/* BAGIAN FITUR */
.feature-strip {
    padding: 55px 6%;
    background: #fff;
    color: #17243b;
}

.feature-box {
    padding: 25px;
    border: 1px solid #e7edf5;
    border-radius: 12px;
    height: 100%;
    box-shadow: 0 8px 30px #10233b0a;
    transition: all .35s ease;
}

.feature-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 18px 35px rgba(0,0,0,.12);
    border-color: #18bfdc;
}

.feature-box i {
    font-size: 1.8rem;
    color: #18b9d6;
    display: inline-block;
    transition: transform .35s ease;
}

.feature-box:hover i {
    transform: scale(1.25) rotate(-8deg);
}

.site-footer {
    background: #101e32;
    color: #aebbd0;
    padding: 24px;
    text-align: center;
}

/* RESPONSIVE */
@media(max-width: 700px) {
    .hero-live {
        min-height: 490px;
        background-position: center;
    }

    .hero-content {
        padding: 45px 7%;
    }

    .site-top {
        padding: 10px 5%;
    }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        transition-duration: .01ms !important;
    }
}
</style>

<script>
document.body.classList.add('landing-page');
</script>

<!-- HEADER LOGO -->
<div class="site-top">
    <a href="index.php" class="text-decoration-none">
        <span class="live-logo">LIVE</span>
        <sup class="text-muted"> campus</sup>
    </a>

    <div class="small text-secondary d-none d-md-block">
        LEARNING IN VIRTUAL ENVIRONMENT
    </div>
</div>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg landing-nav">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold" href="index.php">
            LIVE CAMPUS
        </a>

        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#liveNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="liveNav">

            <ul class="navbar-nav mx-auto gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#program">
                        Program
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fitur">
                        Berita
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="register.php">
                        Registrasi Mata Kuliah Daring
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fitur">
                        Informasi Lainnya
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#tentang">
                        Tentang Kami
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- HERO / BACKGROUND KAMPUS -->
<section class="hero-live">

    <div class="hero-content">

        <div class="live-logo"
             style="font-size:clamp(4rem,10vw,7rem);
                    line-height:1;
                    color:#fff;
                    background:none;">
            LIVE
        </div>

        <h1>
            LEARNING IN VIRTUAL ENVIRONMENT
        </h1>

        <p>
            Platform pembelajaran digital untuk
            menghubungkan mahasiswa dan dosen.
            Akses jadwal, materi kuliah, tugas,
            pengumpulan, dan penilaian dalam satu tempat.
        </p>

        <div class="hero-actions">

            <a class="btn btn-live" href="login.php">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                LOGIN
            </a>

            <a class="btn btn-live" href="register.php">
                <i class="bi bi-person-plus me-1"></i>
                REGISTER DOSEN
            </a>

            <a class="btn btn-live" href="register.php">
                <i class="bi bi-mortarboard me-1"></i>
                REGISTER MAHASISWA
            </a>

        </div>

    </div>
</section>

<!-- FITUR WEBSITE -->
<section class="feature-strip" id="fitur">

    <div class="text-center mb-4">

        <div class="small text-info fw-bold">
            PLATFORM AKADEMIK DIGITAL
        </div>

        <h2>
            Semua kebutuhan kuliah dalam satu portal
        </h2>

        <p class="text-secondary">
            Belajar, mengajar, dan mengelola perkuliahan
            jadi lebih mudah.
        </p>

    </div>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="feature-box">
                <i class="bi bi-journal-plus"></i>
                <h5 class="mt-3">Tugas Kuliah</h5>
                <p class="text-secondary small">
                    Dosen membuat tugas dan menentukan
                    deadline. Mahasiswa mengumpulkan
                    file secara online.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-box">
                <i class="bi bi-folder2-open"></i>
                <h5 class="mt-3">Materi Digital</h5>
                <p class="text-secondary small">
                    Dosen membagikan PDF, PPT, Word,
                    dan bahan ajar lainnya.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-box">
                <i class="bi bi-award"></i>
                <h5 class="mt-3">Nilai & Feedback</h5>
                <p class="text-secondary small">
                    Dosen menilai tugas dan memberikan
                    masukan langsung kepada mahasiswa.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-box">
                <i class="bi bi-calendar-week"></i>
                <h5 class="mt-3">Jadwal Kuliah</h5>
                <p class="text-secondary small">
                    Informasi jadwal, mata kuliah,
                    dosen, jam, dan ruangan tersedia
                    terpusat.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- TENTANG LIVE CAMPUS -->
<section class="feature-strip pt-0" id="tentang">

    <div class="container text-center">

        <h3>Mulai aktivitas akademikmu</h3>

        <p class="text-secondary">
            Masuk sebagai dosen atau mahasiswa
            untuk mengakses ruang belajar digital.
        </p>

        <a href="login.php" class="btn btn-live px-4">
            Masuk ke LIVE Campus
        </a>

    </div>

</section>

<footer class="site-footer">
    © <?= date('Y') ?> LIVE Campus · Learning in Virtual Environment
</footer>

<?php require 'includes/footer.php'; ?>
