<?php
session_start();

// Menghubungkan ke database
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data yang dikirimkan dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Melakukan query ke database untuk memeriksa apakah username dan password cocok
    $query = "SELECT * FROM admin_karyawan WHERE username='$username' AND password='$password' AND role='$role'";
    $result = mysqli_query($db, $query);

    // Menghitung jumlah baris hasil query
    $count = mysqli_num_rows($result);

    // Jika data ditemukan, maka login berhasil
    if ($count == 1) {
        // Mengarahkan ke halaman dashboard atau halaman sesuai peran (role)
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['nama'] = $row['nama']; // Simpan nama admin ke dalam sesi
        
        if ($role == 'admin') {
            header("location: halaman.php"); // Ubah sesuai dengan halaman dashboard admin
            exit(); // Penting: hentikan eksekusi skrip setelah melakukan pengalihan header
        } elseif ($role == 'petugas') {
            header("location: halaman.php"); // Ubah sesuai dengan halaman dashboard petugas
            exit(); // Penting: hentikan eksekusi skrip setelah melakukan pengalihan header
        } else {
            // Role lainnya bisa ditambahkan di sini
            echo "Role tidak valid";
        }
    } else {
        // Jika data tidak ditemukan, maka login gagal
        $error = "Username, password, atau peran tidak valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Karyawan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg_login.jpg'); /* Ubah 'background.jpg' sesuai dengan nama file gambar Anda */
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
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <!-- Input username, password, dan peran (role) -->
            <input type="text" name="username" required placeholder="Masukkan Username..">
            <input type="password" name="password" required placeholder="Masukkan Kata sandi..">
            <select name="role" required>
                <option value="" disabled selected>Pilih Peran...</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
            </select>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>
