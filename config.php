<?php
$servername = "localhost"; // Địa chỉ server MySQL
$username = "root";        // Tên đăng nhập MySQL
$password = "root";            // Mật khẩu MySQL
$dbname = "chude8";      // Tên database

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>