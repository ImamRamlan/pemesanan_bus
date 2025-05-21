<?php
$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($role == 'admin') {
} elseif ($role == 'petugas') {
} else {
    echo "Role tidak valid";
}
?>

<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="assets/img/logo-small.png">
            </div>
        </a>
        <a href="#" class="simple-text logo-normal">
            <?php echo $_SESSION['role'] . " - " . $_SESSION['nama']; ?> <!-- Menampilkan role dan nama -->
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'halaman.php' ? 'class="active"' : ''; ?>>
                <a href="halaman.php">
                    <i class="nc-icon nc-bank"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'manajemen_petugas.php' ? 'class="active"' : ''; ?>>
                <a href="manajemen_petugas.php">
                    <i class="nc-icon nc-diamond"></i>
                    <p>Manajemen Karyawan</p>
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'manajemen_pengguna.php' ? 'class="active"' : ''; ?>>
                <a href="manajemen_pengguna.php">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Manajemen Pengguna</p>
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'manajemen_bus.php' ? 'class="active"' : ''; ?>>
                <a href="manajemen_bus.php">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>Manajemen Bus</p>
                </a>
            </li>
            <li>
                <a>
                Main More Fiture.
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'manajemen_pemesanan.php' ? 'class="active"' : ''; ?>>
                <a href="manajemen_pemesanan.php">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Pemesanan Tiket</p>
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'manajemen_rute.php' ? 'class="active"' : ''; ?>>
                <a href="manajemen_rute.php">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Manajemen Rute</p>
                </a>
            </li>
            <li <?php echo basename($_SERVER['PHP_SELF']) == 'laporan_perhari.php' ? 'class="active"' : ''; ?>>
                <a href="laporan_perhari.php">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Laporan Perhari</p>
                </a>
            </li>
            <li>
                <a href="#" onclick="confirmLogout()">
                    <i class="nc-icon nc-spaceship"></i>
                    <p>Keluar</p>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    function confirmLogout() {
        // Tampilkan kotak dialog konfirmasi
        var result = confirm("Apakah Anda yakin ingin keluar?");

        // Jika pengguna memilih "Ya", redirect ke logout.php
        if (result) {
            window.location.href = "logout.php";
        }
    }
</script>