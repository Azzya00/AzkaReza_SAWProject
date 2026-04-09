<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Ambil data alternatif
$alternatif = [];
$result = mysqli_query($koneksi, "SELECT * FROM tbl_alternatif ORDER BY id_alternatif ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $alternatif[] = $row;
}

// Ambil bobot
$qBobot = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria DESC LIMIT 1");
$bobot = mysqli_fetch_assoc($qBobot);

if (!$bobot) {
    $bobot = ['C1'=>0,'C2'=>0,'C3'=>0,'C4'=>0,'C5'=>0];
}

$totalBobot = $bobot['C1'] + $bobot['C2'] + $bobot['C3'] + $bobot['C4'] + $bobot['C5'];

if ($totalBobot == 0) {
    $w = [0,0,0,0,0];
} else {
    $w = [
        $bobot['C1'] / $totalBobot,
        $bobot['C2'] / $totalBobot,
        $bobot['C3'] / $totalBobot,
        $bobot['C4'] / $totalBobot,
        $bobot['C5'] / $totalBobot
    ];
}

// Normalisasi
$R = [];
$maxC = ['C1'=>-INF,'C2'=>-INF,'C3'=>-INF,'C5'=>-INF];
$minC = ['C4'=>INF];

foreach ($alternatif as $a) {
    foreach(['C1','C2','C3','C5'] as $c) {
        if ($a[$c] > $maxC[$c]) $maxC[$c] = $a[$c];
    }
    foreach(['C4'] as $c) {
        if ($a[$c] < $minC[$c]) $minC[$c] = $a[$c];
    }
}

foreach ($alternatif as $a) {
    $r = [];
    foreach(['C1','C2','C3','C5'] as $c) {
        $r[$c] = ($maxC[$c] == 0) ? 0 : round($a[$c] / $maxC[$c], 3);
    }
    foreach(['C4'] as $c) {
        $r[$c] = ($a[$c] == 0) ? 0 : round($minC[$c] / $a[$c], 3);
    }
    $R[] = $r;
}

// Hitung Vi
$Vi = [];
foreach ($R as $i => $r) {
    $Vi[$i] = round(
        $r['C1']*$w[0] + $r['C2']*$w[1] + $r['C3']*$w[2] + $r['C4']*$w[3] + $r['C5']*$w[4],
        3
    );
}

$ranking = $Vi;
arsort($ranking);
// Ambil alternatif terbaik (ranking 1)
$topIndex = array_key_first($ranking);
$topNama  = $alternatif[$topIndex]['novel'];
$topNilai = $ranking[$topIndex];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perhitungan SAW</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: url('uploads/perpus6.jpeg') center/cover no-repeat;
        margin: 0;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        /* transparan */
        backdrop-filter: blur(10px);
        /* efek kaca 🔥 */
        padding-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #fff;
    }

    .sidebar img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        margin-bottom: 15px;
        object-fit: cover;
        border: 3px solid #fff;
    }

    .sidebar h3 {
        margin-bottom: 30px;
        text-align: center;
        font-size: 1.5rem;
    }

    .sidebar a {
        width: 100%;
        text-align: left;
        padding: 12px 20px;
        margin: 5px 0;
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* CONTENT FIX */
    .main {
        margin-left: 220px;
        padding: 20px;
    }

    .card {
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        background: rgba(0, 0, 0, 0.4);
        /* transparan */
        backdrop-filter: blur(10px);
        /* efek kaca 🔥 */
    }

    .card {
        color: #fff !important;
    }

    .card h5 {
        color: #fff !important;

    }

    /* Table jadi transparan + teks putih */
    /* TABLE TRANSPARAN FULL */
    /* RESET TOTAL TABLE */
    .table {
        background: transparent !important;
        color: #fff !important;
    }

    /* HAPUS SEMUA BACKGROUND BOOTSTRAP */
    .table> :not(caption)>*>* {
        background: transparent !important;
        color: #fff !important;
    }

    /* HEADER */
    .table thead th {
        color: #fff !important;
        background: transparent !important;
    }

    /* BODY */
    .table tbody td {
        color: #fff !important;
        background: transparent !important;
    }

    /* BORDER PUTIH */
    .table,
    .table th,
    .table td {
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
    }

    /* HOVER */
    .table-hover tbody tr:hover {
        background: rgba(255, 255, 255, 0.1) !important;
    }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <img src="uploads/perpus1.jpeg">
        <h3><?= $_SESSION['username'] ?></h3>

        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="kriteria.php"><i class="fas fa-list-check me-2"></i> Kriteria</a>
        <a href="alternatif.php"><i class="fas fa-book me-2"></i> Daftar Novel</a>
        <a href="perhitungan.php" class="active"><i class="fas fa-calculator me-2"></i> Perhitungan</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="main">

        <!-- Bobot -->
        <div class="card p-3">
            <h5>Bobot Kriteria</h5>
            <table class="table table-bordered text-center">
                <tr>
                    <th>Rating</th>
                    <th>Jumlah Halaman</th>
                    <th>Popularitas</th>
                    <th>Harga</th>
                    <th>Jumlah Review</th>
                </tr>
                <tr>
                    <td><?= round($w[0],3) ?></td>
                    <td><?= round($w[1],3) ?></td>
                    <td><?= round($w[2],3) ?></td>
                    <td><?= round($w[3],3) ?></td>
                    <td><?= round($w[4],3) ?></td>
                </tr>
            </table>
        </div>

        <!-- Normalisasi -->
        <div class="card p-3">
            <h5>Matriks R</h5>
            <table class="table table-bordered text-center">
                <tr>
                    <th>No</th>
                    <th>Alternatif</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>C5</th>
                </tr>

                <?php foreach($R as $i=>$r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $alternatif[$i]['novel'] ?></td>
                    <td><?= $r['C1'] ?></td>
                    <td><?= $r['C2'] ?></td>
                    <td><?= $r['C3'] ?></td>
                    <td><?= $r['C4'] ?></td>
                    <td><?= $r['C5'] ?></td>
                </tr>
                <?php endforeach; ?>

            </table>
        </div>

        <!-- Vi -->
        <div class="card p-3">
            <h5>Nilai Vi</h5>
            <table class="table table-bordered text-center">
                <tr>
                    <th>Alternatif</th>
                    <th>Vi</th>
                </tr>

                <?php foreach($Vi as $i=>$v): ?>
                <tr>
                    <td><?= $alternatif[$i]['novel'] ?></td>
                    <td><?= $v ?></td>
                </tr>
                <?php endforeach; ?>

            </table>
        </div>

        <!-- Ranking -->
        <div class="card p-3">
            <h5>Ranking</h5>
            <table class="table table-bordered text-center">
                <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>Nilai</th>
                </tr>

                <?php $rank=1; foreach($ranking as $i=>$v): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= $alternatif[$i]['novel'] ?></td>
                    <td><?= $v ?></td>
                </tr>
                <?php endforeach; ?>

            </table>
        </div>
        <div class="card p-4 text-center">
            <h4 class="mb-3">📖 Rekomendasi Novel Terbaik 📖</h4>

            <h2 style="font-weight:700;">
                <?= $topNama ?>
            </h2>

            <p style="font-size:18px;">
                Nilai Preferensi (Vi): <b><?= $topNilai ?></b>
            </p>

            <p style="opacity:0.8;">
                Dipilih berdasarkan perhitungan metode SAW dengan mempertimbangkan
                rating, jumlah halaman, popularitas, harga, dan jumlah review.
            </p>
        </div>
    </div>

</body>

</html>