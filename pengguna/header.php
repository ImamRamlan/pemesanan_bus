<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo $title; ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Favicons -->
    <link href="ass/img/api/logo-api.png" rel="icon">
    <link href="ass/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="ass/vendor/aos/aos.css" rel="stylesheet">
    <link href="ass/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="ass/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="ass/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="ass/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="ass/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="ass/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="ass/css/style.css" rel="stylesheet">
    <style>
    .seat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 kolom */
        gap: 10px; /* Ruang antara tombol */
    }

    .seat-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seat-button {
        padding: 5px; /* Padding yang lebih kecil */
        width: calc(100% - 10px); /* Lebar dikurangi dengan total padding */
        cursor: pointer;
        border: none;
        border-radius: 5px;
    }

    .disabled-seat {
        background-color: red;
        color: white;
        cursor: not-allowed;
    }

    .available-seat {
        background-color: green;
        color: white;
    }

    .selected-seat {
        background-color: blue;
        color: white;
    }
</style>

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center justify-content-lg-between">
            <h1 class="logo me-auto me-lg-0"><a href="index.html">Bintang Prima<span>.</span></a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li>
                        <a class="nav-link scrollto" href="dashboard.php">Beranda</a>
                    </li>

                    <li class="">
                        <a class="nav-link scrollto" href="riwayat_pemesanan.php">Riwayat Pemesanan</a>
                    </li>
                    <li class="">
                        <a class="nav-link scrollto" href="cek_rute.php">Cek Rute</a>
                    </li>
                    <li class="">
                        <a class="nav-link scrollto" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
            <a href="#" class="get-started-btn scrollto"> <?php echo $_SESSION['nama']; ?></a>
        </div>

    </header><!-- End Header -->