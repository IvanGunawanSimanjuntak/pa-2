<?php
include 'config.php';

$id = $_POST['id'];
$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];

$query = "UPDATE buku SET judul='$judul', pengarang='$pengarang' WHERE id=$id";
if (mysqli_query($conn, $query)) {
    header('Location: index.php');
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>
