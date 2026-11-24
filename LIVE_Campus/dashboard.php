
<?php
require_once 'config.php';
login_required();

$page_title = 'Dashboard LIVE Campus';

$uid = (int) user()['id'];
$role = user()['role'];
$isStaff = in_array($role, ['admin', 'dosen'], true);

// ===================================
// STATISTIK DASHBOARD
// ===================================

if ($role === 'mahasiswa') {

    $counts['tugas'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tugas"
    )->fetchColumn();

    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM pengumpulan WHERE mahasiswa_id=?"
    );
    $q->execute([$uid]);
    $counts['kirim'] = (int)$q->fetchColumn();

    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM pengumpulan
         WHERE mahasiswa_id=? AND nilai IS NOT NULL"
    );
    $q->execute([$uid]);
    $counts['nilai'] = (int)$q->fetchColumn();

    $q = $pdo->prepare(
        "SELECT AVG(nilai) FROM pengumpulan
         WHERE mahasiswa_id=? AND nilai IS NOT NULL"
    );
    $q->execute([$uid]);
    $counts['rata'] = round((float)($q->fetchColumn() ?: 0), 1);

} else {

    $counts['mahasiswa'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM users WHERE role='mahasiswa'"
    )->fetchColumn();

    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM tugas WHERE dosen_id=?"
    );
    $q->execute([$uid]);
    $counts['tugas'] = (int)$q->fetchColumn();

    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM pengumpulan p
         JOIN tugas t ON p.tugas_id=t.id
         WHERE t.dosen_id=?"
    );
    $q->execute([$uid]);
    $counts['kirim'] = (int)$q->fetchColumn();

    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM pengumpulan p
         JOIN tugas t ON p.tugas_id=t.id
         WHERE t.dosen_id=? AND p.nilai IS NULL"
    );
    $q->execute([$uid]);
    $counts['perlu_dinilai'] = (int)$q->fetchColumn();
}

// ===================================
// DATA TUGAS TERBARU
// ===================================

