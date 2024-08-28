<?php
global $conn;
session_start();
require_once "config.php";  // Tập tin cấu hình để kết nối cơ sở dữ liệu

// Xử lý khi người dùng gửi form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập tên đăng nhập và mật khẩu.";
    } else {
        // Chuẩn bị câu lệnh SQL
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Liên kết biến với câu lệnh đã chuẩn bị
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            // Thực thi câu lệnh
            if (mysqli_stmt_execute($stmt)) {
                // Lưu kết quả
                mysqli_stmt_store_result($stmt);

                // Kiểm tra nếu username tồn tại
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Liên kết kết quả vào biến
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        // Xác thực mật khẩu
                        if (password_verify($password, $hashed_password)) {
                            // Mật khẩu đúng, bắt đầu phiên làm việc
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Chuyển hướng đến trang chủ
                            header("Location: welcome.php");
                            exit();
                        } else {
                            $error = "Sai mật khẩu.";
                        }
                    }
                } else {
                    $error = "Tên đăng nhập không tồn tại.";
                }
            } else {
                $error = "Đã xảy ra lỗi. Vui lòng thử lại.";
            }

            // Đóng câu lệnh
            mysqli_stmt_close($stmt);
        }
    }

    // Đóng kết nối
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="style.css"> <!-- Tập tin CSS -->
    <style>
        /* CSS cơ bản cho trang đăng nhập */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .container h2 {
            margin: 0 0 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: #ff0000;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Đăng Nhập</h2>
    <?php if (isset($error)) { echo "<p class='error'>" . $error . "</p>"; } ?>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Đăng Nhập">
        </div>
    </form>
</div>
</body>
</html>
