<?php
require_once('vendor/autoload.php');
require_once('koneksi.php');

// Ambil parameter filter
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Validasi filter tanggal
if (empty($tanggal_mulai) || empty($tanggal_akhir)) {
    die("Filter tanggal tidak lengkap.");
}

// Query untuk mendapatkan data sesuai filter, termasuk nama bus
$query = "SELECT pt.id_pemesanan, r.nama_rute, b.nama_bus, pg.nama, pt.jumlah_tiket, pt.total_harga, pt.tanggal_pesan 
          FROM pemesanan_tiket pt
          INNER JOIN rute r ON pt.id_rute = r.id_rute
          INNER JOIN bus212109 b ON r.id_bus = b.id_bus  -- Join dengan tabel bus untuk mendapatkan nama bus
          INNER JOIN pengguna212109 pg ON pt.id_pengguna = pg.id_pengguna
          WHERE pt.tanggal_pesan BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";

// Eksekusi query
$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) == 0) {
    die("Tidak ada data yang ditemukan.");
}

// Inisialisasi PDF
$pdf = new TCPDF();
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();

// Header PDF
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Pemesanan Tiket', 0, 1, 'C');

// Informasi Filter
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Tanggal: ' . $tanggal_mulai . ' s/d ' . $tanggal_akhir, 0, 1, 'L');

// Header Tabel
$html = '
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal Pesanan</th>
                <th>Rute</th>
                <th>Nama Bus</th>
                <th>Jumlah Tiket</th>
                <th>Harga Per Tiket</th>
                <th>Total Harga Tiket</th>
            </tr>
        </thead>
        <tbody>
';

$total_tiket = 0;
$total_pendapatan = 0;
$nomor = 1;

// Isi Tabel
$pdf->SetFont('helvetica', '', 10);
while ($row = mysqli_fetch_assoc($result)) {
    $harga_per_tiket = $row['total_harga'] / $row['jumlah_tiket'];
    $html .= '
        <tr>
            <td>' . $nomor++ . '</td>
            <td>' . $row['tanggal_pesan'] . '</td>
            <td>' . $row['nama_rute'] . '</td>
            <td>' . $row['nama_bus'] . '</td>  <!-- Menampilkan nama bus -->
            <td>' . $row['jumlah_tiket'] . '</td>
            <td>Rp. ' . number_format($harga_per_tiket, 2) . '</td>
            <td>Rp. ' . number_format($row['total_harga'], 2) . '</td>
        </tr>
    ';
    
    // Hitung total tiket dan pendapatan
    $total_tiket += $row['jumlah_tiket'];
    $total_pendapatan += $row['total_harga'];
}

$html .= '</tbody>';

// Total Tiket dan Pendapatan
$html .= '
    <tfoot>
        <tr>
            <td colspan="4"><strong>Total Tiket</strong></td>
            <td colspan="3"><strong>' . $total_tiket . '</strong></td>
        </tr>
        <tr>
            <td colspan="4"><strong>Total Pendapatan</strong></td>
            <td colspan="3"><strong>Rp. ' . number_format($total_pendapatan, 2) . '</strong></td>
        </tr>
    </tfoot>
</table>';

// Tulis HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('laporan_pemesanan.pdf', 'I');
?>
