<?php
include 'koneksi/db.php';

// Data user admin
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);

// Cek apakah user admin sudah ada
$query = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // Buat user admin baru
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $query);
    
    echo "<div style='text-align: center; margin-top: 50px;'>";
    echo "<h3 style='color: green;'>User Admin Berhasil Dibuat!</h3>";
    echo "<p>Username: admin</p>";
    echo "<p>Password: admin123</p>";
    echo "<a href='index.php' style='text-decoration: none; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px;'>Login Sekarang</a>";
    echo "</div>";
} else {
    echo "<div style='text-align: center; margin-top: 50px;'>";
    echo "<h3 style='color: blue;'>User Admin Sudah Ada</h3>";
    echo "<p>Username: admin</p>";
    echo "<p>Password: admin123</p>";
    echo "<a href='index.php' style='text-decoration: none; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px;'>Login Sekarang</a>";
    echo "</div>";
}
?> 