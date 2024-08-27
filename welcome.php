<?php
session_start();
require_once "config.php";

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];  // Lấy vai trò người dùng từ session
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Tiêm Chủng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Chào mừng bạn đến với hệ thống tiêm chủng</h1>

    <!-- Menu điều hướng -->
    <nav>
        <ul>
            <?php if ($user_role == 'manager') { ?>
                <li><a href="manage_users.php">Quản lý người dùng</a></li>
                <li><a href="service.php">Quản Lý Dịch Vụ Tiêm Chủng</a></li>
                <li><a href="report.php">Báo Cáo & Thống Kê</a></li>
            <?php } elseif ($user_role == 'employee') { ?>
                <li><a href="register.php">Đăng Ký Tiêm Chủng</a></li>
            <?php } elseif ($user_role == 'doctor') { ?>
                <li><a href="appointment.php">Khám Sàng Lọc</a></li>
            <?php } elseif ($user_role == 'cashier') { ?>
                <li><a href="cashier.php">Thanh toán hoá đơn</a></li>
            <?php } elseif ($user_role == 'nurse') { ?>
                <li><a href="nurse.php">Ghi Nhận Tiêm Chủng</a></li>
            <?php } elseif ($user_role == 'user') { ?>
                <li><a href="register.php">Đăng Ký Tiêm Chủng</a></li>
                <li><a href="history.php">Lịch Sử Tiêm Chủng</a></li>
            <?php } ?>
            <li><a href="logout.php">Đăng Xuất</a></li>
        </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="main-content">
        <p>Chọn chức năng từ menu trên để tiếp tục.</p>
    </div>
</div>
</body>
</html>
