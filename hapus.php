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

// Ambil data pengguna untuk mendapatkan foto
$stmt = $conn->prepare("SELECT foto_profile FROM data_pengguna WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Hapus foto jika bukan default.jpg
    if ($user['foto_profile'] != 'default.jpg' && file_exists('uploads/' . $user['foto_profile'])) {
        unlink('uploads/' . $user['foto_profile']);
    }

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM data_pengguna WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "Data tidak ditemukan";
}

header("Location: dashboard.php");
exit();
?> 