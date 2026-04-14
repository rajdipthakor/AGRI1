<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Manage Applications';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Update application status
if ($action == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $app_id = (int)$_GET['id'];
    $status = trim($_GET['status']);
    $valid_statuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
    
    if (in_array($status, $valid_statuses)) {
        $conn->query("UPDATE applications SET status = '$status' WHERE id = $app_id");
    }
    header("Location: manage_applications.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

    <div class="container-fluid py-5">
        <h1 class="mb-4">Manage Applications</h1>

        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">All Applications</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Worker Name</th>
                                <th>Job Title</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $apps = $conn->query("SELECT a.id, a.status, a.applied_at, u.full_name, u.email, u.phone, j.job_title
                                                FROM applications a
                                                JOIN users u ON a.worker_id = u.id
                                                JOIN jobs j ON a.job_id = j.id
                                                ORDER BY a.applied_at DESC");
                            while ($app = $apps->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($app['email']); ?></td>
                                <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $app['status'] == 'pending' ? 'warning' : 
                                             ($app['status'] == 'shortlisted' ? 'info' : 
                                              ($app['status'] == 'hired' ? 'success' : 'danger'));
                                    ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="?action=update_status&id=<?php echo $app['id']; ?>&status=shortlisted" 
                                           class="btn btn-info">Shortlist</a>
                                        <a href="?action=update_status&id=<?php echo $app['id']; ?>&status=hired" 
                                           class="btn btn-success">Hire</a>
                                        <a href="?action=update_status&id=<?php echo $app['id']; ?>&status=rejected" 
                                           class="btn btn-danger">Reject</a>
                                    </div>
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
