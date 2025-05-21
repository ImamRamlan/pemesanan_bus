<?php
session_start();
$title = "Laporan Per Hari | Pemesanan Buss";
include 'koneksi.php';
include 'header.php';

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

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

$query_rute = "SELECT * FROM rute";
$result_rute = mysqli_query($db, $query_rute);

$query_tanggal = "SELECT DISTINCT tanggal_pesan FROM pemesanan_tiket";
$result_tanggal = mysqli_query($db, $query_tanggal);
?>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="manajemen_pengguna.php" class="card-link">
                <div class="card card-stats bg-info text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="nc-icon nc-globe"></i>
                                </div>
                            </div>
                            <div class="col-7 text-white">
                                <div class="numbers">
                                    <p class="card-category text-white">Pengguna</p>
                                    <p class="card-title text-white"><?php echo $total_pengguna; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i>
                            Check
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="manajemen_pemesanan.php" class="card-link">
                <div class="card card-stats bg-success text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="nc-icon nc-money-coins"></i>
                                </div>
                            </div>
                            <div class="col-7 text-white">
                                <div class="numbers">
                                    <p class="card-category text-white">Total Pendapatan</p>
                                    <p class="card-title text-white">Rp.<?php echo number_format($total_pendapatan, 2); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-calendar-o"></i>
                            Check
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="manajemen_bus.php" class="card-link">
                <div class="card card-stats bg-danger text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="nc-icon nc-vector"></i>
                                </div>
                            </div>
                            <div class="col-7 text-white">
                                <div class="numbers">
                                    <p class="card-category text-white">Jumlah Bus</p>
                                    <p class="card-title text-white"><?php echo $total_bus; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-clock-o"></i>
                            Check
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="manajemen_rute.php" class="card-link">
                <div class="card card-stats bg-primary text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="nc-icon nc-favourite-28"></i>
                                </div>
                            </div>
                            <div class="col-7 text-white">
                                <div class="numbers">
                                    <p class="card-category text-white">Jumlah Rute</p>
                                    <p class="card-title text-white"><?php echo $total_rute; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i>
                            Check
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <h4>Dashboard Navigasi</h4>
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
                    <h5 class="card-title">Data Pengguna</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryPengguna = mysqli_query($db, "SELECT * FROM pengguna212109");
                                while ($dataPengguna = mysqli_fetch_array($queryPengguna)) {
                                ?>
                                    <tr>
                                        <th><?php echo $no; ?></th>
                                        <td><?php echo $dataPengguna['nama']; ?></td>
                                        <td><?php echo $dataPengguna['username']; ?></td>
                                        <td><?php echo $dataPengguna['alamat']; ?></td>
                                        <td><?php echo $dataPengguna['nomor_telepon']; ?></td>
                                        <td>
                                            <a href="delete_pengguna.php?id_pengguna=<?php echo $dataPengguna['id_pengguna']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?');">Hapus <i class='nc-icon nc-simple-remove'></i></a>
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
