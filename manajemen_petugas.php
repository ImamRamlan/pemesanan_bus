<?php
session_start();
$title = "Manajemen Petugas | Pemesanan Buss";
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
                <?php unset($_SESSION['success_message']); // Hapus pesan keberhasilan setelah ditampilkan 
                ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['delete_message'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['delete_message']; ?>
                </div>
                <?php unset($_SESSION['delete_message']); // Hapus pesan keberhasilan setelah ditampilkan 
                ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Data Karyawan</h5>
                    <a href="tambah_petugas.php" class="btn btn-primary">Tambah Data +</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryKaryawan = mysqli_query($db, "SELECT * FROM admin_karyawan");
                                while ($dataKaryawan = mysqli_fetch_array($queryKaryawan)) {
                                ?>
                                    <tr>
                                        <th><?php echo $no; ?></th>
                                        <td><?php echo $dataKaryawan['nama']; ?></td>
                                        <td><?php echo $dataKaryawan['username']; ?></td>
                                        <td><?php echo $dataKaryawan['role']; ?></td>
                                        <td>
                                            <a href="edit_petugas.php?id_admin_karyawan=<?php echo $dataKaryawan['id_admin_karyawan']; ?>" class="btn btn-warning"><i class='nc-icon nc-simple-delete'></i></a>
                                            <a href="delete_petugas.php?id_admin_karyawan=<?php echo $dataKaryawan['id_admin_karyawan']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?');"><i class='nc-icon nc-simple-remove'></i></a>
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