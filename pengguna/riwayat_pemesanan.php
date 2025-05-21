<?php
include '../koneksi.php'; // Menghubungkan ke database
$title = "Riwayat Pemesanan | Pemesanan Bus";

session_start(); // Mulai sesi
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$id_pengguna = $_SESSION['id_pengguna'];

// Query untuk mengambil riwayat pemesanan berdasarkan ID pengguna dengan informasi gambar bus
$query = "SELECT pt.id_pemesanan, pt.tanggal_pesan, r.nama_rute, pt.jumlah_tiket, pt.total_harga, pt.status_pembayaran, pt.bukti_pembayaran, b.gambar AS gambar_bus
          FROM pemesanan_tiket pt
          JOIN rute r ON pt.id_rute = r.id_rute
          JOIN bus212109 b ON r.id_bus = b.id_bus
          WHERE pt.id_pengguna = '$id_pengguna'
          ORDER BY pt.tanggal_pesan DESC";

$result = mysqli_query($db, $query);

include 'header.php';
?>

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1>Riwayat Pemesanan<span>.</span></h1>
                <h2>Daftar Riwayat Pemesanan Anda</h2>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<main id="main">
    <section class="portfolio">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Riwayat Pemesanan</h2>
                <p>Daftar Riwayat Pemesanan Anda</p>
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
                                <h4>Tanggal Pesan: <?php echo $row['tanggal_pesan']; ?></h4>
                                <h4>Total Harga: Rp <?php echo number_format($row['total_harga'], 2, ',', '.'); ?></h4>
                                <div class="portfolio-links">
                                    <a href="detail_riwayat.php?id=<?php echo $row['id_pemesanan']; ?>" title="Detail Riwayat"><i class="bx bx-info-circle"></i></a>
                                </div>
                            </div>
                        </div>


                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>