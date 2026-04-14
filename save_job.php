<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'worker') {
    header("Location: login.php");
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = (int)$_POST['job_id'];
    $user_id = $_SESSION['user_id'];

    // Check if already saved
    $check = $conn->prepare("SELECT id FROM saved_jobs WHERE job_id = ? AND worker_id = ?");
    $check->bind_param("ii", $job_id, $user_id);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {
        // Remove from saved
        $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE job_id = ? AND worker_id = ?");
        $stmt->bind_param("ii", $job_id, $user_id);
        $stmt->execute();
        echo json_encode(['status' => 'removed']);
    } else {
        // Add to saved
        $stmt = $conn->prepare("INSERT INTO saved_jobs (job_id, worker_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $job_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'saved']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
} else {
    echo json_encode(['status' => 'invalid']);
}
