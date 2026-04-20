-- ═══════════════════════════════════════════════════
--  DIGITRACT — Full Database Schema
-- ═══════════════════════════════════════════════════
CREATE DATABASE IF NOT EXISTS digitract CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digitract;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1, created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT IGNORE INTO admins (name, email, password_hash) VALUES
('Administrator','admin@digitract.com','$2y$12$eImiTXuWVxfM37uY4JANjuexFnMDpq5KqDKLnSp6jP7y8D9OxL6S2');

CREATE TABLE IF NOT EXISTS admin_login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY, admin_id INT NOT NULL,
    ip_address VARCHAR(45), latitude DECIMAL(10,8), longitude DECIMAL(11,8),
    neighbourhood VARCHAR(150), municipality VARCHAR(150),
    logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS instructors (
    id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20), specialization VARCHAR(100), license_number VARCHAR(50),
    license_expiry DATE, certificate_status ENUM('valid','expiring_soon','expired') DEFAULT 'valid',
    status ENUM('active','on_leave','inactive') DEFAULT 'active',
    profile_photo VARCHAR(255), created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS instructor_login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY, instructor_id INT NOT NULL,
    ip_address VARCHAR(45), logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,  -- must be @gmail.com
    password_hash VARCHAR(255) NOT NULL, phone VARCHAR(20), address VARCHAR(255),
    status ENUM('active','inactive','completed') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- OTP table: one active OTP per student at a time
CREATE TABLE IF NOT EXISTS student_otps (
    id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL,
    otp_hash VARCHAR(255) NOT NULL,   -- bcrypt of 6-digit OTP
    expires_at DATETIME NOT NULL,     -- 10 minutes TTL
    used TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS student_login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL,
    ip_address VARCHAR(45), logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT NOT NULL, student_id INT NOT NULL,
    lesson_date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL,
    lesson_type VARCHAR(100), location VARCHAR(255),
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    notes TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id)    REFERENCES students(id)    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL, student_id INT NOT NULL, instructor_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT, submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (schedule_id)   REFERENCES schedules(id)   ON DELETE CASCADE,
    FOREIGN KEY (student_id)    REFERENCES students(id)    ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS performance_records (
    id INT AUTO_INCREMENT PRIMARY KEY, instructor_id INT NOT NULL,
    period_month TINYINT NOT NULL, period_year YEAR NOT NULL,
    lessons_completed INT DEFAULT 0, avg_rating DECIMAL(3,2) DEFAULT 0.00,
    pass_rate DECIMAL(5,2) DEFAULT 0.00, attendance_rate DECIMAL(5,2) DEFAULT 0.00,
    notes TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_instr_period (instructor_id, period_month, period_year)
);

CREATE TABLE IF NOT EXISTS compliance_records (
    id INT AUTO_INCREMENT PRIMARY KEY, instructor_id INT NOT NULL,
    requirement_type VARCHAR(100) NOT NULL, issued_date DATE, expiry_date DATE,
    status ENUM('valid','expiring_soon','expired') DEFAULT 'valid',
    document_path VARCHAR(255), notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
);

CREATE INDEX idx_schedules_date       ON schedules(lesson_date);
CREATE INDEX idx_schedules_instructor ON schedules(instructor_id);
CREATE INDEX idx_student_otps_exp     ON student_otps(expires_at);

-- ── PATCH: if upgrading from earlier schema ──────────
-- ALTER TABLE instructors ADD COLUMN is_approved TINYINT(1) DEFAULT 0 AFTER status;
-- ALTER TABLE instructors ADD COLUMN approved_by INT DEFAULT NULL AFTER is_approved;
-- ALTER TABLE instructors ADD COLUMN approved_at DATETIME DEFAULT NULL AFTER approved_by;
-- ALTER TABLE instructors ADD COLUMN last_login DATETIME DEFAULT NULL AFTER approved_at;
-- ALTER TABLE students ADD COLUMN last_login DATETIME DEFAULT NULL AFTER status;
-- Note: students table uses Gmail OTP — no password_hash needed.
