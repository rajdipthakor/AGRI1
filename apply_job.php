<?php
include 'includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: jobs.php");
    exit();
}

$job_id = (int)$_GET['id'];
$worker_id = $_SESSION['user_id'];

// Check if job exists
$check = $conn->prepare("SELECT id FROM jobs WHERE id = ? AND is_active = TRUE");
$check->bind_param("i", $job_id);
$check->execute();
if ($check->get_result()->num_rows == 0) {
    header("Location: jobs.php");
    exit();
}

// Check if already applied
$applied_check = $conn->prepare("SELECT id FROM applications WHERE job_id = ? AND worker_id = ?");
$applied_check->bind_param("ii", $job_id, $worker_id);
$applied_check->execute();
if ($applied_check->get_result()->num_rows > 0) {
    header("Location: jobs.php?error=already-applied");
    exit();
}

$page_title = 'Apply for Job';
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cover_letter = trim($_POST['cover_letter'] ?? '');

    if (empty($cover_letter)) {
        $error_msg = "Please write a cover letter!";
    } else {
        // Handle resume upload
        $resume_file = '';
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $allowed = array('pdf', 'doc', 'docx');
            $filename = $_FILES['resume']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (!in_array(strtolower($filetype), $allowed)) {
                $error_msg = "Invalid file type. Only PDF and Word documents allowed!";
            } else {
                $new_filename = 'resume_' . $worker_id . '_' . time() . '.' . $filetype;
                if (move_uploaded_file($_FILES['resume']['tmp_name'], 'uploads/' . $new_filename)) {
                    $resume_file = $new_filename;
                }
            }
        }

        if (empty($error_msg)) {
            $stmt = $conn->prepare("INSERT INTO applications (job_id, worker_id, cover_letter, resume) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $job_id, $worker_id, $cover_letter, $resume_file);
            
            if ($stmt->execute()) {
                $success_msg = "Application submitted successfully!";
                $_POST = array();
            } else {
                $error_msg = "Error submitting application. Please try again.";
            }
        }
    }
}

// Get job details
$job = $conn->prepare("SELECT j.*, u.full_name FROM jobs j JOIN users u ON j.farmer_id = u.id WHERE j.id = ?");
$job->bind_param("i", $job_id);
$job->execute();
$job_data = $job->get_result()->fetch_assoc();
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-paper-plane"></i> Apply for Job</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h5><?php echo htmlspecialchars($job_data['job_title']); ?></h5>
                            <p class="mb-0">Posted by <strong><?php echo htmlspecialchars($job_data['full_name']); ?></strong></p>
                        </div>

                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                                <a href="dashboard.php" class="alert-link">Go to Dashboard</a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data" novalidate id="applyForm">
                            <div class="mb-3">
                                <label class="form-label">Cover Letter *</label>
                                <textarea class="form-control" name="cover_letter" rows="8" 
                                          placeholder="Tell us why you're interested in this position and what skills you bring..." required><?php echo htmlspecialchars($_POST['cover_letter'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">Write a compelling cover letter highlighting your experience</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Resume (Optional)</label>
                                <input type="file" class="form-control" name="resume" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max 2MB)</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-send"></i> Submit Application
                                </button>
                                <a href="job_detail.php?id=<?php echo $job_id; ?>" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
