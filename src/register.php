<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgetin";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengecek apakah form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validasi data
    if (strlen($username) < 6 || strlen($username) > 12) {
        echo '<div class="alert alert-danger">Username harus antara 6 dan 12 karakter.</div>';
        exit; // Hentikan eksekusi script jika ada error
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data ke database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Registrasi berhasil!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-danger">Tidak ada data yang dikirim.</div>';
}
