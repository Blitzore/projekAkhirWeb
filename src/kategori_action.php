<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgetin";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    exit("Harus login terlebih dahulu.");
}

$username = $_SESSION['username'];

// Cek apakah 'action' ada di $_POST
if (!isset($_POST['action'])) {
    exit("Tidak ada aksi yang diterima.");
}

$action = $_POST['action'];

switch ($action) {
    case 'add':
        $name = trim($_POST['name']);

        // Cek apakah nama kategori sudah ada
        $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ? AND username = ?");
        $stmt->bind_param("ss", $name, $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "duplicate";
            exit();
        }

        // Tambahkan kategori baru
        $stmt = $conn->prepare("INSERT INTO categories (name, username) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $username);
        $stmt->execute();
        $newId = $stmt->insert_id;
        echo "<tr data-id='$newId'><td>" . htmlspecialchars($name) . "</td><td>
              <a class='btn btn-primary editCategory' href='#' role='button'><i class='bi bi-pencil-fill'></i> Edit</a>
              <a class='btn btn-danger deleteCategory' href='#' role='button'><i class='bi bi-trash-fill'></i> Delete</a>
              </td></tr>";
        $stmt->close();
        break;

    case 'edit':
        $id = $_POST['id'];
        $name = trim($_POST['name']);

        // Cek apakah nama kategori sudah ada pada kategori lain
        $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ? AND username = ? AND id != ?");
        $stmt->bind_param("ssi", $name, $username, $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "duplicate";
            exit();
        }

        // Perbarui nama kategori
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ? AND username = ?");
        $stmt->bind_param("sis", $name, $id, $username);
        $stmt->execute();
        $stmt->close();
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ? AND username = ?");
        $stmt->bind_param("is", $id, $username);
        $stmt->execute();
        $stmt->close();
        break;

    default:
        exit("Aksi tidak dikenal.");
}

$conn->close();
