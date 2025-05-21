<?php
session_start();
$title = "Tambah Petugas | Pemesanan Buss";
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

// Proses tambah data karyawan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    // Cek apakah username sudah digunakan
    $query_check_username = "SELECT COUNT(*) as count FROM admin_karyawan WHERE username = '$username'";
    $result_check_username = mysqli_query($db, $query_check_username);
    $data_check_username = mysqli_fetch_assoc($result_check_username);
    if ($data_check_username['count'] > 0) {
        // Jika username sudah digunakan, atur pesan kesalahan
        $error_message = "Username sudah digunakan, silakan pilih username lain.";
    } else {
        // Query untuk menambah data ke tabel admin_karyawan
        $query = "INSERT INTO admin_karyawan (username, password, nama, role) VALUES ('$username', '$password', '$nama', '$role')";

        // Jalankan query
        $result = mysqli_query($db, $query);

        if ($result) {
            // Set pesan sukses ke dalam sesi
            $_SESSION['success_message'] = "Data karyawan berhasil ditambahkan.";
            // Redirect ke halaman manajemen_karyawan setelah berhasil menambah data
            header("Location: manajemen_petugas.php");
            exit();
        } else {
            // Jika terjadi kesalahan dalam menambah data, tampilkan pesan kesalahan
            echo "Error: " . mysqli_error($db);
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
                    <h5 class="card-title">Tambah Data Karyawan</h5>
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
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Petugas">Petugas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary btn-round">Tambah Data</button>
                                <a href="manajemen_petugas.php" class="btn btn-secondary btn-round">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
