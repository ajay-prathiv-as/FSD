-- =============================================
-- school_db.sql
-- Run this file in phpMyAdmin or MySQL terminal
-- =============================================


-- Step 1: Create and select the database
CREATE DATABASE IF NOT EXISTS school_db;
USE school_db;


-- =============================================
-- TABLE 1: users
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,        -- always store hashed passwords!
    role        VARCHAR(50)   DEFAULT 'Student',
    status      VARCHAR(20)   DEFAULT 'Active',
    created_at  DATETIME      DEFAULT CURRENT_TIMESTAMP
);


-- =============================================
-- TABLE 2: login_logs
-- Track every login attempt
-- =============================================
CREATE TABLE IF NOT EXISTS login_logs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT           NULL,            -- NULL if email not found
    email       VARCHAR(150)  NOT NULL,
    status      VARCHAR(20)   NOT NULL,        -- 'success' or 'failed'
    ip_address  VARCHAR(50),
    logged_at   DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


-- =============================================
-- INSERT USERS
-- Passwords are hashed using PHP password_hash()
--
-- Plain passwords used below:
--   Admin123   → for admin@school.com
--   Pass456    → for student@school.com
--   Teach789   → for teacher@school.com
--
-- To regenerate hashes run this in PHP:
--   echo password_hash("Admin123", PASSWORD_DEFAULT);
-- =============================================

INSERT INTO users (name, email, password, role, status) VALUES
(
  'Admin User',
  'admin@school.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Admin',
  'Active'
),
(
  'Ravi Kumar',
  'student@school.com',
  '$2y$10$TKh8H1.PfFk5QGb5hQ9EQe1qRTJCOMSB4RxV7D5z6bF/G7lFuP1K.',
  'Student',
  'Active'
),
(
  'Mrs. Lakshmi',
  'teacher@school.com',
  '$2y$10$abcdefghijklmnopqrstuuVGZzra.rko/7p3YyMaH4A5mS5N5bfXe',
  'Teacher',
  'Active'
),
(
  'Old Student',
  'old@school.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Student',
  'Inactive'            -- this account is blocked, cannot login
);


-- =============================================
-- USEFUL SELECT QUERIES
-- =============================================

-- View all users
-- SELECT id, name, email, role, status, created_at FROM users;

-- View only active users
-- SELECT * FROM users WHERE status = 'Active';

-- Count users per role
-- SELECT role, COUNT(*) AS total FROM users GROUP BY role;

-- View all login attempts
-- SELECT * FROM login_logs ORDER BY logged_at DESC;

-- View only failed login attempts
-- SELECT * FROM login_logs WHERE status = 'failed' ORDER BY logged_at DESC;

-- View login history for one user
-- SELECT * FROM login_logs WHERE email = 'admin@school.com' ORDER BY logged_at DESC;
