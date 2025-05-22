<?php
session_start();
include 'koneksi/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

// Ambil data pengguna
$stmt = $conn->prepare("SELECT * FROM data_pengguna WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$user) {
    header("Location: dashboard.php");
    exit();
}

// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $foto_name = $user['foto_profile']; // Gunakan foto yang ada jika tidak ada upload baru

    // Upload foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $foto_name = time() . '_' . $username . '.' . $ext;
            $upload_path = 'uploads/' . $foto_name;
            
            if (move_uploaded_file($foto['tmp_name'], $upload_path)) {
                // Hapus foto lama jika bukan default.jpg
                if ($user['foto_profile'] != 'default.jpg' && file_exists('uploads/' . $user['foto_profile'])) {
                    unlink('uploads/' . $user['foto_profile']);
                }
            } else {
                $_SESSION['error'] = "Gagal upload foto";
                $foto_name = $user['foto_profile'];
            }
        }
    }

    // Cek apakah username sudah digunakan (kecuali oleh user ini sendiri)
    $check = $conn->prepare("SELECT id FROM data_pengguna WHERE username = ? AND id != ?");
    $check->bind_param("si", $username, $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
    } else {
        if (!empty($_POST['password'])) {
            // Update dengan password baru
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE data_pengguna SET nama = ?, username = ?, password = ?, foto_profile = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nama, $username, $password, $foto_name, $id);
        } else {
            // Update tanpa password
            $stmt = $conn->prepare("UPDATE data_pengguna SET nama = ?, username = ?, foto_profile = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nama, $username, $foto_name, $id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data berhasil diupdate!";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal update data: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Edit Akun</a>
            <a href="dashboard.php" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Edit Akun</h4>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error'] ?>
                                <?php unset($_SESSION['error']) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profile</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                                <div class="mt-2">
                                    <img id="preview" src="uploads/<?= htmlspecialchars($user['foto_profile']) ?>" class="preview-image">
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="dashboard.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html> 