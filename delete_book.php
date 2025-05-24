<?php
include 'koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit();
}

$id = intval($_GET['id']); // Sanitasi input

// Persiapan pesan notifikasi
session_start();

try {
    // Mulai transaksi untuk memastikan konsistensi
    $conn->begin_transaction();

    // Gunakan prepared statement untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Buku berhasil dihapus!'
            ];

            // Ambil ID maksimum yang tersisa di tabel
            $stmt_max = $conn->query("SELECT MAX(id) as max_id FROM books");
            $row = $stmt_max->fetch_assoc();
            $max_id = $row['max_id'];

            // Jika tabel kosong, reset AUTO_INCREMENT ke 1
            if ($max_id === null) {
                $new_auto_increment = 1;
            } else {
                $new_auto_increment = $max_id + 1;
            }

            // Reset AUTO_INCREMENT
            $conn->query("ALTER TABLE books AUTO_INCREMENT = $new_auto_increment");

            // Verifikasi bahwa AUTO_INCREMENT telah diperbarui
            $stmt_verify = $conn->query("SHOW TABLE STATUS LIKE 'books'");
            $table_status = $stmt_verify->fetch_assoc();
            $current_auto_increment = $table_status['Auto_increment'];

            if ($current_auto_increment != $new_auto_increment) {
                throw new Exception("Gagal mengatur ulang AUTO_INCREMENT. Nilai saat ini: $current_auto_increment, diharapkan: $new_auto_increment");
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'warning',
                'text' => 'Tidak ada buku yang dihapus (ID tidak ditemukan)'
            ];
        }
    } else {
        throw new Exception("Error saat menjalankan query penghapusan");
    }

    // Commit transaksi
    $conn->commit();
    
    $stmt->close();
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollback();
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Error: ' . $e->getMessage()
    ];
} finally {
    $conn->close();
    header("Location: index.php");
    exit();
}
?>