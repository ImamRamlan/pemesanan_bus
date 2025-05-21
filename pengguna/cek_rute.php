<?php
include '../koneksi.php'; // Menghubungkan ke database
$title = "Cek Rute | Pemesanan Bus";

session_start(); // Mulai sesi
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Query untuk mengambil data rute dari database dengan menggabungkan informasi dari tabel rute dan tabel bus
$query = "SELECT r.*, b.gambar AS gambar_bus FROM rute AS r JOIN bus212109 AS b ON r.id_bus = b.id_bus";
$result = mysqli_query($db, $query);

// Memeriksa apakah query berhasil
if (!$result) {
    die("Query gagal dijalankan: " . mysqli_error($db));
}

include 'header.php';
?>

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1>Reservasi Bus<span>.</span></h1>
                <h2>Cek Rute Bus</h2>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<main id="main">
    <section class="portfolio">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Rute</h2>
                <p>Daftar Rute Bus</p>
                
            </div>
            <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-lg-4 col-md-6 portfolio-item filter-card">
                        <div class="portfolio-wrap">
                            <a href="#">
                                <img src="../gambar_bus/<?php echo $row['gambar_bus']; ?>" class="img-fluid" alt="Rute Image">
                            </a>
                            <div class="portfolio-info">
                                <h4><?php echo $row['nama_rute']; ?></h4>

                                <div class="portfolio-links">
                                    <a href="pesan_tiket.php?rute_id=<?php echo $row['id_rute']; ?>" title="Pesan Tiket"><i class="bx bx-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <h5>Dari: <?php echo $row['lokasi_awal']; ?></h5>
                        <h5>Ke: <?php echo $row['lokasi_tujuan']; ?></h5>
                        <h5>Berangkat: <?php echo $row['jam_berangkat']; ?></h5>
                        <h5>Tiba: <?php echo $row['jam_tiba']; ?></h5>
                        <h5>Harga: Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></h5>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>