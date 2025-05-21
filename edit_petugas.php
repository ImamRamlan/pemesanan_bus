<?php
session_start();
$title = "Edit Petugas | Pemesanan Buss";
include 'koneksi.php';

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel error_message
$error_message = "";
$success_message = "";

// Periksa apakah parameter id_admin_karyawan telah diterima dari URL
if (!isset($_GET['id_admin_karyawan'])) {
    header("Location: manajemen_petugas.php");
    exit();
}

// Ambil id_admin_karyawan dari URL
$id_admin_karyawan = $_GET['id_admin_karyawan'];

// Periksa apakah data petugas dengan id_admin_karyawan tertentu ada dalam database
$query_check_petugas = "SELECT * FROM admin_karyawan WHERE id_admin_karyawan = $id_admin_karyawan";
$result_check_petugas = mysqli_query($db, $query_check_petugas);

if (mysqli_num_rows($result_check_petugas) <= 0) {
    header("Location: manajemen_petugas.php");
    exit();
}

// Ambil data petugas
$data_petugas = mysqli_fetch_assoc($result_check_petugas);

// Proses edit data petugas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    // Query untuk memperbarui data petugas
    $query_update_petugas = "UPDATE admin_karyawan SET username = '$username', password = '$password', nama = '$nama', role = '$role' WHERE id_admin_karyawan = $id_admin_karyawan";

    // Jalankan query
    $result_update_petugas = mysqli_query($db, $query_update_petugas);

    if ($result_update_petugas) {
        // Set pesan sukses ke dalam sesi
        $_SESSION['success_message'] = "Data petugas berhasil diperbarui.";

        // Redirect ke halaman manajemen_petugas setelah berhasil mengedit data
        header("Location: manajemen_petugas.php");
        exit();
    } else {
        // Jika terjadi kesalahan dalam mengedit data, tampilkan pesan kesalahan
        $error_message = "Gagal mengedit data petugas.";
    }
}

include 'header.php';
include 'sidebar.php';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Edit Data Petugas</h5>
                    <a href="manajemen_petugas.php">&lt; Back</a>
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="nc-icon nc-simple-remove"></i>
                            </button>
                            <span><b>Gagal - </b> <?php echo $error_message; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_admin_karyawan=' . $id_admin_karyawan; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" value="<?php echo $data_petugas['username']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Masukkan Password Baru" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" value="<?php echo $data_petugas['nama']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="Admin" <?php if ($data_petugas['role'] == 'Admin') echo 'selected'; ?>>Admin</option>x
                                        <option value="Petugas" <?php if ($data_petugas['role'] == 'Petugas') echo 'selected'; ?>>Petugas</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary btn-round">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>