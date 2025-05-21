<?php
session_start();
$title = "Edit Rute | Pemesanan Buss";
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

// Ambil ID rute dari query string
$id_rute = isset($_GET['id_rute']) ? intval($_GET['id_rute']) : 0;

// Jika tidak ada ID rute, redirect ke manajemen_rute.php
if ($id_rute == 0) {
    header("Location: manajemen_rute.php");
    exit();
}

// Ambil data rute berdasarkan ID
$query_rute = "SELECT * FROM rute WHERE id_rute = $id_rute";
$result_rute = mysqli_query($db, $query_rute);
$data_rute = mysqli_fetch_assoc($result_rute);

// Jika data rute tidak ditemukan, redirect ke manajemen_rute.php
if (!$data_rute) {
    header("Location: manajemen_rute.php");
    exit();
}

// Proses edit data rute
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $id_bus = $_POST['id_bus'];
    $nama_rute = $_POST['nama_rute'];
    $lokasi_awal = $_POST['lokasi_awal'];
    $lokasi_tujuan = $_POST['lokasi_tujuan'];
    $jam_berangkat = $_POST['jam_berangkat'];
    $jam_tiba = $_POST['jam_tiba'];
    $harga = $_POST['harga'];

    // Cek apakah nama rute sudah ada (kecuali untuk rute ini sendiri)
    $query_check_nama_rute = "SELECT COUNT(*) as count FROM rute WHERE nama_rute = '$nama_rute' AND id_rute != $id_rute";
    $result_check_nama_rute = mysqli_query($db, $query_check_nama_rute);
    $data_check_nama_rute = mysqli_fetch_assoc($result_check_nama_rute);
    if ($data_check_nama_rute['count'] > 0) {
        // Jika nama rute sudah ada, atur pesan kesalahan
        $error_message = "Nama rute sudah ada, silakan pilih nama rute lain.";
    } else {
        if (empty($error_message)) {
            // Query untuk mengupdate data rute
            $query = "UPDATE rute SET id_bus = '$id_bus', nama_rute = '$nama_rute', lokasi_awal = '$lokasi_awal', lokasi_tujuan = '$lokasi_tujuan', jam_berangkat = '$jam_berangkat', jam_tiba = '$jam_tiba', harga = '$harga' WHERE id_rute = $id_rute";

            // Jalankan query
            $result = mysqli_query($db, $query);

            if ($result) {
                // Set pesan sukses ke dalam sesi
                $_SESSION['success_message'] = "Data rute berhasil diupdate.";
                // Redirect ke halaman manajemen_rute setelah berhasil mengupdate data
                header("Location: manajemen_rute.php");
                exit();
            } else {
                // Jika terjadi kesalahan dalam mengupdate data, tampilkan pesan kesalahan
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
                    <h5 class="card-title">Edit Data Rute</h5>
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
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_rute=' . $id_rute; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Bus</label>
                                    <select class="form-control" name="id_bus" required>
                                        <option value="">Pilih Bus</option>
                                        <?php
                                        $queryBus = mysqli_query($db, "SELECT id_bus, nama_bus FROM bus");
                                        while ($dataBus = mysqli_fetch_assoc($queryBus)) {
                                            $selected = $data_rute['id_bus'] == $dataBus['id_bus'] ? 'selected' : '';
                                            echo "<option value='" . $dataBus['id_bus'] . "' $selected>" . $dataBus['nama_bus'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Rute</label>
                                    <input type="text" class="form-control" name="nama_rute" value="<?php echo $data_rute['nama_rute']; ?>" placeholder="Masukkan Nama Rute" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Lokasi Awal</label>
                                    <input type="text" class="form-control" name="lokasi_awal" value="<?php echo $data_rute['lokasi_awal']; ?>" placeholder="Masukkan Lokasi Awal" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Lokasi Tujuan</label>
                                    <input type="text" class="form-control" name="lokasi_tujuan" value="<?php echo $data_rute['lokasi_tujuan']; ?>" placeholder="Masukkan Lokasi Tujuan" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tanggal/Jam Berangkat</label>
                                    <input type="datetime-local" class="form-control" name="jam_berangkat" value="<?php echo $data_rute['jam_berangkat']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jam Tiba</label>
                                    <input type="time" class="form-control" name="jam_tiba" value="<?php echo $data_rute['jam_tiba']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" class="form-control" name="harga" value="<?php echo $data_rute['harga']; ?>" placeholder="Masukkan Harga" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary btn-round">Update Data</button>
                                <a href="manajemen_rute.php" class="btn btn-secondary btn-round">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
