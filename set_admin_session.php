<?php
session_start();

// Optional: You can add a simple token or referrer check here
$_SESSION['admin_access'] = true;
$_SESSION['admin_expire'] = time() + (30 * 60); // 30 mins

echo json_encode(["status" => "success"]);
?>
