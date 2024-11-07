<?php
$selectedCategory = isset($_GET['kategori']) ? $_GET['kategori'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Input Data Budget</title>
    <link rel="stylesheet" href="assets/Styles/style.css" />
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- favIcon -->
    <link rel="shortcut icon" href="assets/Gambar/favicon.ico" />
    <!-- My style -->
    <link rel="stylesheet" href="../assets/Styles/styleInput.css">
</head>

<body>

    <!-- Awal Nav -->
    <nav class="navbar navbar-expand-md bg-body-tertiary shadow-sm fixed-top">
        <div class="container-fluid">
            <img src="../assets/Gambar/budgetin.png" alt="Logo budgetin" />
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="main.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Pengeluaran</a>
                    </li>
                </ul>
                <div class="ms-auto">
                    <a class="btn btn-outline-danger btn-sm fw-bold me-5" href="../index.html" role="button"><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Akhir Nav -->

    <!-- ==================================ISI================================= -->
    <section id="inputData">
        <form method="POST" action="php/addData.php">
            <div class="wrapper">
                <!-- Form input data kategori -->
                <label id="kategori" class="menu">Kategori</label>
                <input type="text" class="isi" id="kategoriInput" name="kategori" value="<?php echo htmlspecialchars($selectedCategory); ?>" required readonly />
                <hr />
                <label id="keterangan" class="menu">Keterangan </label>
                <input
                    type="text"
                    class="isi"
                    name="keterangan"
                    placeholder="detail kategori"
                    required />
                <hr />
                <label id="jml-biaya" class="menu">Jumlah</label>
                <input
                    type="number"
                    class="isi"
                    id="harga"
                    name="jumlah"
                    placeholder="jumlah biaya"
                    required />
                <hr />
                <label id="tanggal" class="menu">Tanggal </label>
                <input type="date" name="tanggal" id="date" class="isi" required />

                <div class="button-group">
                    <input type="reset" value="Reset" />
                    <input type="submit" value="Submit" />
                </div>
            </div>
        </form>
    </section>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- My JS -->
    <script src="assets/JS/script.js"></script>
</body>

</html>