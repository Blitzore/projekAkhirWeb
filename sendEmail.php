<?php
// Menyertakan file autoload dari Composer
require 'vendor/autoload.php';

// Menggunakan namespace PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $mail->isSMTP();                                    // Set PHPMailer untuk menggunakan SMTP
        $mail->Host = 'smtp.gmail.com';                     // Alamat server SMTP (untuk Gmail)
        $mail->SMTPAuth = true;                             // Aktifkan autentikasi SMTP
        $mail->Username = 'ahmadhabib2b@gmail.com';         // Gunakan dengan alamat Gmail kamu
        $mail->Password = 'yyxfufwfuvaabtly';               // Gunakan dengan Sandi Aplikasi dari Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Aktifkan enkripsi TLS
        $mail->Port = 587;                                  // Port SMTP untuk TLS

        // Penerima dan pengirim email
        $mail->setFrom($email, $nama);  // Alamat email pengirim
        $mail->addAddress('ahmadhabib2b@gmail.com', 'Ahmad'); // Gunakan dengan alamat email penerima

        // Konten email
        $mail->isHTML(true);                                // Set email sebagai format HTML
        $mail->Subject = 'Saran pengembangan Budgetin';          // Subjek email
        $mail->Body    = "Nama: $nama<br>Email: $email<br>Pesan: $pesan"; // Isi email dalam format HTML
        $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan"; // Isi email (Plain Text)

        // Kirim email
        $mail->send();
        echo 'Email berhasil dikirim';
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Tidak ada data yang dikirim.";
}
