<?php
include '../koneksi.php'; // Menghubungkan ke database
$title = "Detail Riwayat Pemesanan | Pemesanan Bus";

session_start(); // Mulai sesi
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil ID pemesanan dari parameter URL
$id_pemesanan = $_GET['id'];

// Query untuk mengambil detail pemesanan berdasarkan ID pemesanan
$query = "SELECT pt.*, r.nama_rute, r.jam_berangkat, b.gambar AS gambar_bus, GROUP_CONCAT(kd.nomor_kursi ORDER BY kd.nomor_kursi ASC SEPARATOR ', ') as nomor_kursi
          FROM pemesanan_tiket pt
          JOIN rute r ON pt.id_rute = r.id_rute
          JOIN bus b ON r.id_bus = b.id_bus
          LEFT JOIN kursi_dipesan kd ON pt.id_pemesanan = kd.id_pemesanan
          WHERE pt.id_pemesanan = '$id_pemesanan'
          GROUP BY pt.id_pemesanan";

$result = mysqli_query($db, $query);

// Memeriksa apakah query berhasil
if (!$result || mysqli_num_rows($result) == 0) {
    die("Data pemesanan tidak ditemukan.");
}

$row = mysqli_fetch_assoc($result);

include 'header.php';
?>

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1>Detail Riwayat Pemesanan<span>.</span></h1>
                <h2>Detail Lengkap Pemesanan Tiket Anda</h2>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<main id="main">
    <section class="portfolio-details">
        <div class="container" data-aos="fade-up">
            <div class="portfolio-description">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="portfolio-wrap">
                            <img src="../gambar_bus/<?php echo $row['gambar_bus']; ?>" class="img-fluid" alt="Rute Image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="portfolio-info">
                            <h3>Detail Pemesanan</h3>
                            <ul>
                                <li><strong>Nama Rute:</strong> <?php echo $row['nama_rute']; ?></li>
                                <li><strong>Tanggal Pesan:</strong> <?php echo $row['tanggal_pesan']; ?></li>
                                <li><strong>Tanggal - Jam Berangkat:</strong> <?php echo $row['jam_berangkat']; ?></li>
                                <li><strong>Jumlah Tiket:</strong> <?php echo $row['jumlah_tiket']; ?></li>
                                <li><strong>Total Harga:</strong> Rp <?php echo number_format($row['total_harga'], 2, ',', '.'); ?></li>
                                <li><strong>Status Pembayaran:</strong> <?php echo $row['status_pembayaran']; ?></li>
                                <li><strong>Nomor Kursi:</strong> <?php echo $row['nomor_kursi']; ?></li>
                            </ul>
                            <?php if (!empty($row['bukti_pembayaran'])) : ?>
                                <div class="portfolio-links">
                                    <h3>Bukti Pembayaran</h3>
                                    <img src="uploads/<?php echo $row['bukti_pembayaran']; ?>" class="img-fluid" alt="Bukti Pembayaran">
                                </div>
                            <?php endif; ?>
                            <a href="generate_tiket.php?id=<?php echo $id_pemesanan; ?>" class="btn btn-primary mt-3">Generate Tiket</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
