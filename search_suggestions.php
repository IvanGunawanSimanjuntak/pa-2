<?php
include 'koneksi.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$suggestions = [];

if (!empty($query)) {
    $sql = "SELECT DISTINCT title, author, category, isbn 
            FROM books 
            WHERE title LIKE ? OR author LIKE ? OR category LIKE ? OR isbn LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%$query%";
    $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['title'];
        $suggestions[] = $row['author'];
        $suggestions[] = $row['category'];
        $suggestions[] = $row['isbn'];
    }
    $suggestions = array_unique($suggestions); // Hapus duplikat
    $suggestions = array_slice($suggestions, 0, 5); // Batasi hingga 5 saran
}

echo json_encode($suggestions);
$stmt->close();
$conn->close();
?>