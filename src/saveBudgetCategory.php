<?php
session_start();

// Cek jika user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User tidak login']);
    exit();
}

// Koneksi ke database
$servername = "localhost"; // Ganti jika perlu
$username = "root"; // Ganti dengan username database kamu
$password = ""; // Ganti dengan password database kamu
$dbname = "budgetin"; // Ganti dengan nama database kamu

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil username dari session
$user = $_SESSION['username'];

// Ambil data dari POST
$budget = $_POST['budget'];
$monthYear = $_POST['month_year'];
$category = $_POST['category'];

// Periksa apakah ada kategori yang sama di bulan dan tahun yang sama
$container_id = $_POST['container_id'] ?? 'budgetCategory'; // Default untuk container awal

$sql = "SELECT id FROM budget_categories 
        WHERE username = ? AND month_year = ? AND container_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user, $monthYear, $container_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika ada, update category_name dan amount
    $row = $result->fetch_assoc();
    $id = $row['id'];

    $update_sql = "UPDATE budget_categories SET amount = ?, category_name = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $budget, $category, $id);
    $update_stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Data budget kategori berhasil diperbarui']);
} else {
    // Jika tidak ada, masukkan data baru
    $insert_sql = "INSERT INTO budget_categories (username, category_name, amount, month_year, container_id) 
               VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssdss", $user, $category, $budget, $monthYear, $container_id);
    $insert_stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Data budget kategori berhasil disimpan']);
}

$stmt->close();
$conn->close();
