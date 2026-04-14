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

$job_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$page_title = 'Edit Job';

// Verify ownership
$check = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND farmer_id = ?");
$check->bind_param("ii", $job_id, $user_id);
$check->execute();
$job = $check->get_result()->fetch_assoc();

if (!$job) {
    header("Location: dashboard.php");
    exit();
}

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = trim($_POST['job_title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $salary_min = (int)($_POST['salary_min'] ?? 0);
    $salary_max = (int)($_POST['salary_max'] ?? 0);
    $job_type = trim($_POST['job_type'] ?? '');
    $experience = trim($_POST['experience_required'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');

    if (empty($job_title) || empty($description) || empty($location)) {
        $error_msg = "Please fill all required fields!";
    } else {
        $stmt = $conn->prepare("UPDATE jobs SET job_title=?, category=?, description=?, location=?, city=?, state=?, 
                              salary_min=?, salary_max=?, job_type=?, experience_required=?, deadline=? WHERE id=?");
        $stmt->bind_param("ssssssiiissi", $job_title, $category, $description, $location, $city, $state, 
                         $salary_min, $salary_max, $job_type, $experience, $deadline, $job_id);
        
        if ($stmt->execute()) {
            $success_msg = "Job updated successfully!";
        } else {
            $error_msg = "Error updating job!";
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
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Job</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                                <a href="dashboard.php?tab=my-jobs" class="alert-link">Back to Jobs</a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate id="editJobForm">
                            <div class="mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" class="form-control" name="job_title" 
                                       value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Heavy Machinery" <?php echo $job['category'] == 'Heavy Machinery' ? 'selected' : ''; ?>>Heavy Machinery</option>
                                        <option value="Manual Labor" <?php echo $job['category'] == 'Manual Labor' ? 'selected' : ''; ?>>Manual Labor</option>
                                        <option value="Technical Skills" <?php echo $job['category'] == 'Technical Skills' ? 'selected' : ''; ?>>Technical Skills</option>
                                        <option value="Supervision" <?php echo $job['category'] == 'Supervision' ? 'selected' : ''; ?>>Supervision</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Job Type *</label>
                                    <select class="form-select" name="job_type" required>
                                        <option value="">Select Type</option>
                                        <option value="full-time" <?php echo $job['job_type'] == 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                                        <option value="part-time" <?php echo $job['job_type'] == 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                                        <option value="seasonal" <?php echo $job['job_type'] == 'seasonal' ? 'selected' : ''; ?>>Seasonal</option>
                                        <option value="contract" <?php echo $job['job_type'] == 'contract' ? 'selected' : ''; ?>>Contract</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Experience Required</label>
                                <input type="text" class="form-control" name="experience_required" 
                                       placeholder="e.g., 2+ years" value="<?php echo htmlspecialchars($job['experience_required']); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location *</label>
                                    <input type="text" class="form-control" name="location" 
                                           value="<?php echo htmlspecialchars($job['location']); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" name="city" 
                                           value="<?php echo htmlspecialchars($job['city']); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">State *</label>
                                    <input type="text" class="form-control" name="state" 
                                           value="<?php echo htmlspecialchars($job['state']); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salary (Min) *</label>
                                    <input type="number" class="form-control" name="salary_min" step="100"
                                           value="<?php echo htmlspecialchars($job['salary_min']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salary (Max) *</label>
                                    <input type="number" class="form-control" name="salary_max" step="100"
                                           value="<?php echo htmlspecialchars($job['salary_max']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Application Deadline *</label>
                                <input type="date" class="form-control" name="deadline" 
                                       value="<?php echo htmlspecialchars($job['deadline']); ?>" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="dashboard.php?tab=my-jobs" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
