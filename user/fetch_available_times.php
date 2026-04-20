<?php
include '../conn.php'; // your DB connection

if (!isset($_GET['date']) || !isset($_GET['service_type'])) {
    echo json_encode([]);
    exit;
}

$selected_date = $_GET['date'];
$service_type = $_GET['service_type'];
$appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;

$service_durations = [
    "Initial Consultation" => 30,
    "Second Opinion" => 30,
    "Post-Treatment Follow-up" => 30,
    "Braces Adjustment Follow-up" => 30,
    "General Check-up" => 60,
    "Routine Cleaning" => 60,
    "Braces Installation" => 60,
    "Braces Adjustment" => 30,
    "Retainer Fitting" => 30,
    "Partial Dentures" => 60,
    "Full Dentures" => 60,
    "Denture Adjustment" => 60,
    "Porcelain Veneers" => 60,
    "Composite Veneers" => 60,
    "Dental Crown" => 60,
    "Dental Bridge" => 60,
    "Simple Extraction" => 45,
    "Surgical Extraction" => 45,
    "Composite Filling" => 45,
    "Amalgam Filling" => 45,
    "Basic Cleaning" => 60,
    "Deep Cleaning" => 60,
    "In-office Whitening" => 60,
    "Take-home Whitening Kit" => 60,
    "Toothache Treatment" => 30,
    "Broken Tooth Repair" => 30,
    "Special Request" => 30
];

$duration = $service_durations[$service_type] ?? 60;

// Get booked appointments excluding this appointment_id (for reschedule)
$query = "SELECT appointment_time, service_type FROM appointments WHERE appointment_date = ?";
$params = [$selected_date];
$types = "s";

if ($appointment_id > 0) {
    $query .= " AND id != ?";
    $types .= "i";
    $params[] = $appointment_id;
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$booked_intervals = [];
while ($row = $result->fetch_assoc()) {
    $start_timestamp = strtotime($selected_date . ' ' . substr($row['appointment_time'], 0, 5));
    $booked_duration = $service_durations[$row['service_type']] ?? 60;
    $end_timestamp = strtotime("+$booked_duration minutes", $start_timestamp);
    $booked_intervals[] = ['start' => $start_timestamp, 'end' => $end_timestamp];
}

$work_start = strtotime("$selected_date 09:00");
$work_end = strtotime("$selected_date 18:00");
$lunch_start = strtotime("$selected_date 12:00");
$lunch_end = strtotime("$selected_date 13:00");

$available_slots = [];

for ($slot_start = $work_start; $slot_start + ($duration * 60) <= $work_end; $slot_start += $duration * 60) {
    // Skip lunch break
    if ($slot_start < $lunch_end && ($slot_start + $duration * 60) > $lunch_start) {
        continue;
    }

    $slot_end = $slot_start + ($duration * 60);

    // Check overlap
    $overlaps = false;
    foreach ($booked_intervals as $interval) {
        if ($slot_start < $interval['end'] && $slot_end > $interval['start']) {
            $overlaps = true;
            break;
        }
    }

    if (!$overlaps) {
        $available_slots[] = date("H:i", $slot_start);
    }
}

echo json_encode($available_slots);
?>