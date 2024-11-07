<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgetin";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Fetch categories for the logged-in user
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, name FROM categories WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Budgetin - Kategori</title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- favIcon -->
    <link rel="shortcut icon" href="../assets/Gambar/favicon.ico" />
    <!-- My CSS -->
    <link rel="stylesheet" href="../assets/Styles/styleKategori.css" />
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
                        <a class="nav-link active" aria-current="page" href="Main.php">Home</a>
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

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Kategori</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTable">
                        <?php foreach ($categories as $category): ?>
                            <tr data-id="<?= $category['id'] ?>">
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td>
                                    <a class="btn btn-primary editCategory btn-sm" href="#" role="button"><i class="bi bi-pencil-fill"></i> Edit</a>
                                    <a class="btn btn-danger deleteCategory btn-sm" href="#" role="button"><i class="bi bi-trash-fill"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center addKategori">
            <div class="col-lg-1">
                <label for="kategori" class="form-label fw-semibold fs-4">Kategori: </label>
            </div>
            <div class="col-lg-6">
                <input type="text" id="kategori" class="form-control mb-3" aria-describedby="inputkategori" />
            </div>
            <div class="col-lg-1">
                <button id="addCategory" class="btn btn-primary"><i class="bi bi-plus"></i> Add</button>
            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- My JS -->
    <script src="../assets/JS/scriptKategori.js"></script>
</body>

</html>