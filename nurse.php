<?php
// Start session
global $conn;
session_start();
require_once "config.php";

// Kiểm tra quyền truy cập, chỉ cho phép 'nurse' truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'nurse') {
    header('Location: login.php');
    exit();
}

// Biến thông báo
$success_message = "";

// Kiểm tra nếu có yêu cầu chuyển trạng thái thành 'vaccinated'
if (isset($_GET['vaccinate_id'])) {
    $vaccinate_id = $_GET['vaccinate_id'];

    // Cập nhật trạng thái thành 'vaccinated'
    $update_query = "UPDATE vaccination_records SET status = 'vaccinated' WHERE id = $vaccinate_id AND status = 'paid'";
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Status updated to 'Vaccinated' successfully!";
    } else {
        echo "Error: " . $update_query . "<br>" . mysqli_error($conn);
    }
}

// Lấy danh sách tất cả các bản ghi
$records_query = "SELECT vr.id, vr.fullname, vr.gender, vr.dob, vr.address, vr.phone, vr.email, u.full_name AS doctor_name, s.name AS service_name, vr.status 
                  FROM vaccination_records vr
                  JOIN users u ON vr.doctor_id = u.id
                  JOIN services s ON vr.service_id = s.id
                  ORDER BY vr.status, vr.appointment_date";
$records_result = mysqli_query($conn, $records_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse - Manage Vaccinations</title>
</head>
<body>
<h1>Manage Vaccinations</h1>

<!-- Hiển thị thông báo thành công -->
<?php if ($success_message != "") { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php } ?>

<table border="1">
    <tr>
        <th>Full Name</th>
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Doctor</th>
        <th>Service</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($records_result)) { ?>
        <tr>
            <td><?php echo $row['fullname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['dob']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo $row['service_name']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <?php if ($row['status'] == 'paid') { ?>
                    <a href="nurse.php?vaccinate_id=<?php echo $row['id']; ?>">Mark as Vaccinated</a>
                <?php } elseif ($row['status'] == 'vaccinated') { ?>
                    <button disabled>Vaccinated</button>
                <?php } else { ?>
                    <button disabled><?php echo ucfirst($row['status']); ?></button>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
