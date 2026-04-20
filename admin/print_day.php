<?php
include 'admin_process.php';

$today = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$sql = "SELECT full_name, service_type, appointment_time, status 
        FROM appointments 
        WHERE appointment_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Appointments for <?php echo $today; ?></title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .status-confirmed { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">
    <h2>Appointments for <?php echo $today; ?></h2>
    <table>
        <tr>
            <th>Time</th>
            <th>Patient</th>
            <th>Service</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['service_type']; ?></td>
            <td class="status-<?php echo strtolower($row['status']); ?>">
                <?php echo $row['status']; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
