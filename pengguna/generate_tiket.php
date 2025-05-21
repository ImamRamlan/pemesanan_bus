<?php
session_start();
require_once '../koneksi.php';
require_once '../vendor/autoload.php';

use TCPDF as TCPDF;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: riwayat_pemesanan.php");
    exit();
}

$id_pemesanan = $_GET['id'];

// Query untuk mengambil detail pemesanan berdasarkan ID pemesanan
$query = "SELECT pt.*, r.nama_rute, b.gambar AS gambar_bus
          FROM pemesanan_tiket pt
          JOIN rute r ON pt.id_rute = r.id_rute
          JOIN bus b ON r.id_bus = b.id_bus
          WHERE pt.id_pemesanan = '$id_pemesanan'";

$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Data pemesanan tidak ditemukan.");
}

$row = mysqli_fetch_assoc($result);

// Memulai pembuatan PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Nama file PDF dan mode tampilan
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Tiket Pemesanan Bus');
$pdf->SetSubject('Detail Pemesanan Tiket Bus');
$pdf->SetKeywords('Pemesanan, Tiket, PDF');
$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

// Set margin
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Halaman baru
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Generate HTML content
$html = '
<div style="text-align:center">
    <h1>Tiket Pemesanan Bus</h1>
</div>
<hr>
<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <th>Nama Rute</th>
        <td>' . $row['nama_rute'] . '</td>
    </tr>
    <tr>
        <th>Tanggal Pesan</th>
        <td>' . $row['tanggal_pesan'] . '</td>
    </tr>
    <tr>
        <th>Jumlah Tiket</th>
        <td>' . $row['jumlah_tiket'] . '</td>
    </tr>
    <tr>
        <th>Total Harga</th>
        <td>Rp ' . number_format($row['total_harga'], 2, ',', '.') . '</td>
    </tr>
    <tr>
        <th>Status Pembayaran</th>
        <td>' . $row['status_pembayaran'] . '</td>
    </tr>';

if (!empty($row['bukti_pembayaran'])) {
    $html .= '
    <tr>
        <th>Bukti Pembayaran</th>
        <td><img src="../pengguna/uploads/' . $row['bukti_pembayaran'] . '" alt="Bukti Pembayaran" style="max-width:300px;"></td>
    </tr>';
}

$html .= '</table>';

// Tambahkan elemen tambahan seperti TTD dan ucapan terima kasih
$html .= '
<br><br>
<div style="text-align:center">
    <p>--------------------------------</p>
    <p>TTD</p>
    <br><br>
    <p>Terima kasih telah memesan tiket kami.</p>
</div>';

// Write HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF untuk ditampilkan di browser
$pdf->Output('Tiket_Pemesanan_' . $id_pemesanan . '.pdf', 'I');
?>
