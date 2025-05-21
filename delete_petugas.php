<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah parameter id_admin_karyawan telah diterima dari URL
if (!isset($_GET['id_admin_karyawan'])) {
    header("Location: manajemen_petugas.php");
    exit();
}

// Ambil id_admin_karyawan dari URL
$id_admin_karyawan = $_GET['id_admin_karyawan'];

// Query untuk menghapus data petugas berdasarkan id
$query_delete_petugas = "DELETE FROM admin_karyawan WHERE id_admin_karyawan = $id_admin_karyawan";

// Jalankan query
$result_delete_petugas = mysqli_query($db, $query_delete_petugas);

if ($result_delete_petugas) {
    // Set pesan sukses ke dalam sesi
    $_SESSION['delete_message'] = "Data petugas berhasil dihapus.";
} else {
    // Jika terjadi kesalahan dalam menghapus data, set pesan kesalahan ke dalam sesi
    $_SESSION['error_message'] = "Gagal menghapus data petugas.";
}

// Redirect kembali ke halaman manajemen_petugas setelah berhasil menghapus data atau gagal
header("Location: manajemen_petugas.php");
exit();
?>
