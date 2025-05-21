<?php
session_start();
$title = "Detail Pemesanan | Pemesanan Bus";

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

include 'header.php';
include 'koneksi.php';

// Ambil ID Pemesanan dari query string
if (isset($_GET['id_pemesanan'])) {
    $id_pemesanan = $_GET['id_pemesanan'];

    // Query untuk mengambil detail pemesanan berdasarkan ID Pemesanan
    $query = "SELECT pt.*, p.username, r.nama_rute, b.nama_bus 
              FROM pemesanan_tiket pt
              JOIN pengguna212109 p ON pt.id_pengguna = p.id_pengguna
              JOIN rute r ON pt.id_rute = r.id_rute
              JOIN bus212109 b ON r.id_bus = b.id_bus
              WHERE pt.id_pemesanan = '$id_pemesanan'";
    $result = mysqli_query($db, $query);

    // Memeriksa apakah query berhasil dan data ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $detailPemesanan = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['error_message'] = "Data pemesanan tidak ditemukan.";
        header("Location: manajemen_pemesanan.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "ID Pemesanan tidak ditemukan.";
    header("Location: manajemen_pemesanan.php");
    exit();
}

// Proses perubahan status pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_pembayaran'])) {
    $status_pembayaran = $_POST['status_pembayaran'];
    $updateQuery = "UPDATE pemesanan_tiket SET status_pembayaran = '$status_pembayaran' WHERE id_pemesanan = '$id_pemesanan'";

    if (mysqli_query($db, $updateQuery)) {
        $detailPemesanan['status_pembayaran'] = $status_pembayaran;
        $_SESSION['success_message'] = "Berhasil Memperbarui Status Pembayaran.";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui status pembayaran.";
    }
}

include 'sidebar.php';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Pemesanan Tiket</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Username</th>
                            <td><?php echo $detailPemesanan['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Rute</th>
                            <td><?php echo $detailPemesanan['nama_rute']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Bus</th>
                            <td><?php echo $detailPemesanan['nama_bus']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesan</th>
                            <td><?php echo $detailPemesanan['tanggal_pesan']; ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Tiket</th>
                            <td><?php echo $detailPemesanan['jumlah_tiket']; ?></td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td><?php echo $detailPemesanan['total_harga']; ?></td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                <form method="POST">
                                    <?php if ($detailPemesanan['status_pembayaran'] == 'Belum Dibayar') : ?>
                                        <button type="submit" name="status_pembayaran" value="Sudah Bayar" class="btn btn-danger">Belum Dibayar</button>
                                    <?php else : ?>
                                        <button type="submit" name="status_pembayaran" value="Belum Dibayar" class="btn btn-success">Sudah Bayar</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <th>Bukti Pembayaran</th>
                            <td>
                                <?php
                                if (!empty($detailPemesanan['bukti_pembayaran'])) {
                                    echo "<img src='pengguna/uploads/{$detailPemesanan['bukti_pembayaran']}' alt='Bukti Pembayaran' style='max-width: 300px;'>";
                                } else {
                                    echo "Belum ada bukti pembayaran";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <a href="manajemen_pemesanan.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>