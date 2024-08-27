<?php
// Start session
global $conn;
session_start();
require_once "config.php";

// Kiểm tra quyền truy cập, chỉ cho phép 'employee' truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header('Location: login.php');
    exit();
}


// Biến thông báo
$success_message = "";

// Kiểm tra khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $doctor_id = $_POST['doctor_id'];
    $service_id = $_POST['service_id'];

    // Insert dữ liệu vào bảng vaccination_records
    $sql = "INSERT INTO vaccination_records (fullname, gender, dob, address, phone, email, doctor_id, service_id)
            VALUES ('$fullname', '$gender', '$dob', '$address', '$phone', '$email', $doctor_id, $service_id)";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Lấy danh sách bác sĩ và dịch vụ từ cơ sở dữ liệu để hiển thị trong form
$doctors_query = "SELECT id, full_name FROM users WHERE role = 'doctor'";
$doctors_result = mysqli_query($conn, $doctors_query);

$services_query = "SELECT id, name FROM services";
$services_result = mysqli_query($conn, $services_query);

// Lấy danh sách các đăng ký thành công
$registrations_query = "SELECT vr.fullname, vr.gender, vr.dob, vr.address, vr.phone, vr.email,vr.status, u.full_name AS doctor_name, s.price, s.name AS service_name 
                        FROM vaccination_records vr
                        JOIN users u ON vr.doctor_id = u.id
                        JOIN services s ON vr.service_id = s.id";
$registrations_result = mysqli_query($conn, $registrations_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Vaccination</title>
</head>
<body>
<h1>Register Vaccination</h1>

<!-- Hiển thị thông báo thành công -->
<?php if ($success_message != "") { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php } ?>

<form method="post" action="register.php">
    <label for="fullname">Full Name:</label><br>
    <input type="text" id="fullname" name="fullname" required><br>

    <label for="gender">Gender:</label><br>
    <select id="gender" name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select><br>

    <label for="dob">Date of Birth:</label><br>
    <input type="date" id="dob" name="dob" required><br>

    <label for="address">Address:</label><br>
    <input type="text" id="address" name="address" required><br>

    <label for="phone">Phone:</label><br>
    <input type="text" id="phone" name="phone" required><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>

    <label for="doctor_id">Select Doctor:</label><br>
    <select id="doctor_id" name="doctor_id" required>
        <?php while ($doctor = mysqli_fetch_assoc($doctors_result)) { ?>
            <option value="<?php echo $doctor['id']; ?>">
                <?php echo $doctor['full_name']; ?>
            </option>
        <?php } ?>
    </select><br>

    <label for="service_id">Select Service:</label><br>
    <select id="service_id" name="service_id" required>
        <?php while ($service = mysqli_fetch_assoc($services_result)) { ?>
            <option value="<?php echo $service['id']; ?>">
                <?php echo $service['name']; ?>
            </option>
        <?php } ?>
    </select><br>

    <input type="submit" value="Register">
</form>

<h2>Successful Registrations</h2>
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
        <th>Price</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($registrations_result)) { ?>
        <tr>
            <td><?php echo $row['fullname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['dob']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo $row['service_name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
