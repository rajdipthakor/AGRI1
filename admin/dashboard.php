<?php
include '../includes/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Admin Dashboard';

// Get statistics
$stats = $conn->query("SELECT 
    (SELECT COUNT(*) FROM users WHERE role = 'farmer') as total_farmers,
    (SELECT COUNT(*) FROM users WHERE role = 'worker') as total_workers,
    (SELECT COUNT(*) FROM jobs WHERE is_active = TRUE) as active_jobs,
    (SELECT COUNT(*) FROM applications) as total_applications,
    (SELECT COUNT(*) FROM applications WHERE status = 'hired') as successful_hires")->fetch_assoc();
?>

<?php include '../includes/header.php'; ?>

    <div class="container-fluid py-5">
        <h1 class="mb-4">Admin Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-primary"><?php echo $stats['total_farmers']; ?></h3>
                        <p class="text-muted">Farmers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-info"><?php echo $stats['total_workers']; ?></h3>
                        <p class="text-muted">Workers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-success"><?php echo $stats['active_jobs']; ?></h3>
                        <p class="text-muted">Active Jobs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-warning"><?php echo $stats['total_applications']; ?></h3>
                        <p class="text-muted">Applications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-danger"><?php echo $stats['successful_hires']; ?></h3>
                        <p class="text-muted">Hires</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Tabs -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="list-group">
                    <a href="manage_users.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a href="manage_jobs.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-briefcase"></i> Manage Jobs
                    </a>
                    <a href="manage_applications.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt"></i> Applications
                    </a>
                    <a href="manage_messages.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope"></i> Messages
                    </a>
                    <a href="reports.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card border-0 shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recent_users = $conn->query("SELECT id, full_name, email, role, created_at, is_active FROM users 
                                                                  WHERE role != 'admin' ORDER BY created_at DESC LIMIT 10");
                                    while ($user = $recent_users->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><span class="badge bg-<?php echo $user['role'] == 'farmer' ? 'primary' : 'info'; ?>">
                                            <?php echo ucfirst($user['role']); ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>
