<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // password hash MD5 sesuai database

    $query = mysqli_query($koneksi, "SELECT * FROM tbl_daftar WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['username'] = $username;
        header("location:dashboard.php");
        exit;
    } else {
        echo "<script>alert('Login gagal! Username atau Password salah.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SPK SAW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('uploads/perpus1.jpeg') center/cover no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Card glassmorphism */
    .card-login {
        width: 28rem;
        padding: 2rem;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-login:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .card-login h4 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #fff;
    }

    /* Input with icon */
    /* HAPUS BACKGROUND AUTOFILL */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.15) inset !important;
        box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.15) inset !important;
        -webkit-text-fill-color: #fff !important;
        transition: background-color 9999s ease-in-out 0s;
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #fff;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #fff;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: none;
        color: #fff;
    }

    /* Buttons */
    .btn-login {
        background: transparent;
        border: 2px solid #fff;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-login:hover {
        background: #fff;
        color: #000;
        transform: translateY(-2px);
    }

    .btn-register {
        background: transparent;
        border: 2px solid #fff;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-register:hover {
        background: #fff;
        color: #000;
        transform: translateY(-2px);
    }

    .text-link {
        color: #fff;
        text-align: center;
        display: block;
        margin-top: 10px;
        text-decoration: none;
    }

    .text-link a {
        color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .text-link a:hover {
        color: #ddd !important;
        text-decoration: underline !important;
    }
    </style>
</head>

<body>

    <div class="card card-login">
        <h4>Azzvel Web Login</h4>
        <form method="post">
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-1 input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn btn-login w-100">Masuk</button>
            <a href="register.php" class="btn btn-register w-100">Register</a>
            <span class="text-link"><a href="reset_password.php">Lupa password?</a></span>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>