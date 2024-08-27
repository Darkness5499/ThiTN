<?php
session_start();    // Bắt đầu phiên làm việc

// Xóa tất cả các biến session
session_unset();

// Hủy phiên làm việc hiện tại
session_destroy();

// Chuyển hướng người dùng về trang login
header("Location: login.php");
exit(); // Ngăn mã lệnh tiếp tục chạy sau khi chuyển hướng