CREATE DATABASE IF NOT EXISTS property_lab;
USE property_lab;

-- 1. Users Table (Includes a balance field for testing CSRF transfers)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 50000.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Properties Table
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    city VARCHAR(100),
    price VARCHAR(50)
);

-- 3. Comments Table (Stored XSS target)
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Lab Data
INSERT INTO properties (title, city, price) VALUES
('Modern Apartment', 'Dhaka', '25,000 BDT'),
('Luxury Villa', 'Chattogram', '60,000 BDT'),
('Premium Duplex House', 'Sylhet', '55,000 BDT');

-- Default Test Users (Password for both is 'password' if using login.php)
INSERT INTO users (fullname, username, email, password, balance) VALUES 
('Test User', 'user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 50000.00),
('Attacker Account', 'attacker', 'attacker@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 100.00);