# Agriculture Hiring Website - Setup Guide

## Project Overview
AgriJobs is a comprehensive platform connecting farmers with agricultural workers. The platform features user authentication, job posting, job applications, admin panel, and more.

## Project Structure
```
agri/
├── css/
│   └── style.css             # Custom styling for the website
├── js/
│   └── script.js             # JavaScript functionality
├── images/                   # Image assets folder
├── includes/
│   ├── db_connection.php     # Database connection
│   ├── header.php            # Header template
│   └── footer.php            # Footer template
├── admin/
│   ├── dashboard.php         # Admin dashboard
│   ├── manage_users.php      # Manage users
│   ├── manage_jobs.php       # Manage jobs
│   ├── manage_applications.php # Manage applications
│   └── manage_messages.php   # Manage messages
├── uploads/                  # Upload directory for resumes
├── index.php                 # Home page
├── about.php                 # About page
├── jobs.php                  # Job listings page
├── job_detail.php            # Job detail page
├── post_job.php              # Post job page (farmer)
├── apply_job.php             # Apply job page (worker)
├── dashboard.php             # User dashboard
├── profile.php               # User profile
├── contact.php               # Contact page
├── login.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Logout script
└── database.sql              # Database schema
```

## Setup Instructions

### 1. Database Setup
1. Open phpMyAdmin (or MySQL client)
2. Create a new database called `agriculture_jobs`
3. Import the `database.sql` file:
   - Go to Import tab
   - Select `database.sql`
   - Click Import

Or import via command line:
```bash
mysql -u root -p agriculture_jobs < database.sql
```

### 2. File Configuration
1. Update database credentials in `includes/db_connection.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');      // Add your password
   define('DB_NAME', 'agriculture_jobs');
   ```

### 3. Upload Directory
Ensure `uploads/` folder has write permissions:
```bash
chmod 755 uploads/
```

## Demo Credentials

After importing the database, use these credentials to login:

### Farmer Account
- **Email:** farmer@example.com
- **Password:** farmer123
- **Role:** Farmer (can post jobs)

### Worker Account
- **Email:** worker@example.com
- **Password:** worker123
- **Role:** Worker (can apply for jobs)

### Admin Account
- **Email:** admin@agrijobs.com
- **Password:** admin123
- **Role:** Admin (full access)

## Features

### User Roles
- **Farmer**: Can post jobs, manage job listings, view applications, hire workers
- **Worker**: Can browse jobs, apply for positions, manage applications
- **Admin**: Can manage users, jobs, applications, and messages

### Core Features

#### 1. Authentication System
- User registration with role selection
- Secure login with password hashing
- Session-based authentication
- Logout functionality

#### 2. Job Management
- Post new jobs (Farmer only)
- Edit/delete job listings
- Job listing with advanced filters
- Job detail view
- Application management

#### 3. Job Search & Filtering
- Search by job title or description
- Filter by category
- Filter by location
- Filter by job type (full-time, part-time, seasonal, contract)

#### 4. Dashboard
**Farmer Dashboard:**
- View all posted jobs
- See applications received
- Update application status
- Job statistics

**Worker Dashboard:**
- View applied jobs
- Track application status
- Browse available jobs
- Job recommendations

#### 5. Admin Panel
- Manage all users (activate/deactivate)
- Manage all jobs
- Manage applications
- View messages/inquiries
- Platform statistics

#### 6. User Profiles
- View/edit profile information
- Display user role
- Location information
- Bio/description

#### 7. Contact System
- Contact form for inquiries
- Message storage in database
- Admin message management

### Technologies Used

**Frontend:**
- HTML5
- CSS3
- JavaScript (ES6+)
- Bootstrap 5.1.3
- Font Awesome 6.0

**Backend:**
- PHP 7.4+
- MySQL/MariaDB
- Session Management

**Security Features:**
- Password hashing with bcrypt
- SQL prepared statements (prevent SQL injection)
- Session validation
- CSRF protection
- Input sanitization

