<?php
require_once "config.php";

// Kiểm tra và xử lý tìm kiếm dịch vụ tiêm chủng
$search = '';
$query = "SELECT * FROM services";
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $_GET['search'];
    // Lọc theo tên dịch vụ
    $query .= " WHERE name LIKE '%$search%'";
}

// Thực hiện truy vấn với kết quả đã được lọc
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Services</title>
    <!-- Link đến Bootstrap CSS để làm đẹp giao diện -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center mb-4">Vaccination Services</h1>

    <!-- Form tìm kiếm dịch vụ -->
    <form method="get" action="public_services.php" class="form-inline justify-content-center mb-4">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search by service name"
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Bảng hiển thị danh sách dịch vụ -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Price ($)</th>
            </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3" class="text-center">No services found</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS và các thư viện phụ trợ -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
