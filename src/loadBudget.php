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
    echo json_encode(['success' => false, 'message' => 'Koneksi gagal']);
    exit();
}

// Ambil username dari session
$user = $_SESSION['username'];

// Pastikan month_year disertakan dalam parameter GET
if (!isset($_GET['month_year'])) {
    echo json_encode(['success' => false, 'message' => 'Parameter bulan dan tahun tidak ada']);
    exit();
}

$monthYear = $_GET['month_year'];

// Query untuk cek apakah ada data budget untuk bulan dan tahun ini
$sql = "SELECT amount FROM budgets WHERE username = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $monthYear);
$stmt->execute();
$result = $stmt->get_result();

// Mengembalikan hasil berdasarkan ada atau tidaknya data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'amount' => $row['amount']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Budget tidak ditemukan untuk bulan ini']);
}

$stmt->close();
$conn->close();
