<?php
include 'includes/db_connection.php';
$page_title = 'Jobs';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$job_type = isset($_GET['job_type']) ? trim($_GET['job_type']) : '';

// Build query
$query = "SELECT j.*, u.full_name, u.phone FROM jobs j 
         JOIN users u ON j.farmer_id = u.id 
         WHERE j.is_active = TRUE";

$params = array();
$types = '';

if (!empty($search)) {
    $query .= " AND (j.job_title LIKE ? OR j.description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if (!empty($category)) {
    $query .= " AND j.category = ?";
    $params[] = $category;
    $types .= 's';
}

if (!empty($location)) {
    $query .= " AND (j.location LIKE ? OR j.city LIKE ?)";
    $location_param = "%$location%";
    $params[] = $location_param;
    $params[] = $location_param;
    $types .= 'ss';
}

if (!empty($job_type)) {
    $query .= " AND j.job_type = ?";
    $params[] = $job_type;
    $types .= 's';
}

$query .= " ORDER BY j.posted_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_jobs = $result->num_rows;
?>

<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Job Listings</h1>

        <!-- Filter Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search jobs..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <option value="Heavy Machinery" <?php echo $category == 'Heavy Machinery' ? 'selected' : ''; ?>>Heavy Machinery</option>
                            <option value="Manual Labor" <?php echo $category == 'Manual Labor' ? 'selected' : ''; ?>>Manual Labor</option>
                            <option value="Technical Skills" <?php echo $category == 'Technical Skills' ? 'selected' : ''; ?>>Technical Skills</option>
                            <option value="Supervision" <?php echo $category == 'Supervision' ? 'selected' : ''; ?>>Supervision</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="location" placeholder="Location..." 
                               value="<?php echo htmlspecialchars($location); ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="job_type">
                            <option value="">All Types</option>
                            <option value="full-time" <?php echo $job_type == 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                            <option value="part-time" <?php echo $job_type == 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                            <option value="seasonal" <?php echo $job_type == 'seasonal' ? 'selected' : ''; ?>>Seasonal</option>
                            <option value="contract" <?php echo $job_type == 'contract' ? 'selected' : ''; ?>>Contract</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-muted">Found <strong><?php echo $total_jobs; ?></strong> job(s)</p>

        <!-- Jobs List -->
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($job = $result->fetch_assoc()) {
            ?>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-briefcase text-success"></i> 
                            <?php echo htmlspecialchars($job['job_title']); ?>
                        </h5>
                        
                        <p class="text-muted mb-2">
                            <i class="fas fa-building"></i> 
                            Posted by: <strong><?php echo htmlspecialchars($job['full_name']); ?></strong>
                        </p>

                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt"></i> 
                            <?php echo htmlspecialchars($job['location']); ?> 
                            (<?php echo htmlspecialchars($job['city']); ?>, <?php echo htmlspecialchars($job['state']); ?>)
                        </p>

                        <p class="text-muted mb-2">
                            <i class="fas fa-tag"></i> 
                            <span class="badge bg-info"><?php echo htmlspecialchars($job['category']); ?></span>
                            <span class="badge bg-warning"><?php echo ucfirst(htmlspecialchars($job['job_type'])); ?></span>
                        </p>

                        <p class="card-text text-truncate">
                            <?php echo htmlspecialchars(substr($job['description'], 0, 150)) . '...'; ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 text-success mb-0">
                                ₹<?php echo number_format($job['salary_min']); ?> - ₹<?php echo number_format($job['salary_max']); ?>
                            </span>
                            <a href="job_detail.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-success">
                                View Details
                            </a>
                        </div>

                        <small class="text-muted d-block mt-3">
                            <i class="fas fa-calendar"></i> 
                            Posted: <?php echo date('M d, Y', strtotime($job['posted_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><p class="text-center text-muted">No jobs found. Try different filters.</p></div>';
            }
            ?>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
