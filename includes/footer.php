    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-leaf"></i> AgriJobs</h5>
                    <p>Connecting farmers with skilled agricultural workers for better productivity.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-decoration-none text-white-50">Home</a></li>
                        <li><a href="jobs.php" class="text-decoration-none text-white-50">Browse Jobs</a></li>
                        <li><a href="about.php" class="text-decoration-none text-white-50">About Us</a></li>
                        <li><a href="contact.php" class="text-decoration-none text-white-50">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <p>
                        <i class="fas fa-envelope"></i> info@agrijobs.com<br>
                        <i class="fas fa-phone"></i> +1-800-AGRIJOBS<br>
                        <i class="fas fa-map-marker-alt"></i> Agricultural Hub, City
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p>&copy; 2026 AgriJobs Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo (strpos(basename($_SERVER['PHP_SELF']), 'admin') !== false) ? '../js/script.js' : 'js/script.js'; ?>"></script>
</body>
</html>
