<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$job_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify ownership and delete
$check = $conn->prepare("DELETE FROM jobs WHERE id = ? AND farmer_id = ?");
$check->bind_param("ii", $job_id, $user_id);
$check->execute();

header("Location: dashboard.php?tab=my-jobs&status=deleted");
exit();
