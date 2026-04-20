<?php
require_once '../conn.php';

$key = "a3f1d9e5c7b2a1f8d4e9b3c6a7f2d5c8e1b4f7d3c2e6a9b5f8d7c4e2a1b3c6";

if (!isset($_GET['report_id'])) {
    die("Missing parameters");
}

$report_id = intval($_GET['report_id']);

$sql = "SELECT mr.file, p.patient_id, p.full_name
        FROM medical_reports mr
        JOIN patients p ON p.patient_id = mr.patient_id
        WHERE mr.report_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Report not found.");
}

$row = $result->fetch_assoc();
$filename = $row['file'];
$foldername = $row['patient_id'] . ' - ' . $row['full_name'];
$path = __DIR__ . '/../uploads/' . $foldername . '/' . $filename;

if (!file_exists($path)) {
    die("File not found.");
}

$cipher = "AES-256-CBC";
$encData = file_get_contents($path);
$iv_length = openssl_cipher_iv_length($cipher);
$iv = substr($encData, 0, $iv_length);
$encrypted = substr($encData, $iv_length);
$decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);

if ($decrypted === false) {
    die("Decryption failed.");
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="decrypted_' . basename($filename) . '"');
header('Content-Length: ' . strlen($decrypted));
echo $decrypted;
exit;
?>
