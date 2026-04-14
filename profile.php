<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'My Profile';
$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// Fetch user data
$user = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user->bind_param("i", $user_id);
$user->execute();
$user_data = $user->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, city = ?, state = ?, bio = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $full_name, $phone, $city, $state, $bio, $user_id);

    if ($stmt->execute()) {
        $success_msg = "Profile updated successfully!";
        $_SESSION['full_name'] = $full_name;
        // Refresh user data
        $user = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $user->bind_param("i", $user_id);
        $user->execute();
        $user_data = $user->get_result()->fetch_assoc();
    } else {
        $error_msg = "Error updating profile!";
    }
}
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-user"></i> My Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success"><?php echo $success_msg; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div style="font-size: 80px; color: #28a745;">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h5><?php echo htmlspecialchars($user_data['full_name']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($user_data['email']); ?></p>
                                <span class="badge bg-<?php echo $user_data['role'] == 'farmer' ? 'primary' : 'info'; ?>">
                                    <?php echo ucfirst($user_data['role']); ?>
                                </span>
                                <p class="mt-3">
                                    <strong>Joined:</strong> <?php echo date('M d, Y', strtotime($user_data['created_at'])); ?>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="full_name" 
                                       value="<?php echo htmlspecialchars($user_data['full_name']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" 
                                       value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" 
                                           value="<?php echo htmlspecialchars($user_data['city'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state" 
                                           value="<?php echo htmlspecialchars($user_data['state'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" name="bio" rows="4"><?php echo htmlspecialchars($user_data['bio'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
