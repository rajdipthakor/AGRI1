<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Manage Users';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$success_msg = '';
$error_msg = '';

// Handle user activation/deactivation
if ($action == 'toggle' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $conn->query("UPDATE users SET is_active = NOT is_active WHERE id = $user_id");
    header("Location: manage_users.php");
    exit();
}

// Handle user deletion
if ($action == 'delete' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $conn->query("DELETE FROM users WHERE id = $user_id AND role != 'admin'");
    header("Location: manage_users.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

    <div class="container-fluid py-5">
        <h1 class="mb-4">Manage Users</h1>

        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">All Users</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = $conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC");
                            while ($user = $users->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td><span class="badge bg-<?php echo $user['role'] == 'farmer' ? 'primary' : 'info'; ?>">
                                    <?php echo ucfirst($user['role']); ?></span></td>
                                <td><?php echo htmlspecialchars($user['city'] ?? '-'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?action=toggle&id=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>">
                                        <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </a>
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
