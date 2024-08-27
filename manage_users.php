<?php
// Start session
global $conn;
session_start();
require_once "config.php";

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    // Nếu không phải admin, chuyển hướng về trang đăng nhập hoặc trang chủ
    header('Location: login.php');
    exit();
}

// Xử lý thêm người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $query = "INSERT INTO users (username, password, role, full_name, phone, email) VALUES ('$username', '$password', '$role', '$full_name', '$phone', '$email')";
    mysqli_query($conn, $query);
}

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $query);
}

// Lấy danh sách người dùng
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Manage Users</h1>

<!-- Form thêm người dùng -->
<h2>Add New User</h2>
<form method="post" action="manage_users.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="user">User</option>
        <option value="employee">Employee</option>
        <option value="doctor">Doctor</option>
        <option value="cashier">Cashier</option>
        <option value="nurse">Nurse</option>
        <option value="manager">Manager</option>
    </select><br>

    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name"><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone"><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br>

    <input type="submit" name="add_user" value="Add User">
</form>

<!-- Danh sách người dùng -->
<h2>List of Users</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Full Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="manage_users.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
