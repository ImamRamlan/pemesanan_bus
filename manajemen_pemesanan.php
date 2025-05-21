<?php
session_start();
$title = "Manajemen Pemesanan | Pemesanan Bus";
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}
include 'header.php';
include 'koneksi.php';
?>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($_SESSION['success_message'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['delete_message'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['delete_message']; ?>
                </div>
                <?php unset($_SESSION['delete_message']); ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Pemesanan Tiket</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>Username</th>
                                <th>Rute</th>
                                <th>Tanggal Pesan</th>
                                <th>Status Pembayaran</th>
                                <th>Nomor Kursi</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryPemesanan = mysqli_query($db, "SELECT pt.id_pemesanan, p.username, r.nama_rute, pt.tanggal_pesan, pt.status_pembayaran, GROUP_CONCAT(kd.nomor_kursi ORDER BY kd.nomor_kursi ASC SEPARATOR ', ') as nomor_kursi 
                                                                     FROM pemesanan_tiket pt
                                                                     JOIN pengguna212109 p ON pt.id_pengguna = p.id_pengguna
                                                                     JOIN rute r ON pt.id_rute = r.id_rute
                                                                     LEFT JOIN kursi_dipesan kd ON pt.id_pemesanan = kd.id_pemesanan
                                                                     GROUP BY pt.id_pemesanan");
                                while ($dataPemesanan = mysqli_fetch_array($queryPemesanan)) {
                                ?>
                                    <tr>
                                        <th><?php echo $no; ?></th>
                                        <td><?php echo $dataPemesanan['username']; ?></td>
                                        <td><?php echo $dataPemesanan['nama_rute']; ?></td>
                                        <td><?php echo $dataPemesanan['tanggal_pesan']; ?></td>
                                        <td><?php echo $dataPemesanan['status_pembayaran']; ?></td>
                                        <td><?php echo $dataPemesanan['nomor_kursi']; ?></td>
                                        <td>
                                            <a href="detail_pemesanan.php?id_pemesanan=<?php echo $dataPemesanan['id_pemesanan']; ?>" class="btn btn-info">Detail</a>
                                        </td>
                                    </tr>
                                <?php
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
