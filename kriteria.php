<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "db.php";

/* ===== NAMA KRITERIA ===== */
$nama_kriteria = [
    1 => "(C1) Rating (%)",
    2 => "(C2)Jumlah Halaman (%)",
    3 => "(C3) Popularitas (%)",
    4 => "(C4) Harga (%)",
    5 => "(C5) Jumlah Review (%)"
];

/* ===== TAMBAH ===== */
if (isset($_POST['action']) == 'add') {
    $C1 = $_POST['C1'];
    $C2 = $_POST['C2'];
    $C3 = $_POST['C3'];
    $C4 = $_POST['C4'];
    $C5 = $_POST['C5'];
    $total = $C1+$C2+$C3+$C4+$C5;

    mysqli_query($koneksi,"INSERT INTO tbl_kriteria 
    (C1,C2,C3,C4,C5,total,id_daftar)
    VALUES ('$C1','$C2','$C3','$C4','$C5','$total','1')");

    header("location:kriteria.php");
    exit;
}

/* ===== UPDATE ===== */
if (isset($_POST['action']) == 'update') {
    $id = $_POST['id_kriteria'];
    $C1 = $_POST['C1'];
    $C2 = $_POST['C2'];
    $C3 = $_POST['C3'];
    $C4 = $_POST['C4'];
    $C5 = $_POST['C5'];
    $total = $C1+$C2+$C3+$C4+$C5;

    mysqli_query($koneksi,"UPDATE tbl_kriteria SET
    C1='$C1',C2='$C2',C3='$C3',C4='$C4',C5='$C5',total='$total'
    WHERE id_kriteria='$id'");

    header("location:kriteria.php");
    exit;
}

/* ===== HAPUS ===== */
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi,"DELETE FROM tbl_kriteria WHERE id_kriteria='$_GET[hapus]'");
    header("location:kriteria.php");
    exit;
}

/* ===== DATA ===== */
$data = mysqli_query($koneksi,"SELECT * FROM tbl_kriteria");
$kriterias = [];
while($row=mysqli_fetch_assoc($data)){
    $kriterias[]=$row;
}

/* ===== BOBOT ===== */
$last = end($kriterias);
$bobot = ['C1'=>0,'C2'=>0,'C3'=>0,'C4'=>0,'C5'=>0];
$norm = ['C1'=>0,'C2'=>0,'C3'=>0,'C4'=>0,'C5'=>0];

if ($last) {
    foreach($bobot as $k=>$v){
        $bobot[$k]=$last[$k];
    }

    $total=array_sum($bobot);
    if($total>0){
        foreach($bobot as $k=>$v){
            $norm[$k]=round($v/$total,2);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kriteria</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        background: url('uploads/perpus3.jpeg') center/cover no-repeat;
        font-family: 'Poppins', sans-serif;
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


    /* CONTENT */
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

    .btn {
        background: transparent !important;
        color: #fff !important;
        border: 1px solid #fff !important;
        transition: 0.3s;
    }

    /* Hover tombol */
    .btn:hover {
        background: #fff !important;
        color: #000 !important;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        box-shadow: none;
    }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <img src="uploads/perpus1.jpeg">
        <h3><?= $_SESSION['username'] ?></h3>

        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="kriteria.php" class="active"><i class="fas fa-list-check me-2"></i> Kriteria</a>
        <a href="alternatif.php"><i class="fas fa-book me-2"></i> Daftar Novel</a>
        <a href="perhitungan.php"><i class="fas fa-calculator me-2"></i> Perhitungan</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="main">
        <div class="row">

            <!-- FORM -->
            <div class="col-md-4">
                <div class="card p-3">

                    <?php if(isset($_GET['edit'])):
$id=$_GET['edit'];
$q=mysqli_query($koneksi,"SELECT * FROM tbl_kriteria WHERE id_kriteria='$id'");
$row=mysqli_fetch_assoc($q);
?>

                    <h5>Edit Kriteria</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_kriteria" value="<?= $id ?>">

                        <?php for($i=1;$i<=5;$i++): ?>
                        <label><?= $nama_kriteria[$i] ?></label>
                        <input type="number" name="C<?= $i ?>" class="form-control mb-2" value="<?= $row['C'.$i] ?>"
                            required>
                        <?php endfor; ?>

                        <button class="btn btn-primary">Update</button>
                    </form>

                    <?php else: ?>

                    <h5>Input Kriteria</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="add">

                        <?php for($i=1;$i<=5;$i++): ?>
                        <label><?= $nama_kriteria[$i] ?></label>
                        <input type="number" name="C<?= $i ?>" class="form-control mb-2" required>
                        <?php endfor; ?>

                        <button class="btn btn-success">Simpan</button>
                    </form>

                    <?php endif; ?>

                </div>
            </div>

            <!-- TABEL -->
            <div class="col-md-8">

                <!-- PEMBOBOTAN -->
                <?php if($last): ?>
                <div class="card p-3">
                    <h5 class="text-success">Pembobotan Kriteria</h5>

                    <table class="table table-bordered text-center">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                            <th>Normalisasi</th>
                        </tr>

                        <?php for($i=1;$i<=5;$i++): ?>
                        <tr>
                            <td><?= $nama_kriteria[$i] ?></td>
                            <td><?= $bobot['C'.$i] ?></td>
                            <td><?= $norm['C'.$i] ?></td>
                        </tr>
                        <?php endfor; ?>

                        <tr>
                            <td><b>Total</b></td>
                            <td><?= array_sum($bobot) ?></td>
                            <td>1</td>
                        </tr>
                    </table>

                </div>
            </div>
            <?php endif; ?>

            <!-- DATA KRITERIA -->
            <div class="card p-3">
                <h5>Data Kriteria</h5>

                <table class="table table-bordered text-center">
                    <tr>
                        <th>No</th>
                        <?php for($i=1;$i<=5;$i++): ?>
                        <th><?= $nama_kriteria[$i] ?></th>
                        <?php endfor; ?>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>

                    <?php $no=1; foreach($kriterias as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['C1'] ?></td>
                        <td><?= $r['C2'] ?></td>
                        <td><?= $r['C3'] ?></td>
                        <td><?= $r['C4'] ?></td>
                        <td><?= $r['C5'] ?></td>
                        <td><?= $r['total'] ?></td>
                        <td>
                            <a href="?edit=<?= $r['id_kriteria'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?hapus=<?= $r['id_kriteria'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

        </div>
    </div>
    </div>

</body>

</html>