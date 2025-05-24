<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "perpustakaan");
if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

$sql = "SELECT id, title, author, category, available, isbn, esp_id FROM books";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Query gagal: " . $conn->error]));
}

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'author' => $row['author'],
        'category' => $row['category'],
        'available' => (int)$row['available'],
        'isbn' => $row['isbn'],
        'esp_id' => $row['esp_id']
    ];
}

echo json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$conn->close();
?>
