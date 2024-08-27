<?php
// Start session
global $conn;
session_start();
require_once "config.php";

// Kiểm tra quyền truy cập (tùy chỉnh nếu cần)
 if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
     header('Location: login.php');
     exit();
 }



// Xử lý thêm dịch vụ tiêm chủng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $query = "INSERT INTO services (name, price) VALUES ('$name', '$price')";
    mysqli_query($conn, $query);
}

// Xử lý sửa dịch vụ tiêm chủng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $query = "UPDATE services SET name='$name', price='$price' WHERE id=$id";
    mysqli_query($conn, $query);
}

// Xử lý xóa dịch vụ tiêm chủng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM services WHERE id=$id";
    mysqli_query($conn, $query);
}

// Xử lý tìm kiếm dịch vụ tiêm chủng
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM services WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM services";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vaccination Services</title>
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
<h1>Manage Vaccination Services</h1>

<!-- Form thêm dịch vụ -->
<h2>Add New Service</h2>
<form method="post" action="service.php">
    <label for="name">Service Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br>

    <input type="submit" name="add_service" value="Add Service">
</form>

<!-- Form tìm kiếm dịch vụ -->
<h2>Search Services</h2>
<form method="get" action="service.php">
    <input type="text" name="search" placeholder="Search by service name" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<!-- Danh sách dịch vụ tiêm chủng -->
<h2>List of Services</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <form method="post" action="service.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                    <input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01" required>
                    <input type="submit" name="edit_service" value="Edit">
                </form>
                <a href="service.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
