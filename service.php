<?php
session_start();
require_once "config.php";

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

// Xử lý thêm dịch vụ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $query = "INSERT INTO services (name, price) VALUES ('$name', '$price')";
    mysqli_query($conn, $query);
}

// Xử lý sửa dịch vụ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $query = "UPDATE services SET name='$name', price='$price' WHERE id=$id";
    mysqli_query($conn, $query);
}

// Xử lý xóa dịch vụ
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM services WHERE id=$id";
    mysqli_query($conn, $query);
}

// Xử lý tìm kiếm
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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
            min-height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        h2 {
            color: #007bff;
            font-size: 18px;
            margin: 15px 0;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            flex-grow: 1;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions form {
            display: inline;
        }
        .actions a, .actions input[type="submit"] {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .actions a {
            background-color: #dc3545;
        }
        .actions a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Manage Vaccination Services</h1>

    <h2>Add New Service</h2>
    <form method="post" action="service.php">
        <label for="name">Service Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
        <input type="submit" name="add_service" value="Add Service">
    </form>

    <h2>Search Services</h2>
    <div class="search-container">
        <form method="get" action="service.php">
            <input type="text" name="search" placeholder="Search by service name" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>
    </div>

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
                <td class="actions">
                    <form method="post" action="service.php">
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
</div>
</body>
</html>
