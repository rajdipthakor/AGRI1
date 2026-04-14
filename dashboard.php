<?php
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Dashboard';
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div style="font-size: 60px; color: #28a745;">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h5 class="mt-3"><?php echo htmlspecialchars($_SESSION['full_name']); ?></h5>
                        <p class="text-muted"><strong><?php echo ucfirst($user_role); ?></strong></p>
                        <a href="profile.php" class="btn btn-sm btn-success">Edit Profile</a>
                    </div>
                </div>

                <div class="list-group mt-4">
                    <?php if ($user_role == 'farmer'): ?>
                        <a href="dashboard.php?tab=my-jobs" class="list-group-item list-group-item-action">
                            <i class="fas fa-briefcase"></i> My Jobs
                        </a>
                        <a href="post_job.php" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus"></i> Post New Job
                        </a>
                        <a href="dashboard.php?tab=applications" class="list-group-item list-group-item-action">
                            <i class="fas fa-users"></i> Applications
                        </a>
                    <?php elseif ($user_role == 'worker'): ?>
                        <a href="dashboard.php?tab=applied-jobs" class="list-group-item list-group-item-action">
                            <i class="fas fa-paper-plane"></i> Applied Jobs
                        </a>
                        <a href="jobs.php" class="list-group-item list-group-item-action">
                            <i class="fas fa-search"></i> Browse Jobs
                        </a>
                        <a href="dashboard.php?tab=saved-jobs" class="list-group-item list-group-item-action">
                            <i class="fas fa-heart"></i> Saved Jobs
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <?php
                $tab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';

                if ($user_role == 'farmer'):
                    if ($tab == 'my-jobs'):
                        // Show farmer's jobs
                        $jobs = $conn->prepare("SELECT * FROM jobs WHERE farmer_id = ? ORDER BY posted_at DESC");
                        $jobs->bind_param("i", $user_id);
                        $jobs->execute();
                        $result = $jobs->get_result();
                ?>
                        <h2 class="mb-4">My Job Postings</h2>
                        <div class="row">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($job = $result->fetch_assoc()): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h5>
                                            <p class="text-muted mb-3"><?php echo htmlspecialchars($job['location']); ?></p>
                                            <p class="card-text text-truncate"><?php echo htmlspecialchars(substr($job['description'], 0, 100)); ?>...</p>
                                            <div class="d-flex gap-2">
                                                <a href="job_detail.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-info">View</a>
                                                <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="delete_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> You haven't posted any jobs yet.
                                        <a href="post_job.php">Post your first job</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                <?php
                    elseif ($tab == 'applications'):
                        // Show applications for farmer's jobs
                        $apps = $conn->prepare("SELECT a.*, j.job_title, u.full_name, u.email, u.phone 
                                               FROM applications a 
                                               JOIN jobs j ON a.job_id = j.id 
                                               JOIN users u ON a.worker_id = u.id 
                                               WHERE j.farmer_id = ? 
                                               ORDER BY a.applied_at DESC");
                        $apps->bind_param("i", $user_id);
                        $apps->execute();
                        $result = $apps->get_result();
                ?>
                        <h2 class="mb-4">Applications Received</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>Worker Name</th>
                                        <th>Job Title</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($app = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                            <td><?php echo htmlspecialchars($app['email']); ?></td>
                                            <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $app['status'] == 'pending' ? 'warning' : 
                                                         ($app['status'] == 'shortlisted' ? 'info' : 
                                                          ($app['status'] == 'hired' ? 'success' : 'danger'));
                                                ?>">
                                                    <?php echo ucfirst($app['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                            <td>
                                                <a href="view_application.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center text-muted">No applications yet</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                <?php
                    else: // Overview
                        $stats = $conn->prepare("SELECT 
                            (SELECT COUNT(*) FROM jobs WHERE farmer_id = ?) as total_jobs,
                            (SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.farmer_id = ?) as total_applications,
                            (SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.farmer_id = ? AND a.status = 'hired') as hired");
                        $stats->bind_param("iii", $user_id, $user_id, $user_id);
                        $stats->execute();
                        $stat_data = $stats->get_result()->fetch_assoc();
                ?>
                        <h2 class="mb-4">Overview</h2>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-success"><?php echo $stat_data['total_jobs']; ?></h3>
                                        <p class="text-muted">Active Jobs Posted</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-info"><?php echo $stat_data['total_applications']; ?></h3>
                                        <p class="text-muted">Total Applications</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-success"><?php echo $stat_data['hired']; ?></h3>
                                        <p class="text-muted">Workers Hired</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="post_job.php" class="btn btn-lg btn-success">Post New Job</a>
                <?php
                    endif;
                elseif ($user_role == 'worker'):
                    if ($tab == 'applied-jobs'):
                        // Show applied jobs
                        $apps = $conn->prepare("SELECT a.*, j.job_title, j.location, j.salary_min, j.salary_max, u.full_name 
                                               FROM applications a 
                                               JOIN jobs j ON a.job_id = j.id 
                                               JOIN users u ON j.farmer_id = u.id 
                                               WHERE a.worker_id = ? 
                                               ORDER BY a.applied_at DESC");
                        $apps->bind_param("i", $user_id);
                        $apps->execute();
                        $result = $apps->get_result();
                ?>
                        <h2 class="mb-4">Applied Jobs</h2>
                        <div class="row">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($app = $result->fetch_assoc()): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($app['job_title']); ?></h5>
                                            <p class="text-muted"><?php echo htmlspecialchars($app['location']); ?></p>
                                            <p class="h6 text-success">₹<?php echo number_format($app['salary_min']); ?> - ₹<?php echo number_format($app['salary_max']); ?></p>
                                            <p class="small mb-3">
                                                Status: <span class="badge bg-<?php 
                                                    echo $app['status'] == 'pending' ? 'warning' : 
                                                         ($app['status'] == 'shortlisted' ? 'info' : 
                                                          ($app['status'] == 'hired' ? 'success' : 'danger'));
                                                ?>">
                                                    <?php echo ucfirst($app['status']); ?>
                                                </span>
                                            </p>
                                            <small class="text-muted">Applied on <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> You haven't applied to any jobs yet.
                                        <a href="jobs.php">Browse available jobs</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                <?php
                    elseif ($tab == 'saved-jobs'):
                        // Show saved jobs
                        echo '<h2 class="mb-4">Saved Jobs</h2>';
                        echo '<div class="alert alert-info">Saved jobs feature coming soon!</div>';
                    else: // Overview
                        $stats = $conn->prepare("SELECT 
                            (SELECT COUNT(*) FROM applications WHERE worker_id = ?) as total_applications,
                            (SELECT COUNT(*) FROM applications WHERE worker_id = ? AND status = 'hired') as hired,
                            (SELECT COUNT(*) FROM applications WHERE worker_id = ? AND status = 'shortlisted') as shortlisted");
                        $stats->bind_param("iii", $user_id, $user_id, $user_id);
                        $stats->execute();
                        $stat_data = $stats->get_result()->fetch_assoc();
                ?>
                        <h2 class="mb-4">Your Activity</h2>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-info"><?php echo $stat_data['total_applications']; ?></h3>
                                        <p class="text-muted">Jobs Applied</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning"><?php echo $stat_data['shortlisted']; ?></h3>
                                        <p class="text-muted">Shortlisted</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h3 class="text-success"><?php echo $stat_data['hired']; ?></h3>
                                        <p class="text-muted">Jobs Hired</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="jobs.php" class="btn btn-lg btn-success">Browse Jobs</a>
                <?php
                    endif;
                endif;
                ?>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
