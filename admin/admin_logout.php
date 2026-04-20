<?php
session_start();

// Unset only admin-related session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

// Optional: Redirect to index or admin login
header("Location: ../index");
exit();
