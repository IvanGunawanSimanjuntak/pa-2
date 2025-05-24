<?php
include 'koneksi.php';
session_start();

// Inisialisasi histori pencarian jika belum ada
if (!isset($_SESSION['search_history'])) {
    $_SESSION['search_history'] = [];
}

// Cek apakah admin sudah login
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini.'); window.location='logout.php';</script>";
    exit();
}

// Jika belum login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Tambahkan pencarian ke histori
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    if (!in_array($search, $_SESSION['search_history'])) {
        array_unshift($_SESSION['search_history'], $search); // Tambah ke awal array
        if (count($_SESSION['search_history']) > 5) { // Batasi hingga 5 item
            array_pop($_SESSION['search_history']);
        }
    }
}

// Hapus item histori jika diminta
if (isset($_GET['clear_history']) && $_GET['clear_history'] == 'all') {
    unset($_SESSION['search_history']);
    $_SESSION['search_history'] = [];
    header("Location: index.php");
    exit();
} elseif (isset($_GET['clear_history']) && is_numeric($_GET['clear_history'])) {
    $index = intval($_GET['clear_history']);
    if (isset($_SESSION['search_history'][$index])) {
        unset($_SESSION['search_history'][$index]);
        $_SESSION['search_history'] = array_values($_SESSION['search_history']); // Reindex array
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Buku Perpustakaan IT Del</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }
    .dashboard-header {
      background-color: #003366;
      color: #fff;
      padding: 20px 0;
      margin-bottom: 20px;
    }
    .dashboard-title {
      font-size: 1.75rem;
    }
    .dashboard-header p {
      font-size: 0.9rem;
    }
    .btn-add {
      background-color: #28a745;
      color: white;
    }
    .btn-add:hover {
      background-color: #218838;
    }
    .btn-logout {
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      font-size: 0.9rem;
      border-radius: 5px;
      transition: all 0.3s;
    }
    .btn-logout:hover {
      background-color: #a71d2a;
      transform: scale(1.05);
    }
    .table-container {
      background-color: white;
      padding: 15px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .book-card {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.3s ease-in-out;
    }
    .book-card:hover {
      background-color: #f8f9fa;
    }
    .action-buttons .btn {
      font-size: 0.875rem;
    }
    .form-search {
      margin-bottom: 20px;
      position: relative;
    }
    .autocomplete-suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid #ddd;
      border-top: none;
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }
    .autocomplete-suggestions div {
      padding: 8px;
      cursor: pointer;
    }
    .autocomplete-suggestions div:hover {
      background-color: #f0f0f0;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #003366;
    }
    .history-list {
      list-style: none;
      padding: 0;
      margin: 10px 0;
      background: #f8f9fa;
      border-radius: 5px;
    }
    .history-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 10px;
      border-bottom: 1px solid #ddd;
    }
    .history-item:last-child {
      border-bottom: none;
    }
    .history-item span {
      font-size: 0.9rem;
    }
    .history-item .delete-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 4px 8px;
      border-radius: 3px;
      font-size: 0.8rem;
      transition: all 0.3s;
    }
    .history-item .delete-btn:hover {
      background-color: #a71d2a;
      transform: scale(1.05);
    }
    .history-item .delete-btn i {
      margin-right: 4px;
    }
    .clear-all {
      text-align: right;
      padding: 8px 10px;
      background: #f8f9fa;
      border-top: 1px solid #ddd;
      border-radius: 0 0 5px 5px;
    }
    .clear-all .clear-all-btn {
      background-color: #ff6b6b;
      color: white;
      padding: 4px 10px;
      border: none;
      border-radius: 3px;
      font-size: 0.8rem;
      transition: all 0.3s;
    }
    .clear-all .clear-all-btn:hover {
      background-color: #e63946;
      transform: scale(1.05);
    }
    @media (max-width: 576px) {
      .dashboard-title {
        font-size: 1.25rem;
      }
      .dashboard-header p {
        font-size: 0.8rem;
      }
      .table-container {
        padding: 10px;
      }
      .table th, .table td {
        font-size: 0.8rem;
        padding: 6px;
      }
      .action-buttons .btn {
        font-size: 0.75rem;
        padding: 4px 6px;
      }
      .form-search .form-control, .form-search .btn {
        font-size: 0.8rem;
      }
      .history-item span {
        font-size: 0.8rem;
      }
      .history-item .delete-btn {
        font-size: 0.75rem;
        padding: 3px 6px;
      }
      .clear-all .clear-all-btn {
        font-size: 0.75rem;
        padding: 3px 8px;
      }
      .btn-add, .btn-logout {
        font-size: 0.8rem;
        padding: 5px 10px;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="dashboard-title">
            <i class="fas fa-book-open me-2"></i>Data Buku Perpustakaan IT Del
          </h1>
          <p class="mb-0">Manajemen koleksi buku perpustakaan kampus</p>
        </div>
        <div class="col-md-6 text-end">
          <a href="add_book.php" class="btn btn-add me-2">
            <i class="fas fa-plus-circle me-1"></i> Tambah Buku Baru
          </a>
          <a href="logout.php" class="btn btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="table-container">
      <form method="GET" class="form-search">
        <div class="input-group mb-3">
          <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari judul, penulis, kategori..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
        </div>
        <div id="autocomplete-suggestions" class="autocomplete-suggestions"></div>
      </form>

      <?php if (!empty($_SESSION['search_history'])): ?>
        <h5>Histori Pencarian</h5>
        <ul class="history-list">
          <?php foreach ($_SESSION['search_history'] as $index => $history): ?>
            <li class="history-item">
              <span><?= htmlspecialchars($history) ?></span>
              <button class="delete-btn" onclick="window.location.href='?clear_history=<?= $index ?>'">
                <i class="fas fa-trash-alt"></i> Hapus
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="clear-all">
          <button class="clear-all-btn" onclick="window.location.href='?clear_history=all'">
            <i class="fas fa-trash-alt"></i> Hapus Semua
          </button>
        </div>
      <?php endif; ?>

      <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT * FROM books";
        if (!empty($search)) {
          $search = $conn->real_escape_string($search);
          $sql .= " WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR category LIKE '%$search%' OR isbn LIKE '%$search%'";
        }
        $result = $conn->query($sql);
      ?>

      <?php if ($result && $result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Judul Buku</th>
              <th>Penulis</th>
              <th>ISBN</th>
              <th>Kategori</th>
              <th>ESP ID</th>
              <th>Ketersediaan</th>
              <th style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="book-card">
              <td><?= $row['id'] ?></td>
              <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
              <td><?= htmlspecialchars($row['author']) ?></td>
              <td><?= htmlspecialchars($row['isbn']) ?></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($row['esp_id']) ?></span></td>
              <td>
                <?php if ($row['available']): ?>
                  <span class="badge bg-success">Tersedia</span>
                <?php else: ?>
                  <span class="badge bg-danger">Dipinjam</span>
                <?php endif; ?>
              </td>
              <td class="action-buttons">
                <div class="d-flex gap-2">
                  <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                  </a>
                  <a href="delete_book.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                    <i class="fas fa-trash-alt me-1"></i> Hapus
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-book fa-3x mb-3 text-muted"></i>
          <h4 class="text-muted">Tidak ditemukan buku untuk pencarian Anda.</h4>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rows = document.querySelectorAll('.book-card');
      rows.forEach(row => {
        row.style.opacity = '1';
        row.style.transform = 'translateY(0)';
      });

      const searchInput = document.getElementById('searchInput');
      const suggestions = document.getElementById('autocomplete-suggestions');

      searchInput.addEventListener('input', function () {
        const query = this.value;
        if (query.length < 2) {
          suggestions.style.display = 'none';
          return;
        }

        fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            suggestions.innerHTML = '';
            if (data.length > 0) {
              data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = item;
                div.addEventListener('click', function () {
                  searchInput.value = item;
                  suggestions.style.display = 'none';
                  searchInput.form.submit();
                });
                suggestions.appendChild(div);
              });
              suggestions.style.display = 'block';
            } else {
              suggestions.style.display = 'none';
            }
          })
          .catch(error => console.error('Error:', error));
      });

      document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
          suggestions.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>