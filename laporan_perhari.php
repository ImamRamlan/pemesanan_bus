<?php
session_start();
$title = "Laporan Per Hari | Pemesanan Buss";
include 'koneksi.php';
include 'header.php';

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

// Fetch total data
$query_pengguna = "SELECT COUNT(*) AS total_pengguna FROM pengguna212109";
$result_pengguna = mysqli_query($db, $query_pengguna);
$row_pengguna = mysqli_fetch_assoc($result_pengguna);
$total_pengguna = $row_pengguna['total_pengguna'];

$query_pendapatan = "SELECT SUM(total_harga) AS total_pendapatan FROM pemesanan_tiket";
$result_pendapatan = mysqli_query($db, $query_pendapatan);
$row_pendapatan = mysqli_fetch_assoc($result_pendapatan);
$total_pendapatan = $row_pendapatan['total_pendapatan'];

$query_jumlah_bus = "SELECT COUNT(*) AS total_bus FROM bus212109";
$result_jumlah_bus = mysqli_query($db, $query_jumlah_bus);
$row_jumlah_bus = mysqli_fetch_assoc($result_jumlah_bus);
$total_bus = $row_jumlah_bus['total_bus'];

$query_jumlah_rute = "SELECT COUNT(*) AS total_rute FROM rute";
$result_jumlah_rute = mysqli_query($db, $query_jumlah_rute);
$row_jumlah_rute = mysqli_fetch_assoc($result_jumlah_rute);
$total_rute = $row_jumlah_rute['total_rute'];

include 'sidebar.php';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Laporan Pemesanan</h5>
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label for="tanggal_mulai">Tanggal Mulai:</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="<?php echo isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : ''; ?>">
                            </div>
                            <div class="col-lg-6">
                                <label for="tanggal_akhir">Tanggal Akhir:</label>
                                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="submit" name="reset" value="1" class="btn btn-secondary">Reset</button>
                        <a href="download_laporan_pdf.php?tanggal_mulai=<?php echo isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : ''; ?>&tanggal_akhir=<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; ?>" class="btn btn-success">Download Laporan PDF</a>
                    </form>

                    <?php
                    // If reset button is clicked, remove filters
                    if (isset($_GET['reset']) && $_GET['reset'] == '1') {
                        unset($_GET['tanggal_mulai']);
                        unset($_GET['tanggal_akhir']);
                    }

                    // Get filtered parameters
                    $tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
                    $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

                    // Determine if filters are applied
                    $show_table = !empty($tanggal_mulai) && !empty($tanggal_akhir);

                    if ($show_table) :
                        // Query to get filtered bookings with bus names
                        $query_pemesanan = "SELECT pt.id_pemesanan, r.nama_rute, b.nama_bus, pt.jumlah_tiket, pt.total_harga, pt.bukti_pembayaran, pt.status_pembayaran, pt.tanggal_pesan
                            FROM pemesanan_tiket pt
                            INNER JOIN rute r ON pt.id_rute = r.id_rute
                            INNER JOIN bus212109 b ON r.id_bus = b.id_bus
                            INNER JOIN pengguna212109 pg ON pt.id_pengguna = pg.id_pengguna
                            WHERE pt.tanggal_pesan BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
                        $result_pemesanan = mysqli_query($db, $query_pemesanan);

                        // Initialize total variables
                        $total_tiket = 0;
                        $total_pendapatan_filtered = 0;
                    ?>

                        <!-- Tabel untuk menampilkan data pemesanan -->
                        <table class="table">
                            <thead class="text-primary">
                                <th>Nomor</th>
                                <th>Tanggal Pesan</th>
                                <th>Nama Bus</th>
                                <th>Rute</th>
                                <th>Jumlah Tiket</th>
                                <th>Harga Tiket</th>
                                <th>Total Harga Tiket</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $nomor = 1; // Inisialisasi nomor urut
                                while ($row_pemesanan = mysqli_fetch_assoc($result_pemesanan)) : ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?></td>
                                        <td><?php echo $row_pemesanan['tanggal_pesan']; ?></td>
                                        <td><?php echo $row_pemesanan['nama_bus']; ?></td>
                                        <td><?php echo $row_pemesanan['nama_rute']; ?></td>
                                        <td><?php echo $row_pemesanan['jumlah_tiket']; ?></td>
                                        <td>Rp. <?php echo number_format($row_pemesanan['total_harga'] / $row_pemesanan['jumlah_tiket'], 2); ?></td>
                                        <td>Rp. <?php echo number_format($row_pemesanan['total_harga'], 2); ?></td>
                                        <td>
                                            <a href="download_pdf.php?id_pemesanan=<?php echo $row_pemesanan['id_pemesanan']; ?>" class="btn btn-success btn-sm">Cetak</a>
                                        </td>
                                    </tr>
                                    <?php
                                    // Add to totals
                                    $total_tiket += $row_pemesanan['jumlah_tiket'];
                                    $total_pendapatan_filtered += $row_pemesanan['total_harga'];
                                    ?>
                                <?php endwhile; ?>
                                <tr>
                                    <th colspan="4">Jumlah Total Tiket</th>
                                    <th><?php echo number_format($total_tiket); ?></th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th colspan="4">Total Pendapatan</th>
                                    <th colspan="4">Rp. <?php echo number_format($total_pendapatan_filtered, 2); ?></th>
                                </tr>
                            </tbody>
                        </table>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