## Usage Guide

### For Farmers
1. Register as a Farmer
2. Complete your profile
3. Go to Dashboard → Post New Job
4. Fill job details and submit
5. View applications in Dashboard
6. Update application status (shortlist/hire/reject)
7. Manage your job listings

### For Workers
1. Register as a Worker
2. Complete your profile
3. Go to Jobs page
4. Search/filter jobs of interest
5. Click "View Details" to see full job info
6. Click "Apply Now" to submit application
7. Track application status in Dashboard

### For Admin
1. Login with admin credentials
2. Go to Admin Dashboard
3. Use management options to:
   - Manage users (activate/deactivate)
   - Manage job postings
   - Update application statuses
   - View platform statistics

## Configuration Options

### Customize Colors
Edit `css/style.css`:
```css
:root {
    --primary-color: #28a745;
    --secondary-color: #6c757d;
    --dark-color: #343a40;
    --light-color: #f8f9fa;
}
```

### Customize Site Title
Update in `includes/header.php`:
```php
<title><?php echo isset($page_title) ? $page_title . ' - AgriJobs' : 'AgriJobs'; ?></title>
```

### Email Configuration (Optional)
Add email notification functionality in post_job.php and apply_job.php:
```php
// Example: Send email to farmer on new application
mail($farmer_email, "New Application", $message);
```

## API Endpoints (Future Enhancement)

The platform can be extended with REST APIs:
- `GET /api/jobs/` - List all jobs
- `GET /api/jobs/{id}` - Get job details
- `POST /api/applications/` - Submit application
- `GET /api/user/applications` - Get user applications
- `POST /api/login` - User authentication

## Performance Optimization

1. **Database Indexing**: Already implemented on frequently queried fields
2. **Caching**: Consider implementing Redis for session data
3. **CDN**: Use Bootstrap and Font Awesome from CDN (already done)
4. **Image Optimization**: Compress images in `/images` folder
5. **Database Connection Pooling**: Implement for production

## Security Checklist

- ✅ Password hashing with bcrypt
- ✅ Prepared statements for all queries
- ✅ Session validation
- ✅ Input sanitization
- ✅ HTTPS ready (configure on server)
- ⚠️ Add rate limiting for login attempts
- ⚠️ Implement CSRF tokens for forms
- ⚠️ Add 2FA for admin accounts
- ⚠️ Regular security audits

## Troubleshooting

### Database Connection Error
- Ensure MySQL/MariaDB is running
- Check credentials in `includes/db_connection.php`
- Verify database name is `agriculture_jobs`

### Upload Issues
- Check `uploads/` folder permissions
- Ensure folder exists and is writable
- Check file size limits in php.ini

### Session Issues
- Clear browser cookies
- Check session timeout in php.ini
- Verify cookies are enabled in browser

### 404 Errors
- Ensure all files are in correct folders
- Check file names match include statements
- Verify .htaccess if using URL rewriting

## Future Enhancements

1. **Email Notifications**
   - Send email alerts on new jobs
   - Application status notifications
   - Message notifications

2. **Advanced Features**
   - Rating and reviews system
   - Worker verification
   - Payment integration
   - Skill matching algorithm

3. **Mobile App**
   - React Native or Flutter app
   - Push notifications
   - Offline functionality

4. **Analytics Dashboard**
   - User engagement metrics
   - Job posting trends
   - Application success rates
   - Revenue reporting

5. **Internationalization**
   - Multi-language support
   - Currency conversion
   - Regional customization

## Support & Maintenance

For issues or questions:
1. Check the troubleshooting section
2. Review PHP error logs
3. Check MySQL error logs
4. Verify server requirements (PHP 7.4+, MySQL 5.7+)

## License

This project is provided as-is for educational and commercial use.

## Credits

Developed as a comprehensive agriculture hiring platform solution.

---

**Last Updated:** April 2026
**Version:** 1.0.0
