CREATE DATABASE IF NOT EXISTS property_lab;
USE property_lab;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE properties(
 id INT PRIMARY KEY AUTO_INCREMENT,
 title VARCHAR(200),
 city VARCHAR(100),
 price VARCHAR(50)
);

INSERT INTO properties(title,city,price) VALUES
('Modern Apartment','Dhaka','25000 BDT'),
('Luxury Villa','Chattogram','60000 BDT');

CREATE TABLE comments(
 id INT PRIMARY KEY AUTO_INCREMENT,
 comment_text TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
