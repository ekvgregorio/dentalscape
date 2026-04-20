<?php
require '../conn.php';
require_once 'admin_process.php';


// Daily Engagement
$active_users_query = "SELECT 
    SUM(CASE WHEN last_login >= NOW() - INTERVAL 1 DAY THEN 1 ELSE 0 END) AS active,
    SUM(CASE WHEN last_login < NOW() - INTERVAL 1 DAY OR last_login IS NULL THEN 1 ELSE 0 END) AS inactive
FROM (
    SELECT user_id, MAX(login_datetime) AS last_login
    FROM login_history
    GROUP BY user_id
) AS last_logins";

$active_users_result = $conn->query($active_users_query);
$active_data = $active_users_result->fetch_assoc();

// Verified Users
$verified_users_query = "SELECT 
    SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) AS verified,
    SUM(CASE WHEN is_verified = 0 THEN 1 ELSE 0 END) AS unverified
FROM users";
$verified_users_result = $conn->query($verified_users_query);
$verified_data = $verified_users_result->fetch_assoc();

$verified_data['verified'] = isset($verified_data['verified']) ? (int)$verified_data['verified'] : 0;
$verified_data['unverified'] = isset($verified_data['unverified']) ? (int)$verified_data['unverified'] : 0;


//User Activity Over Time
$login_query = "SELECT 
    DATE(login_datetime) as login_date, 
    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_count,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count
FROM login_history 
GROUP BY DATE(login_datetime)
ORDER BY login_date ASC;
";
$login_result = $conn->query($login_query);
$login_dates = [];
$success_counts = [];
$failed_counts = [];
while ($row = $login_result->fetch_assoc()) {
    $login_dates[] = $row['login_date'];
    $success_counts[] = $row['success_count'];
    $failed_counts[] = $row['failed_count'];
}

//Appointment Status Count
$status_query = "
    SELECT 
        MONTH(appointment_date) AS month,
        status,
        COUNT(*) as count 
    FROM appointments 
    GROUP BY MONTH(appointment_date), status
    ORDER BY month
";
$status_result = $conn->query($status_query);
$month_names = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
$statuses = ['Pending', 'Confirmed', 'Canceled', 'Done'];
$datasetMap = [];

foreach ($statuses as $status) {
    $datasetMap[$status] = array_fill(1, 12, 0);
}
while ($row = $status_result->fetch_assoc()) {
    $month = (int)$row['month'];
    $status = $row['status'];
    $count = (int)$row['count'];

    if (isset($datasetMap[$status])) {
        $datasetMap[$status][$month] = $count;
    }
}

$months = array_values($month_names);


//Daily Appointments Trend
$daily_query = "SELECT appointment_date, COUNT(*) as count 
                FROM appointments 
                WHERE appointment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                GROUP BY appointment_date";
$daily_result = $conn->query($daily_query);
$daily_dates = $daily_counts = [];
while ($row = $daily_result->fetch_assoc()) {
    $daily_dates[] = $row['appointment_date'];
    $daily_counts[] = $row['count'];
}

// Weekly
$weekly_query = "SELECT YEARWEEK(appointment_date, 1) AS week, COUNT(*) as count 
                 FROM appointments 
                 WHERE appointment_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) 
                 GROUP BY week";
$weekly_result = $conn->query($weekly_query);
$weekly_labels = $weekly_counts = [];
while ($row = $weekly_result->fetch_assoc()) {
    $weekly_labels[] = "Week " . substr($row['week'], 4); // extract week number
    $weekly_counts[] = $row['count'];
}

// Monthly
$monthly_query = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') AS month, COUNT(*) as count 
                  FROM appointments 
                  WHERE appointment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                  GROUP BY month";
$monthly_result = $conn->query($monthly_query);
$monthly_labels = $monthly_counts = [];
while ($row = $monthly_result->fetch_assoc()) {
    $monthly_labels[] = $row['month'];
    $monthly_counts[] = $row['count'];
}


$user_growth_query = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS period,
        COUNT(*) as total_users
    FROM users
    GROUP BY period
    ORDER BY period ASC
";
$user_growth_result = $conn->query($user_growth_query);

// Prepare data for chart
$growth_labels = [];
$growth_counts = [];

