<?php
include '../koneksi.php'; // Menghubungkan ke database

session_start(); // Mulai sesi

// Pastikan data telah dikirim dari formulir pesan_tiket.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari formulir
    $id_pengguna = $_SESSION['id_pengguna'];
    $id_rute = $_POST['id_rute'];
    $tanggal_pesan = date('Y-m-d'); // Gunakan tanggal hari ini
    $jumlah_tiket = count($_POST['kursi']); // Menghitung jumlah kursi yang dipilih
    $harga_per_tiket = $_POST['harga']; // Ambil harga per tiket dari formulir
    $total_harga = $jumlah_tiket * $harga_per_tiket; // Hitung total harga
    $status_pembayaran = $_POST['status_pembayaran']; // Ambil status pembayaran dari formulir
    $bukti_pembayaran = ''; // Inisialisasi variabel untuk nama file bukti pembayaran

    // Tangani pengunggahan file bukti pembayaran
    $target_dir = "uploads/"; // Direktori untuk menyimpan file
    $target_file = $target_dir . basename($_FILES["bukti_pembayaran"]["name"]); // Path lengkap file yang diunggah
    $uploadOk = 1; // Status upload

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        $_SESSION['error_message'] = "Maaf, file tersebut sudah ada.";
        $uploadOk = 0;
    }

    // Batasi ukuran file
    if ($_FILES["bukti_pembayaran"]["size"] > 500000) {
        $_SESSION['error_message'] = "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Batasi format file yang diperbolehkan
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['error_message'] = "Maaf, hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Jika tidak ada kesalahan pada upload file, lakukan penyimpanan data ke database
    if ($uploadOk == 1) {
        // Coba unggah file
        if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
            // Jika pengunggahan berhasil, atur variabel bukti_pembayaran dengan nama file
            $bukti_pembayaran = basename($_FILES["bukti_pembayaran"]["name"]);
        } else {
            // Jika terjadi kesalahan saat unggah file, atur pesan kesalahan
            $_SESSION['error_message'] = "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    } else {
        // Jika ada kesalahan pada upload file, atur pesan kesalahan
        $_SESSION['error_message'] = "Maaf, file tidak dapat diunggah.";
    }

    // Jika tidak ada pesan kesalahan, lakukan penyimpanan data ke database
    if (empty($_SESSION['error_message'])) {
        // Query SQL untuk menyimpan data pemesanan tiket ke tabel pemesanan_tiket
        $query_pesan_tiket = "INSERT INTO pemesanan_tiket (id_pengguna, id_rute, tanggal_pesan, jumlah_tiket, total_harga, bukti_pembayaran, status_pembayaran) 
                        VALUES ('$id_pengguna', '$id_rute', '$tanggal_pesan', '$jumlah_tiket', '$total_harga', '$bukti_pembayaran', '$status_pembayaran')";

        // Eksekusi query pemesanan tiket
        if (mysqli_query($db, $query_pesan_tiket)) {
            // Ambil ID pemesanan terbaru
            $id_pemesanan = mysqli_insert_id($db);

            // Inisialisasi flag untuk menandai apakah semua query berhasil dieksekusi
            $success = true;

            // Loop untuk menyimpan kursi yang dipesan ke tabel kursi_dipesan
            foreach ($_POST['kursi'] as $kursi) {
                $query_kursi_dipesan = "INSERT INTO kursi_dipesan (id_pemesanan, nomor_kursi, id_rute) VALUES ('$id_pemesanan', '$kursi', '$id_rute')";
                if (!mysqli_query($db, $query_kursi_dipesan)) {
                    // Jika query gagal, atur flag success menjadi false
                    $success = false;
                    break; // Keluar dari loop
                }
            }

            // Jika semua query berhasil dieksekusi, atur pesan sukses
            if ($success) {
                $_SESSION['success_message'] = "Pemesanan tiket berhasil.";
            } else {
                // Jika ada query yang gagal, atur pesan error
                $_SESSION['error_message'] = "Gagal menyimpan data kursi.";
            }
        } else {
            // Jika query pemesanan tiket gagal, atur pesan error
            $_SESSION['error_message'] = "Gagal melakukan pemesanan tiket: " . mysqli_error($db);
        }
    }

    // Redirect kembali ke halaman pesan_tiket.php setelah selesai
    header("Location: pesan_tiket.php?rute_id=$id_rute");
    exit();
} else {
    // Jika akses tidak valid, atur pesan error dan redirect kembali ke halaman pesan_tiket.php
    $_SESSION['error_message'] = "Akses tidak valid.";
    header("Location: pesan_tiket.php?rute_id=$id_rute");
    exit();
}
?>
