<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Manage Jobs';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle job deletion
if ($action == 'delete' && isset($_GET['id'])) {
    $job_id = (int)$_GET['id'];
    $conn->query("DELETE FROM jobs WHERE id = $job_id");
    header("Location: manage_jobs.php");
    exit();
}

// Handle job activation/deactivation
if ($action == 'toggle' && isset($_GET['id'])) {
    $job_id = (int)$_GET['id'];
    $conn->query("UPDATE jobs SET is_active = NOT is_active WHERE id = $job_id");
    header("Location: manage_jobs.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

    <div class="container-fluid py-5">
        <h1 class="mb-4">Manage Jobs</h1>

        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">All Jobs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Job Title</th>
                                <th>Farmer</th>
                                <th>Location</th>
                                <th>Salary Range</th>
                                <th>Applications</th>
                                <th>Posted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $jobs = $conn->query("SELECT j.*, u.full_name, 
                                                 (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as app_count
                                                 FROM jobs j JOIN users u ON j.farmer_id = u.id 
                                                 ORDER BY j.posted_at DESC");
                            while ($job = $jobs->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($job['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td>₹<?php echo number_format($job['salary_min']); ?> - ₹<?php echo number_format($job['salary_max']); ?></td>
                                <td><span class="badge bg-info"><?php echo $job['app_count']; ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($job['posted_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $job['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $job['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?action=toggle&id=<?php echo $job['id']; ?>" 
                                       class="btn btn-sm btn-<?php echo $job['is_active'] ? 'warning' : 'success'; ?>">
                                        <?php echo $job['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $job['id']; ?>" 
                                       class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>
