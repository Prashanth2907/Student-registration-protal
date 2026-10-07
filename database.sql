CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_code VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    dob DATE NULL,
    gender VARCHAR(20) NULL,
    qualification VARCHAR(100) NULL,
    interests VARCHAR(255) NULL,
    class VARCHAR(100) NULL,
    subject VARCHAR(100) NULL,
    marks INT NULL,
    aadhaar_file VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (user_code, name, email, password, role, dob, gender, qualification, interests, class, subject, marks, aadhaar_file)
VALUES 
('ADM001', 'Administrator', 'admin@example.com', '$2y$10$wT8hTqF6oX67c5kS/hY.ve9VfFomJ0K7W1R4U1N1P2jW4eL5.nUe6', 'admin', '1985-01-01', 'Other', 'Master\'s', 'Coding', 'Admin Staff', 'Administration', 100, NULL),
('STU001', 'John Doe', 'john.doe@example.com', '$2y$10$wT8hTqF6oX67c5kS/hY.ve9VfFomJ0K7W1R4U1N1P2jW4eL5.nUe6', 'student', '1994-03-20', 'Male', 'Bachelors', 'Coding,Design', 'form-control', 'form-control', 1200, 'sample_aadhaar.pdf'),
('STU002', 'Jane Smith', 'jane.smith@example.com', '$2y$10$wT8hTqF6oX67c5kS/hY.ve9VfFomJ0K7W1R4U1N1P2jW4eL5.nUe6', 'student', '1998-07-15', 'Female', 'Master\'s', 'Design,Gaming', 'Class 12', 'Physics', 1150, 'sample_aadhaar.pdf'),
('STU003', 'Alex Johnson', 'alex.j@example.com', '$2y$10$wT8hTqF6oX67c5kS/hY.ve9VfFomJ0K7W1R4U1N1P2jW4eL5.nUe6', 'student', '2004-11-05', 'Male', 'High School', 'Sports,Coding', 'Class 10', 'Mathematics', 980, 'sample_aadhaar.pdf');
