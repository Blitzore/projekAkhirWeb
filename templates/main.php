<?php
session_start();

// Timeout 15 menit
$timeout_duration = 900;

// Cek waktu aktivitas terakhir dan apakah sudah kedaluwarsa
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
  // Jika sesi sudah kedaluwarsa, hapus sesi dan arahkan ke login
  session_unset();
  session_destroy();
  header("Location: login.html");
  exit();
}

// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();

// Menghubungkan ke database
$servername = "localhost"; // Ganti jika perlu
$username = "root"; // Ganti dengan username database kamu
$password = ""; // Ganti dengan password database kamu
$dbname = "budgetin"; // Ganti dengan nama database kamu

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika user sudah login
if (!isset($_SESSION['username'])) {
  header("Location: login.html"); // Redirect ke halaman login
  exit();
}

// Bagian kategori dinamis
$username = $_SESSION['username']; // Ambil username dari sesi
$kategori_options = '';

// Query untuk mengambil kategori dari database
$sql = "SELECT name FROM categories WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $kategori_options .= "<option value='{$row['name']}'>{$row['name']}</option>";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Budgetin - Main Page</title>
  <!-- CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Icon Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- favIcon -->
  <link rel="shortcut icon" href="../assets/Gambar/favicon.ico" />
  <!-- My CSS -->
  <link rel="stylesheet" href="../assets/Styles/styleMain.css" />
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

  <!-- Awal Alert -->
  <div class="container mt-4">
    <div id="alertBox" class="alert alert-danger d-none" role="alert"></div>
  </div>
  <!-- Akhir Alert -->

  <!-- Menampilkan Bulan Tahun -->
  <div class="container text-center bg-primary-subtle">
    <div class="row justify-content-around">
      <div class="col-1">
        <button type="button" class="btn fs-4 left-button"><i class="bi bi-arrow-left"></i></button>
      </div>
      <div class="col-10">
        <div id="currentMonthYear" class="mt-2"></div>
      </div>
      <div class="col-1">
        <button type="button" class="btn fs-4 right-button"><i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
  <!-- Akhir Menampilkan Bulan Tahun -->

  <!-- Awal Budget Total -->
  <div class="container-fluid budget mt-5 pt-5 mb-5" id="budgetTotal">
    <div class="row row-border pb-3" id="budgetTotalRow">
      <div class="col-12 col-md-2"></div>
      <div class="col-12 col-md-7 col-lg-5">
        <div class="input-group">
          <span class="input-group-text fw-bold" id="budgetLabel">Budget Total</span>
          <input type="number" class="form-control form-height" name="budget" id="budget" placeholder="Masukkan budget" aria-label="Budget Total" aria-describedby="budgetLabel" required />
        </div>
        <div class="budget-display d-none mt-2"></div> <!-- Tempat untuk menampilkan budget -->
      </div>
      <div class="col-12 col-md-3 col-lg-1 d-flex align-items-center mt-3 mt-md-0">
        <input class="btn btn-success fw-bold w-100 w-md-auto" type="button" value="Save" id="saveBudgetTotal" />
      </div>
    </div>
  </div>
  <!-- Akhir Budget Total -->

  <!-- Awal Budget Category -->
  <div class="container-fluid category mt-4 row-border mb-5" id="budgetCategory">
    <div class="row pb-3" id="budgetCategoryRow">
      <div class="col-12 col-md-2"></div>
      <div class="col-12 col-md-7 col-lg-4">
        <div class="input-group mb-3">
          <span class="input-group-text fw-bold" id="kategoriLabel">Kategori</span>
          <select class="form-select form-height" name="kategori" id="kategori" aria-label="Kategori" aria-describedby="kategoriLabel">
            <option selected>Pilih Kategori</option>
            <?php echo $kategori_options; ?> <!-- Opsi dinamis akan muncul di sini -->
          </select>
          <span class="input-group-text fw-bold" id="kategoriEditLabel">
            <a class="btn btn-sm" href="kategori.php" role="button" id="editKategori"><i class="bi bi-pencil-square"></i></a>
          </span>
        </div>

        <div class="input-group">
          <span class="input-group-text fw-bold" id="budgetKategoriLabel">Budget</span>
          <input type="number" class="form-control form-height" name="budgetKategori" id="budgetKategori" placeholder="Masukkan budget" aria-label="Budget Kategori" aria-describedby="budgetKategoriLabel" required />
        </div>
        <!-- Tempat untuk menampilkan kategori dan budget kategori -->
        <div class="budget-category-display d-none mt-2"></div>
      </div>
      <div class="col-12 col-md-3 col-lg-2 d-flex justify-content-between align-items-center mt-3 mt-md-0">
        <input class="btn btn-success saveBudgetCategory fw-bold w-100 w-md-auto me-2" type="button" value="Save" id="saveBudgetCategory" />
        <button class="btn btn-danger deleteBudgetCategory fw-bold w-100 w-md-auto d-none" type="button" id="deleteBudgetCategory"> Delete</button>
      </div>
    </div>
    <div class="row">
      <div class="col d-flex flex-column justify-content-end align-items-end">
        <a class="btn btn-primary fw-bold btn-lg inputDataBtn" href="inputDataKategori.php" id="inputData">+</a>
      </div>
    </div>
  </div>
  <!-- Akhir Budget Category -->


  <!-- button to add new Budget Category -->
  <div class="container-fluid" id="addBudgetContainer">
    <div class="row">
      <div class="col d-flex flex-column justify-content-end align-items-end">
        <input class="btn btn-dark fw-bold btn-lg" type="button" id="addBudgetCategory" value="+" />
      </div>
    </div>
  </div>
  <!-- Akhir tombol + body -->

  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- My JS -->
  <script src="../assets/JS/scriptMain.js"></script>
</body>

</html>