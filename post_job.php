<?php
include 'includes/db_connection.php';

// Check if user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

$page_title = 'Post Job';
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = trim($_POST['job_title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $salary_min = trim($_POST['salary_min'] ?? '');
    $salary_max = trim($_POST['salary_max'] ?? '');
    $job_type = trim($_POST['job_type'] ?? '');
    $experience = trim($_POST['experience_required'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');

    // Validation
    if (empty($job_title) || empty($category) || empty($description) || empty($location) || 
        empty($salary_min) || empty($salary_max) || empty($deadline)) {
        $error_msg = "Please fill in all required fields!";
    } elseif ($salary_min > $salary_max) {
        $error_msg = "Minimum salary cannot be greater than maximum salary!";
    } else {
        $stmt = $conn->prepare("INSERT INTO jobs (farmer_id, job_title, category, description, location, city, state, 
                                                   salary_min, salary_max, job_type, experience_required, deadline) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $farmer_id = $_SESSION['user_id'];
        $stmt->bind_param("issssssiiss", $farmer_id, $job_title, $category, $description, $location, $city, $state, 
                         $salary_min, $salary_max, $job_type, $experience, $deadline);
        
        if ($stmt->execute()) {
            $success_msg = "Job posted successfully!";
            // Reset form
            $_POST = array();
        } else {
            $error_msg = "Error posting job. Please try again.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-briefcase"></i> Post a New Job</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate id="postJobForm">
                            <div class="mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" class="form-control" name="job_title" 
                                       value="<?php echo htmlspecialchars($_POST['job_title'] ?? ''); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Heavy Machinery" <?php echo ($_POST['category'] ?? '') == 'Heavy Machinery' ? 'selected' : ''; ?>>Heavy Machinery</option>
                                        <option value="Manual Labor" <?php echo ($_POST['category'] ?? '') == 'Manual Labor' ? 'selected' : ''; ?>>Manual Labor</option>
                                        <option value="Technical Skills" <?php echo ($_POST['category'] ?? '') == 'Technical Skills' ? 'selected' : ''; ?>>Technical Skills</option>
                                        <option value="Supervision" <?php echo ($_POST['category'] ?? '') == 'Supervision' ? 'selected' : ''; ?>>Supervision</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Job Type *</label>
                                    <select class="form-select" name="job_type" required>
                                        <option value="">Select Type</option>
                                        <option value="full-time" <?php echo ($_POST['job_type'] ?? '') == 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                                        <option value="part-time" <?php echo ($_POST['job_type'] ?? '') == 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                                        <option value="seasonal" <?php echo ($_POST['job_type'] ?? '') == 'seasonal' ? 'selected' : ''; ?>>Seasonal</option>
                                        <option value="contract" <?php echo ($_POST['job_type'] ?? '') == 'contract' ? 'selected' : ''; ?>>Contract</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">Provide detailed description of the job</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Experience Required</label>
                                <input type="text" class="form-control" name="experience_required" 
                                       placeholder="e.g., 2+ years" value="<?php echo htmlspecialchars($_POST['experience_required'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location *</label>
                                    <input type="text" class="form-control" name="location" 
                                           value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" name="city" 
                                           value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">State *</label>
                                    <input type="text" class="form-control" name="state" 
                                           value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salary (Min) *</label>
                                    <input type="number" class="form-control" name="salary_min" step="100"
                                           value="<?php echo htmlspecialchars($_POST['salary_min'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salary (Max) *</label>
                                    <input type="number" class="form-control" name="salary_max" step="100"
                                           value="<?php echo htmlspecialchars($_POST['salary_max'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Application Deadline *</label>
                                <input type="date" class="form-control" name="deadline" 
                                       value="<?php echo htmlspecialchars($_POST['deadline'] ?? ''); ?>" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check"></i> Post Job
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
