<?php
include 'includes/db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: jobs.php");
    exit();
}

$job_id = (int)$_GET['id'];
$query = "SELECT j.*, u.id as farmer_id, u.full_name, u.email, u.phone FROM jobs j 
         JOIN users u ON j.farmer_id = u.id 
         WHERE j.id = ? AND j.is_active = TRUE";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: jobs.php");
    exit();
}

$job = $result->fetch_assoc();
$page_title = $job['job_title'];

// Check if already applied
$has_applied = false;
if (isset($_SESSION['user_id'])) {
    $check = $conn->prepare("SELECT id FROM applications WHERE job_id = ? AND worker_id = ?");
    $check->bind_param("ii", $job_id, $_SESSION['user_id']);
    $check->execute();
    $has_applied = ($check->get_result()->num_rows > 0);
}
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h1>
                        
                        <div class="mb-4">
                            <span class="badge bg-info me-2"><?php echo htmlspecialchars($job['category']); ?></span>
                            <span class="badge bg-warning"><?php echo ucfirst(htmlspecialchars($job['job_type'])); ?></span>
                        </div>

                        <hr>

                        <h5>Job Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-map-marker-alt"></i> Location:</strong><br>
                                <?php echo htmlspecialchars($job['location']); ?>, 
                                <?php echo htmlspecialchars($job['city']); ?>, 
                                <?php echo htmlspecialchars($job['state']); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-dollar-sign"></i> Salary Range:</strong><br>
                                ₹<?php echo number_format($job['salary_min']); ?> - ₹<?php echo number_format($job['salary_max']); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-briefcase"></i> Experience Required:</strong><br>
                                <?php echo htmlspecialchars($job['experience_required'] ?? 'Not specified'); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-calendar"></i> Application Deadline:</strong><br>
                                <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                            </div>
                        </div>

                        <hr>

                        <h5>Description</h5>
                        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

                        <hr>

                        <h5>About the Employer</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><?php echo htmlspecialchars($job['full_name']); ?></h6>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($job['contact_email']); ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($job['contact_phone']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h5 class="card-title">Ready to apply?</h5>
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="login.php" class="btn btn-success w-100 mb-2">Login to Apply</a>
                            <a href="register.php" class="btn btn-outline-success w-100">Create Account</a>
                        <?php elseif ($_SESSION['role'] == 'worker'): ?>
                            <?php if ($has_applied): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-check-circle"></i> You have already applied for this job.
                                </div>
                            <?php else: ?>
                                <a href="apply_job.php?id=<?php echo $job_id; ?>" class="btn btn-success w-100">
                                    <i class="fas fa-paper-plane"></i> Apply Now
                                </a>
                            <?php endif; ?>
                        <?php elseif ($_SESSION['role'] == 'farmer'): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This is your job posting.
                            </div>
                        <?php endif; ?>

                        <hr>

                        <div class="d-grid">
                            <button class="btn btn-outline-secondary" onclick="saveJob(<?php echo $job['id']; ?>)">
                                <i class="fas fa-heart"></i> Save Job
                            </button>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Posted <?php echo date('M d, Y', strtotime($job['posted_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>

<script>
function saveJob(jobId) {
    const btn = event.target.closest('button');
    if (btn.classList.contains('saved')) {
        btn.classList.remove('saved');
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
        btn.innerHTML = '<i class="fas fa-heart"></i> Save Job';
    } else {
        btn.classList.add('saved');
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        btn.innerHTML = '<i class="fas fa-heart"></i> Saved';
    }
}
</script>
