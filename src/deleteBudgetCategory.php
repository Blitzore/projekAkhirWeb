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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username']; // Ambil username dari session
    $month_year = $_POST['month_year'];

    // SQL untuk menghapus data dari budget_categories
    $container_id = $_POST['container_id'] ?? 'budgetCategory'; // Default untuk container awal

    $sql = "DELETE FROM budget_categories 
        WHERE username = ? AND month_year = ? AND container_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $month_year, $container_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Kategori budget berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus kategori budget."]);
    }

    $stmt->close();
    $conn->close();
}
