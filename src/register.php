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

// Nonaktifkan mode exception untuk mysqli
mysqli_report(MYSQLI_REPORT_OFF); // Menonaktifkan laporan error dalam bentuk exception

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

    // Menyimpan data pengguna ke database
    $sqlUser = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("ss", $username, $hashedPassword);

    // Eksekusi statement dan periksa kesalahan
    if ($stmtUser->execute()) {
        echo '<div class="alert alert-success">Registrasi berhasil!</div>';

        // Tambahkan kategori default untuk pengguna baru
        $sqlCategory = "
            INSERT INTO categories (name, is_default, username)
            SELECT name, TRUE, ? FROM categories WHERE username IS NULL AND is_default = TRUE
        ";
        $stmtCategory = $conn->prepare($sqlCategory);
        $stmtCategory->bind_param("s", $username);
        $stmtCategory->execute();
        $stmtCategory->close(); // Tutup $stmtCategory setelah digunakan
    } else {
        // Memeriksa apakah kesalahan disebabkan oleh duplikasi username
        if ($stmtUser->errno == 1062) { // Error code untuk duplikasi
            echo '<div class="alert alert-danger">Username sudah digunakan, silakan pilih username lain.</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmtUser->error . '</div>';
        }
    }

    // Tutup statement dan koneksi
    $stmtUser->close(); // Tutup $stmtUser setelah digunakan
    $conn->close();
} else {
    echo '<div class="alert alert-danger">Tidak ada data yang dikirim.</div>';
}
