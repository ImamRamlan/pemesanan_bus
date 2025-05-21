<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Periksa apakah parameter id_bus telah diterima
if (isset($_GET['id_bus'])) {
    $id_bus = $_GET['id_bus'];

    try {
        // Query untuk menghapus data bus berdasarkan id_bus
        $query = "DELETE FROM bus212109 WHERE id_bus='$id_bus'";
        
        // Jalankan query
        $result = mysqli_query($db, $query);

        if ($result) {
            // Set pesan sukses ke dalam sesi
            $_SESSION['delete_message'] = "Data bus berhasil dihapus.";
        } else {
            // Set pesan kesalahan ke dalam sesi jika penghapusan gagal
            $_SESSION['delete_message'] = "Gagal menghapus data bus.";
        }
    } catch (mysqli_sql_exception $e) {
        // Periksa kode kesalahan untuk kunci asing
        if ($e->getCode() == 1451) {
            $_SESSION['delete_message'] = "Bus sedang digunakan dan tidak bisa dihapus.";
        } else {
            $_SESSION['delete_message'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
} else {
    // Set pesan kesalahan ke dalam sesi jika id_bus tidak ditemukan
    $_SESSION['delete_message'] = "ID Bus tidak ditemukan.";
}

// Redirect kembali ke halaman manajemen_bus setelah menghapus data
header("Location: manajemen_bus.php");
exit();
?>
