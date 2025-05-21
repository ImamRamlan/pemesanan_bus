<?php
session_start();
$title = "Edit Bus | Pemesanan Buss";
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

// Proses update data bus
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $id_bus = $_POST['id_bus'];
    $nama_bus = $_POST['nama_bus'];
    $nomor_plat = $_POST['nomor_plat'];
    $jumlah_kursi = $_POST['jumlah_kursi'];
    $instansi = $_POST['instansi'];

    // Query untuk update data bus
    $query = "UPDATE bus SET nama_bus='$nama_bus', nomor_plat='$nomor_plat', jumlah_kursi='$jumlah_kursi', instansi='$instansi' WHERE id_bus='$id_bus'";

    // Jalankan query
    $result = mysqli_query($db, $query);

    if ($result) {
        // Set pesan sukses ke dalam sesi
        $_SESSION['success_message'] = "Data bus berhasil diperbarui.";
        // Redirect ke halaman manajemen_bus setelah berhasil mengupdate data
        header("Location: manajemen_bus.php");
        exit();
    } else {
        // Jika terjadi kesalahan dalam mengupdate data, tampilkan pesan kesalahan
        $error_message = "Error: " . mysqli_error($db);
    }
}

// Ambil ID bus dari parameter URL
$id_bus = $_GET['id_bus'] ?? '';

// Query untuk mendapatkan data bus berdasarkan ID
$query_get_bus = "SELECT * FROM bus212109 WHERE id_bus='$id_bus'";
$result_get_bus = mysqli_query($db, $query_get_bus);
$data_bus = mysqli_fetch_assoc($result_get_bus);

include 'sidebar.php';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Edit Data Bus</h5>
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
                        <input type="hidden" name="id_bus" value="<?php echo $data_bus['id_bus']; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Bus</label>
                                    <input type="text" class="form-control" name="nama_bus" value="<?php echo $data_bus['nama_bus']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nomor Plat</label>
                                    <input type="text" class="form-control" name="nomor_plat" value="<?php echo $data_bus['nomor_plat']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jumlah Kursi</label>
                                    <input type="number" class="form-control" name="jumlah_kursi" value="<?php echo $data_bus['jumlah_kursi']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Instansi</label>
                                    <input type="text" class="form-control" name="instansi" value="<?php echo $data_bus['instansi']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Bus Lama</label><br>
                                    <img src="gambar_bus/<?php echo $data_bus['gambar']; ?>" alt="Gambar Lama">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Bus Baru</label>
                                    <input type="file" class="form-control" name="gambar" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary btn-round">Perbarui Data</button>
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
