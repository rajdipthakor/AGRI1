<?php
include 'includes/db_connection.php';
$page_title = 'About Us';
?>
<?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">About AgriJobs</h1>

        <div class="row">
            <div class="col-md-8">
                <h2>Our Mission</h2>
                <p class="lead">
                    AgriJobs is dedicated to bridging the gap between farmers and skilled agricultural workers. 
                    We believe that efficient labor management is key to successful farming and rural development.
                </p>

                <h2 class="mt-5">Our Vision</h2>
                <p>
                    To create a thriving agricultural ecosystem where farmers can easily find skilled workers 
                    and workers can find meaningful employment in agriculture sector.
                </p>

                <h2 class="mt-5">Why We Started</h2>
                <p>
                    Agriculture is the backbone of our economy, yet finding reliable workers remains a significant 
                    challenge for farmers. AgriJobs was created to address this gap with a digital solution that 
                    makes hiring simple, efficient, and transparent.
                </p>

                <h2 class="mt-5">What We Offer</h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check text-success"></i> 
                        Easy job posting for farmers
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success"></i> 
                        Job search and application for workers
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success"></i> 
                        Secure communication between farmers and workers
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success"></i> 
                        User profiles and ratings
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success"></i> 
                        Mobile-friendly interface
                    </li>
                </ul>

                <h2 class="mt-5">Our Team</h2>
                <p>
                    We are a passionate team of developers, designers, and agricultural experts committed to 
                    transforming the agriculture labor market.
                </p>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow">
                    <div class="card-body text-center">
                        <div style="font-size: 80px; color: #28a745;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5 class="card-title mt-3">AgriJobs Platform</h5>
                        <p class="card-text text-muted">Connecting Agriculture with Opportunity</p>
                        <hr>
                        <p>
                            <strong>Est. 2026</strong><br>
                            Dedicated to transforming agricultural employment
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Contact Info</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <i class="fas fa-envelope"></i> 
                            info@agrijobs.com
                        </p>
                        <p>
                            <i class="fas fa-phone"></i> 
                            +1-800-AGRIJOBS
                        </p>
                        <p>
                            <i class="fas fa-map-marker-alt"></i> 
                            Agricultural Hub, City
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
