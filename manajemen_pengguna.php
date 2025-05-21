<?php
session_start();
$title = "Manajemen Pengguna | Pemesanan Buss";
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
                <?php unset($_SESSION['delete_message']); // Hapus pesan kesalahan setelah ditampilkan 
                ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Data Pengguna</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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
