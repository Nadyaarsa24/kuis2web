<?php
include 'koneksi/db.php';

if ($conn) {
    echo "<h3>Status Koneksi Database:</h3>";
    echo "MySQL Server: <span style='color: green;'>Terhubung</span><br>";
    echo "Database crud_login: <span style='color: green;'>Terhubung</span><br>";
    
    // Cek apakah tabel users sudah ada
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($check_table) > 0) {
        echo "Tabel users: <span style='color: green;'>Sudah ada</span><br>";
    } else {
        echo "Tabel users: <span style='color: red;'>Belum ada</span><br>";
    }
    
    echo "<br><a href='buat_user.php'>Klik di sini untuk membuat user admin</a><br>";
    echo "<a href='index.php'>Kembali ke halaman login</a>";
} else {
    echo "Status: <span style='color: red;'>Koneksi Gagal</span><br>";
    echo "Error: " . mysqli_connect_error();
}
?> 