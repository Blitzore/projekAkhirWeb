<?php
session_start();

// Cek jika user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User tidak login']);
    exit();
}

// Koneksi ke database
$servername = "localhost"; // Ganti jika perlu
$username_db = "root"; // Ganti dengan username database kamu
$password_db = ""; // Ganti dengan password database kamu
$dbname = "budgetin"; // Ganti dengan nama database kamu

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Koneksi gagal: " . $conn->connect_error]));
}

// Ambil data dari permintaan AJAX
$user = $_SESSION['username'];
$budget = $_POST['budget'];
$monthYear = $_POST['month_year']; // Pastikan nama parameter konsisten

// Cek apakah budget sudah ada untuk bulan ini
$sql = "SELECT * FROM budgets WHERE username = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $monthYear);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika sudah ada, update budget
    $sql = "UPDATE budgets SET amount = ? WHERE username = ? AND month_year = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $budget, $user, $monthYear);
} else {
    // Jika belum ada, insert budget baru
    $sql = "INSERT INTO budgets (username, amount, month_year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $user, $budget, $monthYear);
}

// Eksekusi pernyataan
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Budget berhasil disimpan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saat menyimpan budget: ' . $stmt->error]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
