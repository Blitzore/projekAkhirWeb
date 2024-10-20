<?php
// Menyertakan file autoload dari Composer
require __DIR__ . '/../vendor/autoload.php';

// Menggunakan namespace PHPMailer dan Dotenv
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Memuat file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Mengecek apakah form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $pesan = htmlspecialchars($_POST['pesan']);

    // Membuat objek PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi server SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        // Penerima dan pengirim email
        $mail->setFrom($_ENV['EMAIL_FROM'], $nama);  // Alamat email pengirim
        $mail->addAddress($_ENV['EMAIL_FROM'], 'Ahmad'); // Gunakan dengan alamat email penerima

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Saran pengembangan Budgetin';
        $mail->Body    = "Nama: $nama<br>Email: $email<br>Pesan: $pesan";
        $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan";

        // Kirim email
        $mail->send();
        echo 'Email berhasil dikirim';
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Tidak ada data yang dikirim.";
}