while ($row = $user_growth_result->fetch_assoc()) {
    $growth_labels[] = $row['period'];
    $growth_counts[] = (int)$row['total_users'];
}




//Age-based
$age_service_query = "SELECT 
    purpose_of_appointment,
    CASE 
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) BETWEEN 0 AND 12 THEN '0-12'
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) BETWEEN 13 AND 19 THEN '13-19'
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) BETWEEN 20 AND 30 THEN '20-30'
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) BETWEEN 31 AND 45 THEN '31-45'
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) BETWEEN 46 AND 59 THEN '46-59'
        WHEN FLOOR(DATEDIFF(CURRENT_DATE, birthdate) / 365) >= 60 THEN '60+'
        ELSE 'Unknown'
    END as age_range,
    COUNT(*) as count 
    FROM appointments 
    WHERE birthdate IS NOT NULL
    GROUP BY purpose_of_appointment, age_range
    ORDER BY purpose_of_appointment, 
        CASE age_range
            WHEN '0-12' THEN 1
            WHEN '13-19' THEN 2
            WHEN '20-30' THEN 3
            WHEN '31-45' THEN 4
            WHEN '46-59' THEN 5
            WHEN '60+' THEN 6
            ELSE 7
        END";
    
$age_service_result = $conn->query($age_service_query);

$services = [];
$age_ranges = ['0-12', '13-19', '20-30', '31-45', '46-59', '60+'];
$data_by_service_age = [];

while ($row = $age_service_result->fetch_assoc()) {
    $service = $row['purpose_of_appointment'];
    $age_range = $row['age_range'];
    $count = $row['count'];
    
    if (!in_array($service, $services)) {
        $services[] = $service;
    }
    
    $data_by_service_age[$service][$age_range] = $count;
}

$colors = [
    '#9641e0', //Consulation
    '#e03fd0', //Free Tuli
    '#5965b5', //Laboratory
    '#007bff', //Physical Therapy
    '#e665a7', // Crimson
    '#512f87', // Vaccination
    '#9932CC', // Dark Orchid
    '#FF4500', // Orange Red
    '#2E8B57', // Sea Green
    '#4682B4'  // Steel Blue
];

$datasets = [];
foreach ($services as $index => $service) {
    $dataset = [
        'label' => $service,
        'backgroundColor' => $colors[$index % count($colors)],
        'data' => []
    ];
    
    foreach ($age_ranges as $age_range) {
        $dataset['data'][] = isset($data_by_service_age[$service][$age_range]) ? $data_by_service_age[$service][$age_range] : 0;
    }
    
    $datasets[] = $dataset;
}

$age_labels = array_map(function($range) {
    if ($range == '60+') {
        return 'Age 60+';
    }
    return 'Age ' . $range;
}, $age_ranges);


$gender_query = "SELECT gender, COUNT(*) as count FROM appointments GROUP BY gender";
$gender_result = $conn->query($gender_query);
$gender_labels = [];
$gender_data = [];
while ($row = $gender_result->fetch_assoc()) {
    $gender_labels[] = $row['gender'];
    $gender_data[] = $row['count'];
}

//Appointments by Time Slot
$time_query = "SELECT appointment_time, COUNT(*) as count FROM appointments GROUP BY appointment_time";
$time_result = $conn->query($time_query);
$time_labels = [];
$time_data = [];
while ($row = $time_result->fetch_assoc()) {
    $time_labels[] = $row['appointment_time'];
    $time_data[] = $row['count'];
}

//Monthly Appointment Summary
$monthly_query = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as count FROM appointments GROUP BY month";
$monthly_result = $conn->query($monthly_query);
$monthly_labels = [];
$monthly_data = [];
while ($row = $monthly_result->fetch_assoc()) {
    $monthly_labels[] = $row['month'];
    $monthly_data[] = $row['count'];
}



//==================================== Dental Records ================================

