<?php
include 'includes/db_connection.php';

$registration_success = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');

    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
        $error_msg = "Please fill in all required fields!";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters long!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format!";
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        
        if ($check_email->get_result()->num_rows > 0) {
            $error_msg = "Email already registered!";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role, phone, city, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $role, $phone, $city, $state);
            
            if ($stmt->execute()) {
                $registration_success = true;
                // Redirect to login after 2 seconds
                header("refresh:2;url=login.php");
            } else {
                $error_msg = "Registration failed! Please try again.";
            }
        }
        $check_email->close();
    }
}

$page_title = 'Register';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AgriJobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf"></i> AgriJobs
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4"><i class="fas fa-user-plus"></i> Create Account</h2>
                        
                        <?php if ($registration_success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Registration successful! Redirecting to login...
                            </div>
                        <?php elseif (!empty($error_msg)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role *</label>
                                <select class="form-select" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="farmer" <?php echo ($_POST['role'] ?? '') == 'farmer' ? 'selected' : ''; ?>>Farmer (Post Jobs)</option>
                                    <option value="worker" <?php echo ($_POST['role'] ?? '') == 'worker' ? 'selected' : ''; ?>>Worker (Find Jobs)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                                <small class="form-text text-muted">At least 6 characters</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>

                        <hr>
                        <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
