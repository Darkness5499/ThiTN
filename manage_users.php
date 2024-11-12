<?php
// Start session
global $conn;
session_start();
require_once "config.php";

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
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
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, full_name, phone, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $hashed_password, $role, $full_name, $phone, $email);
    $stmt->execute();
    $stmt->close();
    //    $query = "INSERT INTO users (username, password, role, full_name, phone, email) VALUES ('$username', '$hashed_password', '$role', '$full_name', '$phone', '$email')";
    //    mysqli_query($conn, $query);
}

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $query);
}

// Xử lý cập nhật người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Câu lệnh cập nhật người dùng
    $stmt = $conn->prepare("UPDATE users SET username=?, role=?, full_name=?, phone=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $username, $role, $full_name, $phone, $email, $id);
    $stmt->execute();
    $stmt->close();

    // Reload the page after update
    header("Location: manage_users.php");
    exit();
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px 0;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .delete-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-link:hover {
            color: #c82333;
        }

    </style>
</head>
<body>
<div class="container">
    <h1>Manage Users</h1>

    <!-- Form thêm người dùng -->
    <h2>Add New User</h2>
    <form method="post" action="manage_users.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="user">User</option>
            <option value="employee">Employee</option>
            <option value="doctor">Doctor</option>
            <option value="cashier">Cashier</option>
            <option value="nurse">Nurse</option>
            <option value="manager">Manager</option>
        </select>

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name">

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

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
                <form method="post" action="manage_users.php">
                    <td><?php echo $row['id']; ?></td>
                    <td><input type="text" name="username" value="<?php echo $row['username']; ?>"></td>
                    <td>
                        <select name="role">
                            <option value="user" <?php echo ($row['role'] == ' ') ? 'selected' : ''; ?>>User</option>
                            <option value="employee" <?php echo ($row['role'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                            <option value="doctor" <?php echo ($row['role'] == 'doctor') ? 'selected' : ''; ?>>Doctor</option>
                            <option value="cashier" <?php echo ($row['role'] == 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                            <option value="nurse" <?php echo ($row['role'] == 'nurse') ? 'selected' : ''; ?>>Nurse</option>
                            <option value="manager" <?php echo ($row['role'] == 'manager') ? 'selected' : ''; ?>>Manager</option>
                        </select>
                    </td>
                    <td><input type="text" name="full_name" value="<?php echo $row['full_name']; ?>"></td>
                    <td><input type="text" name="phone" value="<?php echo $row['phone']; ?>"></td>
                    <td><input type="email" name="email" value="<?php echo $row['email']; ?>"></td>
                    <td>
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="update_user" class="update-btn">Update</button>
                        <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="delete-link"
                           onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </form>
            </tr>
        <?php } ?>


        </tbody>
    </table>
</div>
</body>
</html>
