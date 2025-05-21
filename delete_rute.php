<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID rute dari query string
$id_rute = isset($_GET['id_rute']) ? intval($_GET['id_rute']) : 0;

// Jika tidak ada ID rute, redirect ke manajemen_rute.php
if ($id_rute == 0) {
    $_SESSION['delete_message'] = "ID rute tidak valid.";
    header("Location: manajemen_rute.php");
    exit();
}

// Query untuk menghapus data rute
$query = "DELETE FROM rute WHERE id_rute = $id_rute";

// Jalankan query
$result = mysqli_query($db, $query);

if ($result) {
    // Set pesan sukses ke dalam sesi
    $_SESSION['success_message'] = "Data rute berhasil dihapus.";
} else {
    // Jika terjadi kesalahan dalam menghapus data, set pesan kesalahan ke dalam sesi
    $_SESSION['delete_message'] = "Error: " . mysqli_error($db);
}

// Redirect ke halaman manajemen_rute setelah berhasil menghapus data
header("Location: manajemen_rute.php");
exit();
?>
