<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "crud_login";

try {
    // Koneksi ke MySQL
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        throw new Exception("Koneksi ke MySQL gagal: " . $conn->connect_error);
    }
    
    // Buat database jika belum ada
    $conn->query("CREATE DATABASE IF NOT EXISTS $db");
    
    // Pilih database
    $conn->select_db($db);
    
    // Buat tabel users jika belum ada
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    $conn->query($sql);
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>