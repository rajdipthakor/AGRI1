<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Messages';
?>

<?php include '../includes/header.php'; ?>

    <div class="container-fluid py-5">
        <h1 class="mb-4">Messages & Inquiries</h1>

        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">All Messages</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $messages = $conn->query("SELECT m.*, u.full_name, u.email
                                                    FROM messages m
                                                    LEFT JOIN users u ON m.sender_id = u.id
                                                    ORDER BY m.sent_at DESC");
                            while ($msg = $messages->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['full_name'] ?? 'Guest'); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($msg['sent_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $msg['is_read'] ? 'success' : 'warning'; ?>">
                                        <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#msgModal" 
                                            onclick="showMessage('<?php echo htmlspecialchars($msg['full_name'] ?? 'Guest'); ?>', '<?php echo htmlspecialchars($msg['subject']); ?>', '<?php echo htmlspecialchars($msg['message']); ?>')">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="msgModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="msgTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="msgBody"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>

<script>
function showMessage(from, subject, message) {
    document.getElementById('msgTitle').textContent = 'From: ' + from + ' - ' + subject;
    document.getElementById('msgBody').textContent = message;
}
</script>
