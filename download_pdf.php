<?php
require_once('vendor/autoload.php');
require_once('koneksi.php');

if (!isset($_GET['id_pemesanan'])) {
    die("ID Pemesanan tidak ditemukan.");
}

$id_pemesanan = $_GET['id_pemesanan'];

// Query untuk mendapatkan detail pemesanan, termasuk nama bus
$query = "SELECT pt.id_pemesanan, r.nama_rute, b.nama_bus, pg.nama, pt.jumlah_tiket, pt.total_harga, pt.tanggal_pesan 
          FROM pemesanan_tiket pt
          INNER JOIN rute r ON pt.id_rute = r.id_rute
          INNER JOIN bus212109 b ON r.id_bus = b.id_bus  -- Join dengan tabel bus untuk mendapatkan nama bus
          INNER JOIN pengguna212109 pg ON pt.id_pengguna = pg.id_pengguna
          WHERE pt.id_pemesanan = '$id_pemesanan'";

$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) == 0) {
    die("Data pemesanan tidak ditemukan.");
}

$row = mysqli_fetch_assoc($result);

// Inisialisasi PDF
$pdf = new TCPDF();
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();

// Header PDF
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Detail Pemesanan Tiket', 0, 1, 'C');

// Konten PDF
$pdf->SetFont('helvetica', '', 12);
$html = '
    <h3>Detail Pemesanan</h3>
    <table>
        <tr><td><strong>Nama Pengguna:</strong></td><td>' . $row['nama'] . '</td></tr>
        <tr><td><strong>Rute:</strong></td><td>' . $row['nama_rute'] . '</td></tr>
        <tr><td><strong>Nama Bus:</strong></td><td>' . $row['nama_bus'] . '</td></tr>  <!-- Menambahkan Nama Bus -->
        <tr><td><strong>Jumlah Tiket:</strong></td><td>' . $row['jumlah_tiket'] . '</td></tr>
        <tr><td><strong>Total Harga:</strong></td><td>Rp. ' . number_format($row['total_harga'], 2) . '</td></tr>
        <tr><td><strong>Tanggal Pesan:</strong></td><td>' . $row['tanggal_pesan'] . '</td></tr>
    </table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('detail_pemesanan.pdf', 'I');
?>
