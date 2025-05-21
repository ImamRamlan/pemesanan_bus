<?php
session_start();
include '../koneksi.php';

// Inisialisasi variabel error_message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari formulir
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa kredensial login
    $query = "SELECT * FROM pengguna212109 WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            // Login berhasil, tarik semua data kolom
            $user = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $user['username'];
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['alamat'] = $user['alamat'];
            $_SESSION['nomor_telepon'] = $user['nomor_telepon'];

            // Redirect ke halaman dashboard atau halaman utama
            header("Location: dashboard.php");
            exit();
        } else {
            // Jika login gagal, atur pesan kesalahan
            $error = "Username atau password salah.";
        }
    } else {
        // Jika terjadi kesalahan dalam query, tampilkan pesan kesalahan
        $error = "Error: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Pemesanan Buss</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg_login.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }

        .container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        h5 {
            text-align: center;
            margin-bottom: 20px;
        }

        p.note {
            text-align: center;
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        select,
        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h5 class="text-center">Masuk | Pemesanan Buss</h5>
        <p class="note">Silakan masukkan kredensial login Anda.</p>

        <form action="" method="POST" id="loginForm">
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <!-- Input username dan password -->
            <input type="text" name="username" required placeholder="Masukkan Username..">
            <input type="password" name="password" required placeholder="Masukkan Kata sandi..">
            <input type="submit" value="Login">
            <br>
            <a href="registrasi.php">Registrasi Akun</a>
        </form>
    </div>
</body>

</html>
