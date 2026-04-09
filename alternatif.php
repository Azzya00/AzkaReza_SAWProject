<?php
session_start();
include "db.php";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$id_daftar = 1;

/* TAMBAH */
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $kode = $_POST['kode'];
    $novel = $_POST['novel'];
    $C1 = (float)$_POST['C1'];
    $C2 = (float)$_POST['C2'];
    $C3 = (float)$_POST['C3'];
    $C4 = (float)$_POST['C4'];
    $C5 = (float)$_POST['C5'];

    mysqli_query($koneksi,"INSERT INTO tbl_alternatif 
    (kode,novel,C1,C2,C3,C4,C5,id_daftar)
    VALUES ('$kode','$novel','$C1','$C2','$C3','$C4','$C5','$id_daftar')");

    header("Location: alternatif.php");
    exit;
}

/* UPDATE */
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id_alternatif'];
    $kode = $_POST['kode'];
    $novel = $_POST['novel'];
    $C1 = $_POST['C1'];
    $C2 = $_POST['C2'];
    $C3 = $_POST['C3'];
    $C4 = $_POST['C4'];
    $C5 = $_POST['C5'];

    mysqli_query($koneksi,"UPDATE tbl_alternatif SET 
    kode='$kode',
    novel='$novel',
    C1='$C1',C2='$C2',C3='$C3',C4='$C4',C5='$C5'
    WHERE id_alternatif='$id'");

    header("Location: alternatif.php");
    exit;
}

/* HAPUS */
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi,"DELETE FROM tbl_alternatif WHERE id_alternatif='$_GET[hapus]'");
    header("Location: alternatif.php");
    exit;
}

/* DATA */
$data = mysqli_query($koneksi,"SELECT * FROM tbl_alternatif");
$alternatifs = [];
while($row=mysqli_fetch_assoc($data)){
    $alternatifs[]=$row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alternatif</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: url('uploads/perpus4.jpeg') center/cover no-repeat;
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

    /* CONTENT (INI YANG FIX BERANTAKAN) */
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
        color: #fff;
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
        <a href="kriteria.php"><i class="fas fa-list-check me-2"></i> Kriteria</a>
        <a href="alternatif.php" class="active"><i class="fas fa-book me-2"></i> Daftar Novel</a>
        <a href="perhitungan.php"><i class="fas fa-calculator me-2"></i> Perhitungan</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="main">
        <div class="row">

            <!-- FORM -->
            <div class="col-md-4">
                <div class="card p-3">

                    <?php if (isset($_GET['edit'])):
$id=$_GET['edit'];
$q=mysqli_query($koneksi,"SELECT * FROM tbl_alternatif WHERE id_alternatif='$id'");
$row=mysqli_fetch_assoc($q);
?>

                    <h5>Edit Alternatif</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_alternatif" value="<?= $id ?>">

                        <label>Kode Novel</label>
                        <input type="text" name="kode" class="form-control mb-2" value="<?= $row['kode'] ?>">

                        <label>Judul Novel</label>
                        <input type="text" name="novel" class="form-control mb-2" value="<?= $row['novel'] ?>">

                        <?php for($i=1;$i<=5;$i++): ?>
                        <label>C<?= $i ?></label>
                        <input type="number" name="C<?= $i ?>" class="form-control mb-2" value="<?= $row['C'.$i] ?>">
                        <?php endfor; ?>

                        <button class="btn btn-primary">Update</button>
                    </form>

                    <?php else: ?>

                    <h5>Input Alternatif</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="add">

                        <label>Kode Novel</label>
                        <input type="text" name="kode" class="form-control mb-2">

                        <label>Judul Novel</label>
                        <input type="text" name="novel" class="form-control mb-2">

                        <?php for($i=1;$i<=5;$i++): ?>
                        <label>C<?= $i ?></label>
                        <input type="number" name="C<?= $i ?>" class="form-control mb-2">
                        <?php endfor; ?>

                        <button class="btn btn-success">Simpan</button>
                    </form>

                    <?php endif; ?>

                </div>
            </div>

            <!-- TABEL -->
            <div class="col-md-8">
                <div class="card p-3">

                    <h5>Daftar Novel</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <tr>
                                <th>#</th>
                                <th>Kode Novel</th>
                                <th>Judul Novel</th>
                                <th>Rating</th>
                                <th>Jumlah Halaman</th>
                                <th>Popularitas</th>
                                <th>Harga</th>
                                <th>Jumlah Review</th>
                                <th>Aksi</th>
                            </tr>

                            <?php $no=1; foreach($alternatifs as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $r['kode'] ?></td>
                                <td><?= $r['novel'] ?></td>
                                <td><?= $r['C1'] ?></td>
                                <td><?= $r['C2'] ?></td>
                                <td><?= $r['C3'] ?></td>
                                <td><?= $r['C4'] ?></td>
                                <td><?= $r['C5'] ?></td>
                                <td>
                                    <a href="?edit=<?= $r['id_alternatif'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?hapus=<?= $r['id_alternatif'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>