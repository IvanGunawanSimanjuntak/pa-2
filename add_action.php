<?php
include 'config.php';

$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];

$query = "INSERT INTO buku (judul, pengarang) VALUES ('$judul', '$pengarang')";
if (mysqli_query($conn, $query)) {
    header('Location: index.php');
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>
