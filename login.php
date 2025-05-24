<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // role: admin / user
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!-- HTML Login -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-image: url('old_books.jpg'); /* Ganti 'old_books.jpg' dengan path gambar kamu */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh; /* Memastikan body memenuhi tinggi layar */
      display: flex;
      align-items: center; /* Vertikal tengah */
      justify-content: center; /* Horizontal tengah */
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.9); /* Transparansi agar background terlihat */
      color: #333;
      border-radius: 12px;
      padding: 30px;
      max-width: 400px; /* Lebar card lebih kecil untuk estetika */
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    h4 {
      font-weight: bold;
      color: #333; /* Warna teks kontras dengan background */
    }

    .form-label {
      color: #666;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 10px;
      font-size: 1rem;
      border-radius: 8px;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .alert-danger {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="card">
    <h4 class="mb-4 text-center">Login Admin Perpustakaan</h4>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>