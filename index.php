<html>
    <hade>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z7QPJKYXPH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Z7QPJKYXPH');
</script>
    </hade>
    <body>
        
    </body>
</html>
<?php
include 'includes/db_connection.php';
$page_title = 'Home';
?>
<?php include 'includes/header.php'; ?>

    <!-- Hero Banner -->
    <section class="hero-banner bg-success text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Welcome to AgriJobs</h1>
                    <p class="lead">Connect farmers with skilled agricultural workers</p>
                    <p class="mb-4">Post jobs, find workers, and grow your farm business efficiently.</p>
                    <div class="d-grid gap-2 d-sm-flex">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="register.php" class="btn btn-light btn-lg px-4 gap-3">Get Started</a>
                            <a href="jobs.php" class="btn btn-outline-light btn-lg px-4">Browse Jobs</a>
                        <?php else: ?>
                            <a href="jobs.php" class="btn btn-light btn-lg px-4 gap-3">Browse Jobs</a>
                            <a href="dashboard.php" class="btn btn-outline-light btn-lg px-4">Dashboard</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="text-align: center; font-size: 120px;">
                        <i class="fas fa-tractor"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose AgriJobs?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-briefcase text-success" style="font-size: 40px;"></i>
                            <h5 class="card-title mt-3">Easy Job Posting</h5>
                            <p class="card-text">Farmers can post jobs in minutes with our simple interface.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-search text-success" style="font-size: 40px;"></i>
                            <h5 class="card-title mt-3">Find Perfect Match</h5>
                            <p class="card-text">Workers can search and apply for jobs that suit their skills.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-lock text-success" style="font-size: 40px;"></i>
                            <h5 class="card-title mt-3">Secure & Safe</h5>
                            <p class="card-text">Your data is protected with industry-standard security measures.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Jobs Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Recent Job Openings</h2>
            <div class="row">
                <?php
                $query = "SELECT j.*, u.full_name, u.phone FROM jobs j 
                         JOIN users u ON j.farmer_id = u.id 
                         WHERE j.is_active = TRUE ORDER BY j.posted_at DESC LIMIT 3";
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0) {
                    while ($job = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h5>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo htmlspecialchars($job['location']); ?>
                            </p>
                            <p class="card-text text-truncate"><?php echo htmlspecialchars(substr($job['description'], 0, 100)) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">
                                    ₹<?php echo number_format($job['salary_min']); ?>
                                </span>
                                <a href="job_detail.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-success">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center text-muted">No jobs available yet.</p></div>';
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="jobs.php" class="btn btn-lg btn-success">View All Jobs</a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <h3 class="text-success display-5">
                        <?php
                        $jobs_count = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE is_active = TRUE")->fetch_assoc()['count'];
                        echo $jobs_count;
                        ?>
                    </h3>
                    <p class="text-muted">Active Job Openings</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 class="text-success display-5">
                        <?php
                        $users_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role != 'admin'")->fetch_assoc()['count'];
                        echo $users_count;
                        ?>
                    </h3>
                    <p class="text-muted">Registered Users</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 class="text-success display-5">
                        <?php
                        $apps_count = $conn->query("SELECT COUNT(*) as count FROM applications")->fetch_assoc()['count'];
                        echo $apps_count;
                        ?>
                    </h3>
                    <p class="text-muted">Successful Applications</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-success text-white">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of farmers and workers already using AgriJobs</p>
            <a href="register.php" class="btn btn-light btn-lg">Create Account Now</a>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
