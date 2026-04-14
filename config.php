/**
 * Agriculture Jobs Platform - Configuration
 * Centralized configuration file for the application
 */

<?php
// Application Settings
define('APP_NAME', 'AgriJobs');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Agriculture Hiring Platform');

// Database Settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agriculture_jobs');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour
session_set_cookie_params(array(
    'lifetime' => SESSION_TIMEOUT,
    'path' => '/',
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
));

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', array('pdf', 'doc', 'docx'));
define('UPLOAD_DIR', 'uploads/');

// Email Settings (If needed)
define('ADMIN_EMAIL', 'admin@agrijobs.com');
define('SUPPORT_EMAIL', 'support@agrijobs.com');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Job Categories
$JOB_CATEGORIES = array(
    'Heavy Machinery' => 'Tractor driving, equipment operation',
    'Manual Labor' => 'Farm labor, harvesting, planting',
    'Technical Skills' => 'Irrigation, soil testing, pest management',
    'Supervision' => 'Farm management, supervisory roles'
);

// Job Types
$JOB_TYPES = array(
    'full-time' => 'Full-time',
    'part-time' => 'Part-time',
    'seasonal' => 'Seasonal',
    'contract' => 'Contract'
);

// Application Statuses
$APP_STATUSES = array(
    'pending' => 'Pending',
    'reviewed' => 'Reviewed',
    'shortlisted' => 'Shortlisted',
    'rejected' => 'Rejected',
    'hired' => 'Hired'
);

// User Roles
$USER_ROLES = array(
    'farmer' => 'Farmer',
    'worker' => 'Worker',
    'admin' => 'Administrator'
);

?>
