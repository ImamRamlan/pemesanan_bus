<?php
session_start();
$title = "Manajemen Bus | Pemesanan Buss";
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
                    <h5 class="card-title"> Data Bus</h5>
                    <a href="tambah_bus.php" class="btn btn-primary">Tambah Data +</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Bus</th>
                                <th>Nomor Plat</th>
                                <th>Jumlah Kursi</th>
                                <th>Instansi</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryBus = mysqli_query($db, "SELECT * FROM bus212109");
                                while ($dataBus = mysqli_fetch_array($queryBus)) {




































                                    
                                ?>
                                    <tr>
                                        <th><?php echo $no; ?></th>
                                        <td><img src="gambar_bus/<?php echo $dataBus['gambar']; ?>" alt="<?php echo $dataBus['nama_bus']; ?>" width="100"></td>
                                        <td><?php echo $dataBus['nama_bus']; ?></td>
                                        <td><?php echo $dataBus['nomor_plat']; ?></td>
                                        <td><?php echo $dataBus['jumlah_kursi']; ?></td>
                                        <td><?php echo $dataBus['instansi']; ?></td>
                                        <td>
                                            <a href="edit_bus.php?id_bus=<?php echo $dataBus['id_bus']; ?>" class="btn btn-warning">Edit</a>
                                            <a href="delete_bus.php?id_bus=<?php echo $dataBus['id_bus']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?');">Hapus</a>
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
