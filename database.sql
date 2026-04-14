-- Create Database
CREATE DATABASE IF NOT EXISTS agriculture_jobs;
USE agriculture_jobs;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('farmer', 'worker', 'admin') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    pincode VARCHAR(10),
    bio TEXT,
    profile_pic VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Jobs Table
CREATE TABLE IF NOT EXISTS jobs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    farmer_id INT NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(100) NOT NULL,
    city VARCHAR(50),
    state VARCHAR(50),
    salary_min DECIMAL(10, 2),
    salary_max DECIMAL(10, 2),
    job_type ENUM('full-time', 'part-time', 'seasonal', 'contract') DEFAULT 'full-time',
    experience_required VARCHAR(50),
    contact_name VARCHAR(100),
    contact_phone VARCHAR(20),
    contact_email VARCHAR(100),
    image VARCHAR(255),
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deadline DATE,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Applications Table
CREATE TABLE IF NOT EXISTS applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,
    worker_id INT NOT NULL,
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'hired') DEFAULT 'pending',
    cover_letter TEXT,
    resume VARCHAR(255),
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, worker_id)
);

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    worker_id INT NOT NULL,
    farmer_id INT NOT NULL,
    job_id INT NOT NULL,
    rating INT CHECK(rating >= 1 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Saved Jobs Table
CREATE TABLE IF NOT EXISTS saved_jobs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    worker_id INT NOT NULL,
    job_id INT NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_saved (worker_id, job_id)
);

-- Create Indexes for better performance
CREATE INDEX idx_farmer_jobs ON jobs(farmer_id);
CREATE INDEX idx_job_status ON jobs(is_active);
CREATE INDEX idx_worker_jobs ON applications(worker_id);
CREATE INDEX idx_job_applications ON applications(job_id);
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_user_email ON users(email);

-- Insert Sample Admin User (Password: admin123)
INSERT INTO users (full_name, email, password, role, phone) VALUES
('Admin User', 'admin@agrijobs.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36jXy51O', 'admin', '9999999999');

-- Insert Sample Farmer (Password: farmer123)
INSERT INTO users (full_name, email, password, role, phone, city, state) VALUES
('Rajesh Kumar', 'farmer@example.com', '$2y$10$CrHPnANmjLDmh7l5v5f4EOv1F7K0p5CvS8YAQtH4fTJnXz2PFqkqS', 'farmer', '9876543210', 'Delhi', 'Delhi');

-- Insert Sample Worker (Password: worker123)
INSERT INTO users (full_name, email, password, role, phone, city, state) VALUES
('Priya Singh', 'worker@example.com', '$2y$10$pX2i5xd9L8Fg7Q2mZj9tXup8k6Yv5E3nW4hR1B9AxDcIyK5Lm0N3G', 'worker', '9876543211', 'Delhi', 'Delhi');

-- Insert Sample Jobs
INSERT INTO jobs (farmer_id, job_title, category, description, location, city, state, salary_min, salary_max, job_type, experience_required, contact_name, contact_phone, contact_email, deadline) VALUES
(2, 'Tractor Driver Needed', 'Heavy Machinery', 'Experienced tractor driver needed for farm operations. Must have valid license and 2+ years experience.', 'Delhi', 'Delhi', 'Delhi', 25000, 35000, 'full-time', '2+ years', 'Rajesh Kumar', '9876543210', 'farmer@example.com', '2026-05-31'),
(2, 'Farm Labor - Harvest Season', 'Manual Labor', 'Seeking multiple farm workers for seasonal harvest work. Hard work, good pay. Free meals and accommodation provided.', 'Delhi', 'Delhi', 'Delhi', 15000, 20000, 'seasonal', '0-1 years', 'Rajesh Kumar', '9876543210', 'farmer@example.com', '2026-04-30'),
(2, 'Irrigation Expert', 'Technical Skills', 'Need expert in modern irrigation systems. Knowledge of drip irrigation and water management essential.', 'Delhi', 'Delhi', 'Delhi', 40000, 50000, 'full-time', '3+ years', 'Rajesh Kumar', '9876543210', 'farmer@example.com', '2026-06-15');
