<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$app_id = (int)$_GET['id'];

// Verify ownership (farmer)
$app = $conn->prepare("SELECT a.*, j.farmer_id FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.id = ?");
$app->bind_param("i", $app_id);
$app->execute();
$app_data = $app->get_result()->fetch_assoc();

if (!$app_data || $app_data['farmer_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

$page_title = 'View Application';
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-file-alt"></i> Application Details</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        // Get full details
                        $details = $conn->prepare("SELECT a.*, u.full_name, u.email, u.phone, j.job_title 
                                                  FROM applications a 
                                                  JOIN users u ON a.worker_id = u.id 
                                                  JOIN jobs j ON a.job_id = j.id 
                                                  WHERE a.id = ?");
                        $details->bind_param("i", $app_id);
                        $details->execute();
                        $app = $details->get_result()->fetch_assoc();
                        ?>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5>Job Title</h5>
                                <p class="h6"><?php echo htmlspecialchars($app['job_title']); ?></p>

                                <h5 class="mt-4">Worker Name</h5>
                                <p class="h6"><?php echo htmlspecialchars($app['full_name']); ?></p>

                                <h5 class="mt-4">Contact Information</h5>
                                <p>
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($app['email']); ?><br>
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($app['phone']); ?>
                                </p>

                                <h5 class="mt-4">Application Status</h5>
                                <span class="badge bg-<?php 
                                    echo $app['status'] == 'pending' ? 'warning' : 
                                         ($app['status'] == 'shortlisted' ? 'info' : 
                                          ($app['status'] == 'hired' ? 'success' : 'danger'));
                                ?>" style="font-size: 1rem;">
                                    <?php echo ucfirst($app['status']); ?>
                                </span>

                                <h5 class="mt-4">Cover Letter</h5>
                                <div class="card bg-light p-3">
                                    <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                                </div>

                                <h5 class="mt-4">Applied Date</h5>
                                <p><?php echo date('M d, Y H:i', strtotime($app['applied_at'])); ?></p>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Update Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <?php if ($app['status'] != 'shortlisted'): ?>
                                                <form method="POST" action="update_application.php">
                                                    <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
                                                    <input type="hidden" name="status" value="shortlisted">
                                                    <button type="submit" class="btn btn-info btn-sm">Shortlist</button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if ($app['status'] != 'hired'): ?>
                                                <form method="POST" action="update_application.php">
                                                    <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
                                                    <input type="hidden" name="status" value="hired">
                                                    <button type="submit" class="btn btn-success btn-sm">Mark as Hired</button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if ($app['status'] != 'rejected'): ?>
                                                <form method="POST" action="update_application.php">
                                                    <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Reject</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($app['resume'])): ?>
                                    <div class="card border-0 shadow-sm mt-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Resume</h6>
                                        </div>
                                        <div class="card-body">
                                            <a href="uploads/<?php echo htmlspecialchars($app['resume']); ?>" 
                                               class="btn btn-sm btn-primary w-100" download>
                                                <i class="fas fa-download"></i> Download Resume
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="dashboard.php?tab=applications" class="btn btn-outline-secondary mt-3">Back to Applications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
