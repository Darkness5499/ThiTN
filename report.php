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

// Kết nối cơ sở dữ liệu

// Xử lý tìm kiếm và lọc dữ liệu
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

$query = "SELECT vr.id, vr.fullname, vr.gender, vr.dob, vr.address, vr.phone, vr.email, 
                         u.full_name AS doctor_name, s.name AS service_name, vr.status, vr.appointment_date, s.price
                  FROM vaccination_records vr
                  JOIN users u ON vr.doctor_id = u.id
                  JOIN services s ON vr.service_id = s.id
                  where 1 = 1 ";

if ($doctor_id) {
    $query .= " AND vr.doctor_id = '$doctor_id'";
}

if ($date_from && $date_to) {
    $query .= " AND vr.appointment_date BETWEEN '$date_from' AND '$date_to'";
}

$result = mysqli_query($conn, $query);

// Tính toán tổng số bệnh nhân và tổng doanh thu
$total_patients = mysqli_num_rows($result);
$total_revenue = 0;
$records = [];
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}
foreach ($records as $row){
    $total_revenue +=$row['price'];
}

// Lấy danh sách bác sĩ để lọc
$doctors_query = "SELECT id, full_name FROM users WHERE role = 'doctor'";
$doctors_result = mysqli_query($conn, $doctors_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Report</title>
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
<h1>Vaccination Report</h1>

<!-- Form lọc và tìm kiếm -->
<form method="get" action="report.php">
    <label for="doctor_id">Doctor:</label>
    <select id="doctor_id" name="doctor_id">
        <option value="">-- Select Doctor --</option>
        <?php while ($doctor = mysqli_fetch_assoc($doctors_result)) { ?>
            <option value="<?php echo $doctor['id']; ?>" <?php if ($doctor_id == $doctor['id']) echo 'selected'; ?>>
                <?php echo $doctor['full_name']; ?>
            </option>
        <?php } ?>
    </select><br>

    <label for="date_from">Date From:</label>
    <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>"><br>

    <label for="date_to">Date To:</label>
    <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>"><br>

    <input type="submit" value="Filter">
</form>

<!-- Thống kê tổng quan -->
<h2>Summary</h2>
<p>Total Patients: <?php echo $total_patients; ?></p>
<p>Total Revenue: $<?php echo number_format($total_revenue, 2); ?></p>

<!-- Danh sách chi tiết -->
<h2>Detail Report</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Doctor</th>
        <th>Service</th>
        <th>Appointment Date</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($records as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['fullname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['dob']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo $row['service_name']; ?></td>
            <td><?php echo $row['appointment_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
