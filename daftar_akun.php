<?php
// Include file koneksi.php untuk menghubungkan ke database
include_once("koneksi.php");

// Include file PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Inisialisasi pesan konfirmasi dan pesan kesalahan
$confirmation_message = "";
$error_message = "";

// Mengecek apakah form telah di submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan data dari form
    $nama_tamu = mysqli_real_escape_string($db, $_POST['nama_tamu']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $telepon = mysqli_real_escape_string($db, $_POST['telepon']);

    // Membuat query untuk memeriksa apakah email sudah ada di database
    $check_email_query = "SELECT * FROM tamu WHERE email = '$email'";
    $check_email_result = mysqli_query($db, $check_email_query);

    if (mysqli_num_rows($check_email_result) > 0) {
        // Jika email sudah ada, tampilkan pesan kesalahan
        $error_message = "<div class='alert alert-danger' role='alert'>Email sudah terdaftar. Silakan gunakan email lain atau login.</div>";
    } else {
        // Membuat query untuk menambahkan data tamu ke database
        $query = "INSERT INTO tamu (nama_tamu, email, password, telepon) VALUES ('$nama_tamu', '$email', '$password', '$telepon')";
        $result = mysqli_query($db, $query);

        // Jika query berhasil dijalankan, kirim email konfirmasi
        if ($result) {
            // Konfigurasi PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'imamkiller77@gmail.com';
                $mail->Password   = 'kxhgtasckhixnrcf';
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;

                $mail->setFrom('imamkiller77@gmail.com', 'Reservasi Hotel');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Konfirmasi Registrasi';
                $mail->Body    = "Terima kasih atas registrasi Anda, $nama_tamu!<br><br>" .
                    "Berikut adalah detail informasi registrasi Anda:<br>" .
                    "Nama: $nama_tamu<br>" .
                    "Email: $email<br>" .
                    "Password: $password<br>" .
                    "Nomor Telepon: $telepon<br><br>" .
                    "Terima kasih!";

                $mail->send();
                // Set pesan konfirmasi
                $confirmation_message = "<div class='alert alert-success' role='alert'>Registrasi berhasil! Silakan cek email Anda untuk konfirmasi.</div>";
            } catch (Exception $e) {
                // Jika email gagal dikirim, hapus data yang baru saja dimasukkan
                $delete_query = "DELETE FROM tamu WHERE email = '$email'";
                mysqli_query($db, $delete_query);

                // Tampilkan pesan kesalahan
                $error_message = "<div class='alert alert-danger' role='alert'>Silahkan periksa koneksi internet Anda dan refresh kembali halaman ini. Terima kasih.</div>";
            }
        } else {
            // Jika query gagal, tampilkan pesan error
            $error_message = "<div class='alert alert-danger' role='alert'>Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun | Reservasi Hotel</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index2.html" class="h1"><b>Reservasi</b>Hotel</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Daftar Akun untuk memulai sesi Anda.</p>

                <!-- Tampilkan pesan konfirmasi di sini -->
                <?php echo $confirmation_message; ?>
                
                <!-- Tampilkan pesan kesalahan di sini -->
                <?php echo $error_message; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nama Lengkap Anda." name="nama_tamu" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nomor Telepon" name="telepon" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <a href="login_tamu.php">Sudah Punya Akun?</a>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
