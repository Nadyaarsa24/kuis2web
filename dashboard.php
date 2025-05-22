<?php
session_start();
include 'koneksi/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user'];
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$foto_profile = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default.jpg';
$nama_lengkap = isset($_SESSION['nama']) ? $_SESSION['nama'] : $username;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .nav-profile-img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Data Pengguna</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-users"></i> Data Pengguna
                        </a>
                    </li>
                    <?php if ($is_admin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah.php">
                            <i class="fas fa-user-plus"></i> Tambah Akun
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3 text-white">
                        <img src="uploads/<?= htmlspecialchars($foto_profile) ?>" class="nav-profile-img">
                        <?= htmlspecialchars($nama_lengkap) ?>
                    </span>
                    <a href="logout.php" class="btn btn-light">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Daftar Pengguna</h4>
                <?php if ($is_admin): ?>
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Akun
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <?php if ($is_admin): ?>
                                <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM data_pengguna ORDER BY id DESC";
                            $result = $conn->query($query);
                            
                            if ($result->num_rows > 0) {
                                $no = 1;
                                while($row = $result->fetch_assoc()) {
                                    $foto = 'uploads/' . $row['foto_profile'];
                                    if (!file_exists($foto)) {
                                        $foto = 'uploads/default.jpg';
                                    }
                                    echo "<tr>";
                                    echo "<td>".$no++."</td>";
                                    echo "<td><img src='".$foto."' class='profile-img'></td>";
                                    echo "<td>".htmlspecialchars($row['nama'])."</td>";
                                    echo "<td>".htmlspecialchars($row['username'])."</td>";
                                    if ($is_admin) {
                                        echo "<td>
                                                <a href='edit.php?id=".$row['id']."' class='btn btn-sm btn-warning' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <a href='hapus.php?id=".$row['id']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")' title='Hapus'>
                                                    <i class='fas fa-trash'></i>
                                                </a>
                                              </td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='".($is_admin ? 5 : 4)."' class='text-center'>Belum ada data pengguna</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 