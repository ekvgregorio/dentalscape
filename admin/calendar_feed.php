<?php
require '../conn.php';

// Fetch all confirmed appointments
$sql = "SELECT id, user_id, full_name, service_type, appointment_date, appointment_time 
        FROM appointments 
        WHERE status IN ('confirmed', 'pending', 'Done')
        ORDER BY appointment_date ASC";

$result = $conn->query($sql);

// Start ICS content
$icsContent = "BEGIN:VCALENDAR\r\n";
$icsContent .= "VERSION:2.0\r\n";
$icsContent .= "CALSCALE:GREGORIAN\r\n";
$icsContent .= "METHOD:PUBLISH\r\n";

while ($row = $result->fetch_assoc()) {
    $uid = $row['id'] . "@dentalscape.com";
    $title = $row['full_name'] . "-" . $row['service_type'];
    $desc  = "Dental appointment for: " .  $row['full_name'];
    $loc   = "DentalScape Clinic"; 

    // Combine date + time
    $startTime = strtotime($row['appointment_date'] . " " . $row['appointment_time']);
    $endTime   = $startTime + 3600; // 1 hour duration (adjust if you have an end time column)

    $start = gmdate("Ymd\THis\Z", $startTime);
    $end   = gmdate("Ymd\THis\Z", $endTime);
    $now   = gmdate("Ymd\THis\Z");

    $icsContent .= "BEGIN:VEVENT\r\n";
    $icsContent .= "UID:$uid\r\n";
    $icsContent .= "DTSTAMP:$now\r\n";
    $icsContent .= "DTSTART:$start\r\n";
    $icsContent .= "DTEND:$end\r\n";
    $icsContent .= "SUMMARY:" . addslashes($title) . "\r\n";
    $icsContent .= "DESCRIPTION:" . addslashes($desc) . "\r\n";
    $icsContent .= "LOCATION:" . addslashes($loc) . "\r\n";
    $icsContent .= "END:VEVENT\r\n";
}

$icsContent .= "END:VCALENDAR\r\n";

// Set headers (so users can subscribe directly)
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

// Output ICS
echo $icsContent;
exit;
?>
