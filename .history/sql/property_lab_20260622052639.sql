CREATE DATABASE IF NOT EXISTS property_lab;
USE property_lab;

-- Drop old tables if they exist to prevent conflicts during lab reset
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS users;

-- 1. Users Table (Tracks authentication and wallet balance for CSRF)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 50000.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Properties Table (Target for SQL Injection search strings)
CREATE TABLE properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200),
    city VARCHAR(100),
    price VARCHAR(50)
);

-- 3. Comments Table (Target for Stored XSS payload rendering)
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    comment_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Practice Lab Data
INSERT INTO properties (title, city, price) VALUES
('Modern Apartment', 'Dhaka', '25,000 BDT'),
('Luxury Villa', 'Chattogram', '60,000 BDT'),
('Premium Duplex House', 'Sylhet', '55,000 BDT');

-- Insert a default demo user and an attacker account (password for both is 'password')
INSERT INTO users (fullname, username, email, password, balance) VALUES 
('Test User1', 'user1', 'user1@example.com', '123', 50000.00),
('Test User2', 'user2', 'user2@example.com', '123', 50000.00),
('Attacker Account', 'attacker', 'attacker@example.com', '123', 0.00);