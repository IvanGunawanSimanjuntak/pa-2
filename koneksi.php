<?php
// koneksi.php
$servername = "localhost";
$username = "root"; // Ganti sesuai username MySQL Anda
$password = "";     // Kosongkan jika tidak ada password (default XAMPP)
$database = "perpustakaan";  // Pastikan nama database sesuai

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