//===== Patient Age ======
function fetchAgeGroups($conn, $encryption_key) {
    $stmt = $conn->prepare("SELECT dob FROM patients");
    $stmt->execute();
    $result = $stmt->get_result();

    $ageGroups = [
        'Under 18' => 0,
        '18-30' => 0,
        '31-50' => 0,
        '51+' => 0
    ];

    while ($row = $result->fetch_assoc()) {
        $dob = decryptData($row['dob'], $encryption_key); // Decrypt date of birth
        $age = date_diff(date_create($dob), date_create('today'))->y;

        if ($age < 18) {
            $ageGroups['Under 18']++;
        } elseif ($age <= 30) {
            $ageGroups['18-30']++;
        } elseif ($age <= 50) {
            $ageGroups['31-50']++;
        } else {
            $ageGroups['51+']++;
        }
    }

    return $ageGroups;
}
$ageGroups = fetchAgeGroups($conn, $encryption_key);
$totalUsers = array_sum($ageGroups);
$summaryParts = [];
foreach ($ageGroups as $group => $count) {
    $percent = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
    $summaryParts[] = "$count in $group ({$percent}%)";
}
$summary = "Summary: From a total of {$totalUsers} patients, " . 
           implode(', ', $summaryParts) . 
           ". The patient age distribution shows the current demographic trend.";


// ===== Gender Distribution =====
function fetchGenderDistribution($conn, $encryption_key) {
    $stmt = $conn->prepare("SELECT gender FROM patients");
    $stmt->execute();
    $result = $stmt->get_result();

    $counts = ['Male' => 0, 'Female' => 0, 'Other' => 0];

    while ($row = $result->fetch_assoc()) {
        $gender = decryptData($row['gender'], $encryption_key); // decrypt gender if stored encrypted
        $gender = strtolower(trim($gender));

        if ($gender === 'male') {
            $counts['Male']++;
        } elseif ($gender === 'female') {
            $counts['Female']++;
        } else {
            $counts['Other']++;
        }
    }
    return $counts;
}


//===== Most Common Treatments =====
function fetchMostCommonTreatments($conn, $encryption_key) {
    $stmt = $conn->prepare("SELECT treatment FROM dental_records");
    $stmt->execute();
    $result = $stmt->get_result();

    $treatmentCounts = [];

    while ($row = $result->fetch_assoc()) {
        $treatment = decryptData($row['treatment'], $encryption_key);
        if ($treatment) {
            if (!isset($treatmentCounts[$treatment])) {
                $treatmentCounts[$treatment] = 0;
            }
            $treatmentCounts[$treatment]++;
        }
    }

    arsort($treatmentCounts); // Sort descending
    return $treatmentCounts;
}

//===== Treatment Frequency by Age Group =====
function fetchTreatmentByAgeGroup($conn, $encryption_key) {
    $stmt = $conn->prepare("
        SELECT p.dob, d.treatment
        FROM patients p
        JOIN dental_records d ON p.patient_id = d.patient_id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $ageGroups = [
        'Under 18' => [],
        '18-30' => [],
        '31-50' => [],
        '51+' => []
    ];

    while ($row = $result->fetch_assoc()) {
        $dob = decryptData($row['dob'], $encryption_key);
        $treatment = decryptData($row['treatment'], $encryption_key);
        if (!$dob || !$treatment) continue;

        $age = date_diff(date_create($dob), date_create('today'))->y;

        if ($age < 18) {
            $ageGroups['Under 18'][] = $treatment;
        } elseif ($age <= 30) {
            $ageGroups['18-30'][] = $treatment;
        } elseif ($age <= 50) {
            $ageGroups['31-50'][] = $treatment;
        } else {
            $ageGroups['51+'][] = $treatment;
        }
    }

    // Count treatments per age group
    $frequency = [];
    foreach ($ageGroups as $group => $treatments) {
        $frequency[$group] = array_count_values($treatments);
    }

    return $frequency;
}

//===== Disease Occurrence =====
function fetchDiseaseOccurrence($conn, $encryption_key) {
    $stmt = $conn->prepare("SELECT diagnosis FROM dental_records");
    $stmt->execute();
    $result = $stmt->get_result();

    $diseaseCounts = [];

    while ($row = $result->fetch_assoc()) {
        $diagnosis = decryptData($row['diagnosis'], $encryption_key);
        if ($diagnosis) {
            if (!isset($diseaseCounts[$diagnosis])) {
                $diseaseCounts[$diagnosis] = 0;
            }
            $diseaseCounts[$diagnosis]++;
        }
    }

    arsort($diseaseCounts);
    return $diseaseCounts;
}


?>