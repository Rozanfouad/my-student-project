-- Create database
CREATE DATABASE IF NOT EXISTS news_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE news_system;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    details TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    user_id INT NOT NULL,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Sample categories
INSERT INTO categories (name) VALUES ('Sports'), ('Politics'), ('Economy');
