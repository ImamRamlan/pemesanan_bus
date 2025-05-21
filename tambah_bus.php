<?php
session_start();
$title = "Tambah Bus | Pemesanan Buss";
include 'koneksi.php';
include 'header.php';

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel error_message
$error_message = "";
$success_message = ""; // Inisialisasi variabel success_message

// Proses tambah data bus
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $nama_bus = $_POST['nama_bus'];
    $nomor_plat = $_POST['nomor_plat'];
    $jumlah_kursi = $_POST['jumlah_kursi'];
    $instansi = $_POST['instansi'];

    // Cek apakah nama bus sudah ada
    $query_check_nama_bus = "SELECT COUNT(*) as count FROM bus212109 WHERE nama_bus = '$nama_bus'";
    $result_check_nama_bus = mysqli_query($db, $query_check_nama_bus);
    $data_check_nama_bus = mysqli_fetch_assoc($result_check_nama_bus);
    if ($data_check_nama_bus['count'] > 0) {
        // Jika nama bus sudah ada, atur pesan kesalahan
        $error_message = "Nama bus sudah ada, silakan pilih nama bus lain.";
    } else {
        // File upload handling
        $gambar = 'gambar_bus_default.jpg'; // Nilai default untuk gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $gambar_name = $_FILES['gambar']['name'];
            $gambar_tmp_name = $_FILES['gambar']['tmp_name'];
            $gambar_size = $_FILES['gambar']['size'];
            $gambar_error = $_FILES['gambar']['error'];
            $gambar_ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
            $allowed_extensions = array("jpg", "jpeg", "png");
            // Check if uploaded file is an image
            if (!in_array(strtolower($gambar_ext), $allowed_extensions)) {
                $error_message = "Hanya file gambar dengan ekstensi JPG, JPEG, dan PNG yang diperbolehkan.";
            } elseif ($gambar_size > 5000000) { // Check file size
                $error_message = "Ukuran file gambar terlalu besar. Maksimum 5MB.";
            } else {
                $gambar = uniqid() . '.' . $gambar_ext;
                move_uploaded_file($gambar_tmp_name, 'gambar_bus/' . $gambar);
            }
        }

        if (empty($error_message)) {
            // Query untuk menambah data ke tabel bus
            $query = "INSERT INTO bus212109 (nama_bus, nomor_plat, jumlah_kursi, instansi, gambar) VALUES ('$nama_bus', '$nomor_plat', '$jumlah_kursi', '$instansi', '$gambar')";

            // Jalankan query
            $result = mysqli_query($db, $query);

            if ($result) {
                // Set pesan sukses ke dalam sesi
                $_SESSION['success_message'] = "Data bus berhasil ditambahkan.";
                // Redirect ke halaman manajemen_bus setelah berhasil menambah data
                header("Location: manajemen_bus.php");
                exit();
            } else {
                // Jika terjadi kesalahan dalam menambah data, tampilkan pesan kesalahan
                echo "Error: " . mysqli_error($db);
            }
        }
    }
}

include 'sidebar.php';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Tambah Data Bus</h5>
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="nc-icon nc-simple-remove"></i>
                            </button>
                            <span><b> Gagal - </b> <?php echo $error_message; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Bus</label>
                                    <input type="text" class="form-control" name="nama_bus" placeholder="Masukkan Nama Bus" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nomor Plat</label>
                                    <input type="text" class="form-control" name="nomor_plat" placeholder="Masukkan Nomor Plat" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jumlah Kursi</label>
                                    <input type="number" class="form-control" name="jumlah_kursi" placeholder="Masukkan Jumlah Kursi" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Instansi</label>
                                    <input type="text" class="form-control" name="instansi" placeholder="Masukkan Instansi" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Gambar Bus</label>
                                    <input type="file" class="form-control" name="gambar" accept="image/*" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary btn-round">Tambah Data</button>
                                <a href="manajemen_bus.php" class="btn btn-secondary btn-round">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>