<?php
session_start();
if (!isset($_SESSION['username'])) header("location:index.php");
include "db.php";
$jml_kriteria = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria"));
$jml_alternatif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_alternatif"));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard SPK SAW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: url('uploads/perpus2.jpeg') center/cover no-repeat;
        margin: 0;
        min-height: 100vh;
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

    /* Main content */
    .main-content {
        margin-left: 220px;
        padding: 0 20px;
    }

    .card-dashboard p {
        color: #fff;
    }

    .card-icon {
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
    }

    .card-dashboard h3 {
        color: #fff;
    }

    /* Header dengan background gambar */
    .header {
        height: 100px;
        width: 1000px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 3rem;
        font-weight: 700;
        border-radius: 0 0 20px 20px;
        margin-bottom: 30px;
        margin-left: 120px;
    }

    /* Dashboard Cards */
    .card-dashboard {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 2rem;
        color: #fff;
    }

    .card-dashboard:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    }

    .card-dashboard h3 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .card-dashboard p {
        font-size: 1.3rem;
        font-weight: 600;
        opacity: 0.85;
    }

    .card-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    </style>
</head>

<body>

    <div class="sidebar">
        <img src="uploads/perpus1.jpeg" alt="Foto Profil">
        <h3><?= $_SESSION['username']; ?></h3>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="kriteria.php"><i class="fas fa-list-check me-2"></i> Kriteria</a>
        <a href="alternatif.php"><i class="fas fa-book me-2"></i> Daftar Novel</a>
        <a href="perhitungan.php"><i class="fas fa-calculator me-2"></i> Perhitungan</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            Azzvel Web
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-6">
                <div class="card-dashboard text-info">
                    <div class="card-icon"><i class="fas fa-list-check"></i></div>
                    <h3><?= $jml_kriteria; ?></h3>
                    <p>Kriteria</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-dashboard text-success">
                    <div class="card-icon"><i class="fas fa-users"></i></div>
                    <h3><?= $jml_alternatif; ?></h3>
                    <p>Daftar Novel</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>