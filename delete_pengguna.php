<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

// Include koneksi ke database
include 'koneksi.php';

// Periksa apakah parameter ID pengguna telah diterima dari URL
if (isset($_GET['id_pengguna'])) {
    // Ambil ID pengguna dari URL dan lakukan pembersihan data
    $id_pengguna = mysqli_real_escape_string($db, $_GET['id_pengguna']);

    // Buat dan eksekusi query untuk menghapus pengguna berdasarkan ID
    $sql = "DELETE FROM pengguna212109 WHERE id_pengguna = $id_pengguna";

    if (mysqli_query($db, $sql)) {
        // Jika penghapusan berhasil, set pesan keberhasilan
        $_SESSION['delete_message'] = "Data pengguna berhasil dihapus.";
    } else {
        // Jika terjadi kesalahan dalam menghapus data, set pesan kesalahan
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data pengguna: " . mysqli_error($db);
    }
} else {
    // Jika tidak ada ID pengguna yang diberikan, arahkan kembali ke halaman manajemen pengguna
    $_SESSION['error_message'] = "Tidak ada ID pengguna yang diberikan untuk dihapus.";
}

// Redirect kembali ke halaman manajemen pengguna setelah melakukan operasi
header("location: manajemen_pengguna.php");
exit;
?>
