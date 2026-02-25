-- CREATE DATABASE IF NOT EXISTS community_complaints;
-- USE community_complaints;
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    priority ENUM('Low', 'Medium', 'High'),
    category VARCHAR(50),
    status ENUM('Pending Review', 'In Progress', 'Resolved') DEFAULT 'Pending Review',
    reporter_name VARCHAR(100),
    location VARCHAR(100),
    reported_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE complaints ADD user_id INT NOT NULL;
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    commenter_name VARCHAR(100) NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);
ALTER TABLE complaints ADD evidence VARCHAR(255) NULL;
ALTER TABLE complaints 
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE users 
ADD COLUMN full_name VARCHAR(255),
ADD COLUMN phone_number VARCHAR(15),
ADD COLUMN address TEXT,
ADD COLUMN profile_picture VARCHAR(255);
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    complaint_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (complaint_id) REFERENCES complaints(id)
);
ALTER TABLE complaints 
ADD department VARCHAR(255);
ALTER TABLE complaints ADD comments TEXT;
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    complaint_id INT,
    message TEXT,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);
ALTER TABLE complaints 
ADD latitude DECIMAL(10, 8), 
ADD longitude DECIMAL(11, 8);
ALTER TABLE users
ADD reset_token VARCHAR(6),
ADD token_expiry DATETIME;











