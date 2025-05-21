    <?php
    include '../koneksi.php'; // Menghubungkan ke database
    $title = "Pesan Tiket | Pemesanan Bus";

    session_start(); // Mulai sesi
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    // Ambil id_rute dari query string
    $id_rute = $_GET['rute_id'];

    // Ambil data rute dan bus terkait dari database
    $query = "SELECT r.*, b.jumlah_kursi, b.nama_bus, r.harga 
          FROM rute r 
          JOIN bus212109 b ON r.id_bus = b.id_bus 
          WHERE r.id_rute = '$id_rute'";
    $result = mysqli_query($db, $query);

    // Memeriksa apakah query berhasil
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_error($db));
    }

    $rute = mysqli_fetch_assoc($result);

    include 'header.php';
    ?>

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center justify-content-center">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
                <div class="col-xl-6 col-lg-8">
                    <h1>Pesan Tiket<span>.</span></h1>
                    <h2>Pemesanan Tiket Bus</h2>

                </div>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <section class="portfolio">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Pesan Tiket</h2>
                    <p><?php echo $rute['nama_rute']; ?></p>


                    <?php
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-info">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
                    }
                    ?>
                </div>

                <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-lg-12 col-md-6 portfolio-item filter-card">
                        <form action="proses_pesan.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_rute" value="<?php echo $id_rute; ?>">
                            <input type="hidden" name="harga" value="<?php echo $rute['harga']; ?>">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="tanggal_pesan">Tanggal Pesan</label>
                                    <input type="date" id="tanggal_pesan" name="tanggal_pesan" value="<?php echo date('Y-m-d'); ?>" required class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_tiket">Jumlah Tiket</label>
                                    <input type="number" id="jumlah_tiket" name="jumlah_tiket" min="1" value="0" readonly required class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="total_harga">Total Harga</label>
                                    <input type="text" id="total_harga" name="total_harga" readonly class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label >Jam Berangkat</label>
                                    <input type="text" readonly class="form-control" value="<?php echo $rute['jam_berangkat']; ?>">
                                </div>
                            </div>
                            <input type="hidden" id="status_pembayaran" name="status_pembayaran" value="Belum Dibayar">
                            <div class="form-group">
                                <label for="kursi">Pilih Kursi</label>
                                <div class="seat-grid">
                                    <?php for ($i = 1; $i <= $rute['jumlah_kursi']; $i++) {
                                        // Memeriksa apakah kursi sudah dipesan
                                        $query_kursi_dipesan = "SELECT COUNT(*) as jumlah FROM kursi_dipesan WHERE id_rute = '$id_rute' AND nomor_kursi = '$i'";
                                        $result_kursi_dipesan = mysqli_query($db, $query_kursi_dipesan);
                                        $row_kursi_dipesan = mysqli_fetch_assoc($result_kursi_dipesan);
                                        $disabled = $row_kursi_dipesan['jumlah'] > 0 ? 'disabled' : '';
                                        $class = $row_kursi_dipesan['jumlah'] > 0 ? 'disabled-seat' : 'available-seat';
                                    ?>
                                        <div class="seat-item">
                                            <button type="button" id="kursi<?php echo $i; ?>" class="seat-button <?php echo $class; ?>" data-value="<?php echo $i; ?>" <?php echo $disabled; ?> onclick="selectSeat(this)"><?php echo $i; ?></button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_rek">Nomor Rekening</label>
                                        <input type="text" id="no_rek" readonly class="form-control" value="Nomor Rekening : 1234567890">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="bukti_pembayaran">Upload Pembayaran</label>
                                    <input type="file" class="form-control" required name="bukti_pembayaran">
                                </div>
                            </div>
                            <div id="selected-seats-container"></div>
                            <button type="submit" class="btn btn-primary">Pesan Tiket</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <script>
        const hargaPerTiket = <?php echo $rute['harga']; ?>;
        let jumlahTiket = 0;

        function selectSeat(button) {
            const seatValue = button.getAttribute('data-value');
            if (button.classList.contains('selected-seat')) {
                button.classList.remove('selected-seat');
                jumlahTiket--;
                document.getElementById('selected-seat-' + seatValue).remove();
            } else {
                button.classList.add('selected-seat');
                jumlahTiket++;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'kursi[]';
                input.id = 'selected-seat-' + seatValue;
                input.value = seatValue;
                document.getElementById('selected-seats-container').appendChild(input);
            }
            document.getElementById('jumlah_tiket').value = jumlahTiket;
            document.getElementById('total_harga').value = jumlahTiket * hargaPerTiket;
        }
    </script>

    <?php include 'footer.php'; ?>