<?php
include '../koneksi.php';
$title = "Dashboard | Pemesanan Bus";
session_start(); // Mulai sesi
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
include 'header.php';
?>
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1>Reservasi Bus<span>.</span></h1>
                <h2>Pemesanan.</h2>
            </div>
        </div>
    </div>
</section><!-- End Hero -->
<?php include 'footer.php'; ?>
