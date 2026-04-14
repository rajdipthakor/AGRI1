<?php
include 'includes/db_connection.php';
$page_title = 'Contact Us';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_msg = "Please fill in all fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email address!";
    } else {
        // Store message in database
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
        $admin_id = 1; // Admin user ID
        $sender_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        
        $stmt->bind_param("iiss", $sender_id, $admin_id, $subject, $message);
        
        if ($stmt->execute()) {
            $success_msg = "Thank you! Your message has been sent successfully.";
            $_POST = array();
        } else {
            $error_msg = "Error sending message. Please try again.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="text-center mb-5">Contact Us</h1>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div style="font-size: 40px; color: #28a745;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5 class="mt-3">Email</h5>
                        <p class="text-muted">info@agrijobs.com</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div style="font-size: 40px; color: #28a745;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5 class="mt-3">Phone</h5>
                        <p class="text-muted">+1-800-AGRIJOBS</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div style="font-size: 40px; color: #28a745;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="mt-3">Address</h5>
                        <p class="text-muted">Agricultural Hub, City</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Send us a Message</h4>
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

                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject *</label>
                                <input type="text" class="form-control" name="subject" 
                                       value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
