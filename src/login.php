<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Sukses login, simpan username ke session
            $_SESSION['username'] = $user['username'];
            echo json_encode(['status' => 'success']);
        } else {
            // Password salah
            echo json_encode(['status' => 'error', 'message' => 'Password salah']);
        }
    } else {
        // Username tidak ditemukan
        echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan']);
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
