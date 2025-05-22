<?php
$host = "localhost";
$user = "root";
$pass = "";

try {
    // Coba koneksi ke MySQL
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        throw new Exception("Koneksi ke MySQL gagal: " . $conn->connect_error);
    }
    
    echo "<div style='font-family: Arial; padding: 20px;'>";
    echo "<h3>✅ Berhasil terhubung ke MySQL Server</h3>";
    
    // Buat database
    $sql = "CREATE DATABASE IF NOT EXISTS crud_login";
    if ($conn->query($sql) === TRUE) {
        echo "<h3>✅ Database crud_login berhasil dibuat</h3>";
        
        // Pilih database
        $conn->select_db("crud_login");
        
        // Buat tabel users
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<h3>✅ Tabel users berhasil dibuat</h3>";
            
            // Cek apakah sudah ada user admin
            $result = $conn->query("SELECT * FROM users WHERE username='admin'");
            if ($result->num_rows == 0) {
                // Buat user admin
                $password = password_hash("admin123", PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password) VALUES ('admin', '$password')";
                if ($conn->query($sql) === TRUE) {
                    echo "<h3>✅ User admin berhasil dibuat</h3>";
                    echo "<p>Username: admin</p>";
                    echo "<p>Password: admin123</p>";
                } else {
                    echo "<h3>❌ Gagal membuat user admin: " . $conn->error . "</h3>";
                }
            } else {
                echo "<h3>ℹ️ User admin sudah ada</h3>";
                echo "<p>Username: admin</p>";
                echo "<p>Password: admin123</p>";
            }
        } else {
            echo "<h3>❌ Gagal membuat tabel users: " . $conn->error . "</h3>";
        }
    } else {
        echo "<h3>❌ Gagal membuat database: " . $conn->error . "</h3>";
    }
    
    echo "<br><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Kembali ke Login</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red; font-family: Arial; padding: 20px;'>";
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<h4>Solusi:</h4>";
    echo "<ol>";
    echo "<li>Pastikan XAMPP/Laragon sudah running</li>";
    echo "<li>Pastikan MySQL sudah berjalan</li>";
    echo "<li>Pastikan username dan password MySQL benar</li>";
    echo "</ol>";
    echo "</div>";
}
?> 