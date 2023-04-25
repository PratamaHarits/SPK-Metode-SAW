<?php
// Koneksi
$conn = mysqli_connect("localhost", "root", "", "db_saw");
// Cek
if (!$conn) {
    die("Gagal terkoneksi : " . mysqli_connect_error());
}
