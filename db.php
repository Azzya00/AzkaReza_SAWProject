<?php
$koneksi = mysqli_connect("localhost", "root", "", "saw");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
