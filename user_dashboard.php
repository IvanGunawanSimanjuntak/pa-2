<?php
include 'koneksi.php';

// Ambil esp_id dari URL (default ESP_1)
$esp_id = isset($_GET['esp_id']) ? $_GET['esp_id'] : 'ESP_1';

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tambahkan meta viewport -->
  <title>Daftar Buku - User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
      padding-top: 20px; /* Kurangi padding untuk layar kecil */
    }
    .container {
      max-width: 960px;
      padding: 0 15px; /* Pastikan ada padding di sisi */
    }
    .header {
      background-color: #003366;
      color: white;
      padding: 15px; /* Kurangi padding untuk layar kecil */
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }
    .header h2 {
      font-size: 1.5rem; /* Sesuaikan ukuran font untuk layar kecil */
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
      font-size: 0.9rem; /* Kurangi ukuran font pada tabel untuk layar kecil */
      padding: 8px; /* Kurangi padding pada sel tabel */
    }
    .form-label, .form-select, .btn {
      font-size: 0.9rem; /* Sesuaikan ukuran font untuk elemen form */
    }
    @media (max-width: 576px) {
      .header h2 {
        font-size: 1.25rem; /* Ukuran font lebih kecil di layar mobile */
      }
      .header p {
        font-size: 0.8rem;
      }
      .table th, .table td {
        font-size: 0.8rem; /* Ukuran font lebih kecil untuk tabel di mobile */
      }
      .form-label {
        font-size: 0.8rem;
      }
      .form-select, .btn {
        font-size: 0.8rem;
        padding: 5px 10px; /* Kurangi padding untuk tombol dan select */
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <h2>📚 Daftar Buku Perpustakaan IT Del</h2>
    <p class="mb-0">Hanya untuk melihat, tanpa login</p>
  </div>

  <div class="mb-4 text-end">
    <form method="GET" class="d-inline-block">
      <label for="esp_id" class="form-label me-2">Lihat berdasarkan ESP ID:</label>
      <select name="esp_id" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
        <?php
          for ($i = 1; $i <= 5; $i++) {
            $selected = ($esp_id == "ESP_$i") ? "selected" : "";
            echo "<option value='ESP_$i' $selected>ESP_$i</option>";
          }
        ?>
      </select>
    </form>
    <a href="halaman utama perpustakaan.html" class="btn btn-secondary ms-2">⬅️ Kembali</a>
  </div>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive"> <!-- Tambahkan kelas table-responsive -->
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