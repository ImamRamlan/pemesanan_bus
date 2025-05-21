<?php
include '../koneksi.php'; 

// Inisialisasi pesan error
$error = '';

// Periksa apakah formulir sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai yang dikirim dari formulir
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['nomor_telepon'];

    // Query SQL untuk memeriksa keberadaan username
    $check_query = "SELECT * FROM pengguna212109 WHERE username = '$username'";
    $result = mysqli_query($db, $check_query);

    // Jika hasil query mengembalikan baris, berarti username sudah ada
    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah terdaftar. Silakan gunakan username lain.";
    } else {
        // Query SQL untuk menyimpan data ke dalam tabel pengguna
        $query = "INSERT INTO pengguna212109 (nama, username, password, alamat, nomor_telepon) 
                  VALUES ('$nama', '$username', '$password', '$alamat', '$no_telp')";

        // Eksekusi query
        if (mysqli_query($db, $query)) {
            echo "<div class='alert alert-success' role='alert'>
                    Registrasi berhasil! Data telah disimpan ke database.
                  </div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>
                    Gagal menyimpan data ke database: " . mysqli_error($db) . "
                  </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("bg_login.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Registrasi Akun</div>
                    <div class="card-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                                <!-- Tampilkan pesan error jika ada -->
                                <div class="text-danger"><?php echo $error; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" placeholder="Masukkan Nomor Telepon" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Daftar</button> <a href="login.php" class="btn btn-success">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
