<?php
include 'koneksi.php';

$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $isbn = $_POST['isbn'];
    $esp_id = $_POST['esp_id'];
    $category = $_POST['category'];
    $available = $_POST['available'];

    $sql = "UPDATE books SET title = '$judul', author = '$penulis', isbn = '$isbn', esp_id = '$esp_id', category = '$category', available = '$available' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Buku - Perpustakaan IT Del</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --accent-color: #e74c3c;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.5rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .form-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 30px;
      margin-bottom: 30px;
    }
    
    .form-title {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #eee;
    }
    
    .form-label {
      font-weight: 500;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }
    
    .form-control {
      border: 1px solid #ddd;
      padding: 0.75rem;
      border-radius: 6px;
      margin-bottom: 1.25rem;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .btn-submit {
      background-color: var(--secondary-color);
      border: none;
      color: white;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 6px;
      transition: all 0.3s;
    }
    
    .btn-submit:hover {
      background-color: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(41, 128, 185, 0.3);
    }
    
    .btn-back {
      background-color: #95a5a6;
      border: none;
      color: white;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 6px;
      transition: all 0.3s;
    }
    
    .btn-back:hover {
      background-color: #7f8c8d;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(127, 140, 141, 0.3);
    }
    
    .input-icon {
      position: relative;
    }
    
    .input-icon i {
      position: absolute;
      left: 15px;
      top: 42px;
      color: #7f8c8d;
    }
    
    .input-icon input, .input-icon select {
      padding-left: 40px;
    }
  </style>
</head>
<body>
  <div class="dashboard-header">
    <div class="container">
      <h1 class="text-center mb-0">
        <i class="fas fa-edit me-2"></i>Edit Data Buku
      </h1>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="form-container">
          <form method="post">
            <div class="mb-4 input-icon">
              <label class="form-label">Judul Buku</label>
              <i class="fas fa-heading"></i>
              <input type="text" name="judul" class="form-control" value="<?= isset($row['title']) ? htmlspecialchars($row['title']) : '' ?>" required>
            </div>
            
            <div class="mb-4 input-icon">
              <label class="form-label">Penulis</label>
              <i class="fas fa-user-edit"></i>
              <input type="text" name="penulis" class="form-control" value="<?= isset($row['author']) ? htmlspecialchars($row['author']) : '' ?>" required>
            </div>
            
            <div class="mb-4 input-icon">
              <label class="form-label">ISBN</label>
              <i class="fas fa-barcode"></i>
              <input type="text" name="isbn" class="form-control" value="<?= isset($row['isbn']) ? htmlspecialchars($row['isbn']) : '' ?>">
            </div>
            
            <div class="mb-4 input-icon">
              <label class="form-label">Kategori</label>
              <i class="fas fa-folder"></i>
              <input type="text" name="category" class="form-control" value="<?= isset($row['category']) ? htmlspecialchars($row['category']) : '' ?>">
            </div>
            
            <div class="mb-4 input-icon">
              <label class="form-label">Ketersediaan</label>
              <i class="fas fa-check"></i>
              <select name="available" class="form-control">
                <option value="1" <?= isset($row['available']) && $row['available'] == 1 ? 'selected' : '' ?>>Tersedia</option>
                <option value="0" <?= isset($row['available']) && $row['available'] == 0 ? 'selected' : '' ?>>Tidak Tersedia</option>
              </select>
            </div>
            
            <div class="mb-4 input-icon">
              <label class="form-label">ESP ID</label>
              <i class="fas fa-microchip"></i>
              <select name="esp_id" class="form-control">
                <option value="">-- Pilih ESP ID --</option>
                <option value="ESP_1" <?= isset($row['esp_id']) && $row['esp_id'] == 'ESP_1' ? 'selected' : '' ?>>ESP_1</option>
                <option value="ESP_2" <?= isset($row['esp_id']) && $row['esp_id'] == 'ESP_2' ? 'selected' : '' ?>>ESP_2</option>
                <option value="ESP_3" <?= isset($row['esp_id']) && $row['esp_id'] == 'ESP_3' ? 'selected' : '' ?>>ESP_3</option>
                <option value="ESP_4" <?= isset($row['esp_id']) && $row['esp_id'] == 'ESP_4' ? 'selected' : '' ?>>ESP_4</option>
                <option value="ESP_5" <?= isset($row['esp_id']) && $row['esp_id'] == 'ESP_5' ? 'selected' : '' ?>>ESP_5</option>
              </select>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
              <a href="index.php" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>Kembali
              </a>
              <button type="submit" class="btn btn-submit">
                <i class="fas fa-save me-2"></i>Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>