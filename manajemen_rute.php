<?php
session_start();
$title = "Manajemen Rute | Pemesanan Buss";
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan pengalihan header
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
                <?php unset($_SESSION['success_message']); // Hapus pesan keberhasilan setelah ditampilkan ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['delete_message'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['delete_message']; ?>
                </div>
                <?php unset($_SESSION['delete_message']); // Hapus pesan keberhasilan setelah ditampilkan ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Data Rute</h5>
                    <a href="tambah_rute.php" class="btn btn-primary">Tambah Data +</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>Nama Rute</th>
                                <th>Lokasi Awal</th>
                                <th>Lokasi Tujuan</th>
                                <th>Tanggal/Jam Berangkat</th>
                                <th>Jam Tiba</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryRute = mysqli_query($db, "SELECT * FROM rute");
                                while ($dataRute = mysqli_fetch_array($queryRute)) {
                                ?>
                                    <tr>
                                        <th><?php echo $no; ?></th>
                                        <td><?php echo $dataRute['nama_rute']; ?></td>
                                        <td><?php echo $dataRute['lokasi_awal']; ?></td>
                                        <td><?php echo $dataRute['lokasi_tujuan']; ?></td>
                                        <td><?php echo $dataRute['jam_berangkat']; ?></td>
                                        <td><?php echo $dataRute['jam_tiba']; ?></td>
                                        <td><?php echo $dataRute['harga']; ?></td>
                                        <td>
                                            <a href="edit_rute.php?id_rute=<?php echo $dataRute['id_rute']; ?>" class="btn btn-warning"><i class='nc-icon nc-simple-delete'></i></a>
                                            <a href="delete_rute.php?id_rute=<?php echo $dataRute['id_rute']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?');"><i class='nc-icon nc-simple-remove'></i></a>
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
