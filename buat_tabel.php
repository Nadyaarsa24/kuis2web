<?php
include 'koneksi/db.php';

// Drop tabel jika sudah ada (untuk memastikan struktur tabel benar)
$conn->query("DROP TABLE IF EXISTS data_pengguna");

// Buat tabel data_pengguna
$sql = "CREATE TABLE data_pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    foto_profile VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<div style='font-family: Arial; padding: 20px;'>";
    echo "<h3 style='color: green;'>✅ Tabel data_pengguna berhasil dibuat</h3>";
    
    // Buat folder uploads jika belum ada
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
        echo "<h3 style='color: green;'>✅ Folder uploads berhasil dibuat</h3>";
    }
    
    // Copy default profile picture jika belum ada
    if (!file_exists('uploads/default.jpg')) {
        // Buat gambar default sederhana
        $image = imagecreatetruecolor(200, 200);
        $bg = imagecolorallocate($image, 238, 238, 238);
        $fg = imagecolorallocate($image, 0, 123, 255);
        
        // Isi background
        imagefill($image, 0, 0, $bg);
        
        // Buat lingkaran untuk avatar default
        imagefilledellipse($image, 100, 100, 120, 120, $fg);
        
        // Simpan gambar
        imagejpeg($image, 'uploads/default.jpg');
        imagedestroy($image);
        
        echo "<h3 style='color: green;'>✅ Foto profile default berhasil dibuat</h3>";
    }
    
    echo "<br><a href='dashboard.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ke Dashboard</a>";
    echo "</div>";
} else {
    echo "<div style='font-family: Arial; padding: 20px;'>";
    echo "<h3 style='color: red;'>❌ Error membuat tabel: " . $conn->error . "</h3>";
    echo "</div>";
}
?> 