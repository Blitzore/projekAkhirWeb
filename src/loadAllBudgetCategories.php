<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User tidak login']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgetin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$user = $_SESSION['username'];
$monthYear = $_GET['month_year'];

$sql = "SELECT container_id, category_name, amount FROM budget_categories 
        WHERE username = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $monthYear);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();
