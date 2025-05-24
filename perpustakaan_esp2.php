<?php
include 'koneksi.php';

// Tetapkan esp_id secara statis
$esp_id = 'ESP_2';

// Ambil daftar buku berdasarkan esp_id
$stmt = $conn->prepare("SELECT * FROM books WHERE esp_id = ?");
$stmt->bind_param("s", $esp_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Buku ESP_2 - User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
      padding-top: 20px;
    }
    .container {
      max-width: 960px;
      padding: 0 15px;
    }
    .header {
      background-color: #003366;
      color: white;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }
    .header h2 {
      font-size: 1.5rem;
    }
    .header p {
      font-size: 0.9rem;
    }
    .badge-available {
      background-color: #28a745;
    }
    .badge-unavailable {
      background-color: #dc3545;
    }
    .table th, .table td {
      font-size: 0.9rem;
      padding: 8px;
    }
    .btn {
      font-size: 0.9rem;
    }
    @media (max-width: 576px) {
      .header h2 {
        font-size: 1.25rem;
      }
      .header p {
        font-size: 0.8rem;
      }
      .table th, .table td {
        font-size: 0.8rem;
      }
      .btn {
        font-size: 0.8rem;
        padding: 5px 10px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <h2>📚 Daftar Buku Perpustakaan IT Del - ESP_2</h2>
  </div>

  <div class="mb-4 text-end">
    <a href="halaman utama perpustakaan.html" class="btn btn-secondary">⬅️ Kembali</a>
  </div>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>ISBN</th>
            <th>Kategori</th>
            <th>Ketersediaan</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= htmlspecialchars($row['isbn']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td>
              <span class="badge <?= $row['available'] ? 'badge-available' : 'badge-unavailable' ?>">
                <?= $row['available'] ? 'Tersedia' : 'Dipinjam' ?>
              </span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      Tidak ada buku ditemukan untuk <strong><?= htmlspecialchars($esp_id) ?></strong>.
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>