$recent = $pdo->query("
    SELECT t.id, t.judul, t.deadline,
           m.nama AS matkul
    FROM tugas t
    JOIN mata_kuliah m ON m.id=t.mata_kuliah_id
    ORDER BY t.created_at DESC
    LIMIT 6
")->fetchAll();

// ===================================
// DATA MATA KULIAH
// ===================================

$courses = $pdo->query("
    SELECT id, kode, nama
    FROM mata_kuliah
    ORDER BY nama
")->fetchAll();

// ===================================
// JADWAL KULIAH
// ===================================

$schedule = $pdo->query("
    SELECT j.hari, j.mulai, j.selesai,
           j.ruangan, m.nama AS matkul, m.kode
    FROM jadwal j
    JOIN mata_kuliah m ON m.id=j.mata_kuliah_id
    ORDER BY FIELD(
        j.hari,
        'Senin','Selasa','Rabu',
        'Kamis','Jumat','Sabtu'
    ), j.mulai
    LIMIT 6
")->fetchAll();

// ===================================
// PENGUMUMAN
// ===================================

$announcements = $pdo->query("
    SELECT p.judul, p.isi, p.created_at,
           u.nama
    FROM pengumuman p
    JOIN users u ON u.id=p.pembuat_id
    ORDER BY p.created_at DESC
    LIMIT 4
")->fetchAll();

// ===================================
// GRAFIK PENGUMPULAN
// ===================================

if ($role === 'mahasiswa') {

    $q = $pdo->prepare("
        SELECT
        SUM(status='terkirim') AS terkirim,
        SUM(status='dinilai') AS dinilai,
        SUM(status='terlambat') AS terlambat
        FROM pengumpulan
        WHERE mahasiswa_id=?
    ");
    $q->execute([$uid]);

} else {

    $q = $pdo->prepare("
        SELECT
        SUM(p.status='terkirim') AS terkirim,
        SUM(p.status='dinilai') AS dinilai,
        SUM(p.status='terlambat') AS terlambat
        FROM pengumpulan p
        JOIN tugas t ON t.id=p.tugas_id
        WHERE t.dosen_id=?
    ");
    $q->execute([$uid]);
}

$chart = $q->fetch() ?: [];

$chartValues = [
    (int)($chart['terkirim'] ?? 0),
    (int)($chart['dinilai'] ?? 0),
    (int)($chart['terlambat'] ?? 0)
];

require 'includes/header.php';
?>

<style>

/* ===================================
   DASHBOARD LIVE CAMPUS
=================================== */

.dashboard-hero {
    position: relative;
    overflow: hidden;
    border: 1px solid #30466a;
    border-radius: 20px;
    padding: 32px;

    background:
        radial-gradient(
            circle at 90% 10%,
            #31588d,
            #1a3155 35%,
            #121a2e 78%
        );
}

.dashboard-hero h1 {
    font-size: clamp(1.7rem, 3vw, 2.5rem);
    font-weight: 800;
}

.mini-label {
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #91a0bb;
}

.dash-stat {
    height: 100%;
    padding: 22px;
    border-radius: 16px;
    border: 1px solid #263653;
    background: linear-gradient(
        145deg,
        #172542,
        #111a2d
    );
    transition: .3s ease;
}

.dash-stat:hover {
    transform: translateY(-6px);
    border-color: #39d6e8;
    box-shadow: 0 12px 28px #0003;
}

.dash-stat .number {
    font-size: 2rem;
    font-weight: 800;
    margin-top: 7px;
}

.icon-wrap {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #203b60;
    color: #39d6e8;
    font-size: 1.3rem;
}

.dash-panel {
    height: 100%;
    background: #121a2e;
    border: 1px solid #263653;
    border-radius: 17px;
    padding: 22px;
    transition: .3s ease;
}

.dash-panel h4 {
    font-size: 1.1rem;
    font-weight: 700;
}

.course-tile {
    height: 100%;
    padding: 18px;
    border-radius: 14px;
    border: 1px solid #293b5b;
    background: linear-gradient(
        145deg,
        #172542,
        #111a2d
    );
    transition: .3s ease;
}

.course-tile:hover {
    transform: translateY(-6px);
    border-color: #39d6e8;
    box-shadow: 0 12px 24px #0003;
}

.course-code {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 8px;
    background: #213d66;
    color: #a8cbff;
    font-size: .75rem;
    font-weight: 800;
}

.course-icon {
    color: #39d6e8;
    font-size: 1.5rem;
}

.dash-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 1px solid #344968;
    border-radius: 10px;
    color: #eaf0ff;
    text-decoration: none;
    transition: .25s ease;
}

.dash-link:hover {
    background: #1d3151;
    border-color: #5b8cff;
    color: white;
    transform: translateX(5px);
}

.schedule-row,
.announcement {
    padding: 13px 0;
    border-bottom: 1px solid #263653;
}

.schedule-row:last-child,
.announcement:last-child {
    border-bottom: 0;
}

.schedule-day {
    min-width: 65px;
    color: #8fb7ff;
    font-weight: 700;
}

.activity-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #39d6e8;
    display: inline-block;
    margin-right: 8px;
}

@media(max-width:767px) {
    .dashboard-hero {
        padding: 22px;
    }

    .dash-panel {
        padding: 17px;
    }
}

</style>

<!-- ===================================
     HERO DASHBOARD
=================================== -->

<div class="dashboard-hero mb-4">

    <div class="row align-items-center">

        <div class="col-lg-8">

            <div class="mini-label mb-2">
                LIVE CAMPUS · LEARNING MANAGEMENT SYSTEM
            </div>

            <h1>
                Halo, <?= e(user()['nama']) ?> 👋
            </h1>

            <p class="muted">
                <?php if ($isStaff): ?>
                    Selamat datang di ruang akademik digital.
                    Kelola perkuliahan, materi, tugas, dan nilai.
                <?php else: ?>
                    Selamat datang kembali.
                    Yuk, cek jadwal, materi, dan tugas kuliahmu.
                <?php endif; ?>
            </p>

            <span class="badge badge-soft">
                <?= strtoupper(e($role)) ?>
            </span>

            <span class="badge badge-soft">
                <?= e(user()['prodi'] ?? 'Teknik Informatika') ?>
            </span>

        </div>

        <div class="col-lg-4 text-lg-end mt-3">

            <?php if ($isStaff): ?>

                <a href="tugas.php" class="btn btn-primary mb-2">
                    <i class="bi bi-plus-circle"></i>
                    Buat Tugas
                </a>

                <a href="materi.php" class="btn btn-outline-light mb-2">
                    <i class="bi bi-cloud-upload"></i>
                    Upload Materi
                </a>

            <?php else: ?>

                <a href="tugas.php" class="btn btn-primary mb-2">
                    <i class="bi bi-upload"></i>
                    Kumpulkan Tugas
                </a>

                <a href="jadwal.php" class="btn btn-outline-light mb-2">
                    <i class="bi bi-calendar"></i>
                    Jadwal Kuliah
                </a>

            <?php endif; ?>

        </div>

    </div>
</div>

<!-- ===================================
     STATISTIK
=================================== -->

<div class="row g-3 mb-4">

<?php

if ($role === 'mahasiswa') {

    $stats = [
        ['Tugas tersedia', $counts['tugas'], 'bi-journal-text'],
        ['Sudah dikumpulkan', $counts['kirim'], 'bi-cloud-check'],
        ['Sudah dinilai', $counts['nilai'], 'bi-award'],
        ['Rata-rata nilai', $counts['rata'], 'bi-graph-up']
    ];

} else {

    $stats = [
        ['Mahasiswa', $counts['mahasiswa'], 'bi-people'],
        ['Tugas dibuat', $counts['tugas'], 'bi-journal-plus'],
        ['Pengumpulan masuk', $counts['kirim'], 'bi-inbox'],
        ['Perlu dinilai', $counts['perlu_dinilai'], 'bi-clipboard-check']
    ];

}

foreach ($stats as $s):
?>

<div class="col-6 col-xl-3">

    <div class="dash-stat">

        <div class="d-flex justify-content-between">

            <div>
                <div class="muted small">
                    <?= e($s[0]) ?>
                </div>

                <div class="number">
                    <?= e($s[1]) ?>
                </div>
            </div>

            <div class="icon-wrap">
                <i class="bi <?= e($s[2]) ?>"></i>
            </div>

        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

<!-- ===================================
     MATA KULIAH DAN AKSES CEPAT
=================================== -->

<div class="row g-3 mb-4">

<div class="col-xl-8">

    <div class="dash-panel">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <div class="mini-label">AKADEMIK</div>
                <h4>Mata Kuliah</h4>
            </div>

            <a href="materi.php">Lihat materi →</a>
        </div>

        <div class="row g-3">

        <?php foreach ($courses as $course): ?>

            <div class="col-md-6 col-lg-4">

                <div class="course-tile">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="course-code">
                            <?= e($course['kode']) ?>
                        </span>

                        <i class="bi bi-mortarboard course-icon"></i>

                    </div>

                    <h6><?= e($course['nama']) ?></h6>

                    <div class="d-flex gap-2 mt-3">

                        <a href="materi.php"
                           class="btn btn-sm btn-outline-light flex-fill">
                            Materi
                        </a>

                        <a href="tugas.php"
                           class="btn btn-sm btn-outline-light flex-fill">
                            Tugas
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

        <?php if (!$courses): ?>
            <p class="muted">Belum ada mata kuliah.</p>
        <?php endif; ?>

        </div>

    </div>

</div>

<div class="col-xl-4">

    <div class="dash-panel">

        <div class="mini-label">NAVIGASI</div>
        <h4 class="mb-3">Akses Cepat</h4>

        <div class="d-grid gap-2">

            <a href="profil.php" class="dash-link">
                <i class="bi bi-person-circle"></i>
                Profil Saya
            </a>

            <a href="jadwal.php" class="dash-link">
                <i class="bi bi-calendar-week"></i>
                Jadwal Kuliah
            </a>

            <a href="materi.php" class="dash-link">
                <i class="bi bi-folder2-open"></i>
                Materi Perkuliahan
            </a>

            <a href="tugas.php" class="dash-link">
                <i class="bi bi-journal-check"></i>
                Tugas dan Pengumpulan
            </a>

            <a href="pengumuman.php" class="dash-link">
                <i class="bi bi-megaphone"></i>
                Pengumuman
            </a>

            <?php if ($isStaff): ?>

                <a href="nilai.php" class="dash-link">
                    <i class="bi bi-clipboard-data"></i>
                    Kelola Penilaian
                </a>

                <a href="mahasiswa.php" class="dash-link">
                    <i class="bi bi-people"></i>
                    Data Mahasiswa
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>

</div>

<!-- ===================================
     TUGAS TERBARU DAN GRAFIK
=================================== -->

<div class="row g-3 mb-4">

<div class="col-lg-7">

    <div class="dash-panel">

        <div class="d-flex justify-content-between mb-3">
            <h4>Tugas Terbaru</h4>
            <a href="tugas.php">Lihat semua →</a>
        </div>

        <?php if (!$recent): ?>

            <p class="muted">Belum ada tugas tersedia.</p>

        <?php else: ?>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Tugas</th>
                        <th>Mata Kuliah</th>
                        <th>Deadline</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($recent as $r): ?>

                    <tr>

                        <td>
                            <i class="bi bi-file-earmark-text text-info me-2"></i>
                            <?= e($r['judul']) ?>
                        </td>

                        <td><?= e($r['matkul']) ?></td>

                        <td>
                            <?= date('d M Y H:i', strtotime($r['deadline'])) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <?php endif; ?>

    </div>

</div>

<div class="col-lg-5">

    <div class="dash-panel">

        <div class="mini-label">ANALISIS AKADEMIK</div>
        <h4>Status Pengumpulan</h4>

        <canvas id="submissionChart" height="190"></canvas>

    </div>

</div>

</div>

<!-- ===================================
     JADWAL DAN PENGUMUMAN
=================================== -->

<div class="row g-3">

<div class="col-lg-6">

    <div class="dash-panel">

        <div class="d-flex justify-content-between mb-3">

            <h4>Jadwal Kuliah</h4>

            <a href="jadwal.php">Lihat semua →</a>

        </div>

        <?php if (!$schedule): ?>

            <p class="muted">Belum ada jadwal kuliah.</p>

        <?php else: ?>

            <?php foreach ($schedule as $sc): ?>

                <div class="schedule-row d-flex gap-3">

                    <div class="schedule-day">
                        <?= e($sc['hari']) ?>
                    </div>

                    <div class="flex-fill">

                        <strong>
                            <?= e($sc['matkul']) ?>
                        </strong>

                        <div class="muted small">
                            <?= substr($sc['mulai'],0,5) ?>
                            -
                            <?= substr($sc['selesai'],0,5) ?>

                            · <?= e($sc['ruangan']) ?>
                        </div>

                    </div>

                    <span class="badge badge-soft">
                        <?= e($sc['kode']) ?>
                    </span>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

<div class="col-lg-6">

    <div class="dash-panel">

        <div class="d-flex justify-content-between mb-3">

            <h4>Pengumuman Terbaru</h4>

            <a href="pengumuman.php">Lihat semua →</a>

        </div>

        <?php if (!$announcements): ?>

            <p class="muted">Belum ada pengumuman.</p>

        <?php else: ?>

            <?php foreach ($announcements as $a): ?>

                <div class="announcement">

                    <strong>
                        <span class="activity-dot"></span>
                        <?= e($a['judul']) ?>
                    </strong>

                    <p class="muted small mt-2 mb-1">
                        <?= e(mb_strimwidth($a['isi'],0,120,'...')) ?>
                    </p>

                    <div class="muted small">
                        <?= e($a['nama']) ?>
                        ·
                        <?= date('d M Y', strtotime($a['created_at'])) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

</div>

<!-- GRAFIK -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const canvas = document.getElementById('submissionChart');

if (canvas) {

    new Chart(canvas, {

        type: 'doughnut',

        data: {

            labels: [
                'Terkirim',
                'Dinilai',
                'Terlambat'
            ],

            datasets: [{

                data: <?= json_encode($chartValues) ?>,

                backgroundColor: [
                    '#5b8cff',
                    '#39d6e8',
                    '#f6ad55'
                ],

                borderColor: '#121a2e',
                borderWidth: 4,
                hoverOffset: 10

            }]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    position: 'bottom',

                    labels: {
                        color: '#c5d2e8',
                        padding: 18
                    }

                }

            },

            cutout: '65%'

        }

    });

}
</script>

<?php require 'includes/footer.php'; ?>
