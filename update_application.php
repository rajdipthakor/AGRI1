<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['app_id'])) {
    header("Location: dashboard.php");
    exit();
}

$app_id = (int)$_POST['app_id'];
$status = trim($_POST['status']);
$valid_statuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];

if (!in_array($status, $valid_statuses)) {
    header("Location: dashboard.php");
    exit();
}

// Verify ownership
$check = $conn->prepare("SELECT a.id FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.id = ? AND j.farmer_id = ?");
$check->bind_param("ii", $app_id, $_SESSION['user_id']);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $app_id);
    $stmt->execute();
}

header("Location: view_application.php?id=" . $app_id);
exit();
