<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Konfigurasi database (pindahkan ke file terpisah untuk produksi)
$host = "localhost";
$db_user = "root";
$db_pass = ""; // Ganti dengan password aman untuk produksi
$db_name = "perpustakaan";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

$esp_id = isset($_GET['esp_id']) ? $conn->real_escape_string($_GET['esp_id']) : '';

if (empty($esp_id)) {
    die(json_encode(["error" => "Parameter esp_id wajib diisi"]));
}

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT id, isbn, title, author, category, available, esp_id FROM books WHERE esp_id = ?");
$stmt->bind_param("s", $esp_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die(json_encode(["error" => "Query gagal: " . $conn->error]));
}

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'id' => $row['id'],
        'isbn' => $row['isbn'],
        'title' => $row['title'],
        'author' => $row['author'],
        'category' => $row['category'],
        'available' => (int)$row['available'],
        'esp_id' => $row['esp_id']
    ];
}

echo json_encode($books, JSON_UNESCAPED_UNICODE);
$stmt->close();
$conn->close();
?>