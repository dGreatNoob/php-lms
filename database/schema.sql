-- LMS Database Schema

-- Users: students and admins
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Courses
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Topics and subtopics (self-referencing)
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    parent_topic_id INT DEFAULT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    file_path VARCHAR(255),
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lectures (can be attached to topics)
CREATE TABLE lectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT,
    file_path VARCHAR(255),
    image_path VARCHAR(255),
    archived TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATETIME DEFAULT NULL,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Add submission requirement columns to lectures
ALTER TABLE lectures
ADD COLUMN requires_submission TINYINT(1) DEFAULT 0,
ADD COLUMN submission_type ENUM('text','file','both') DEFAULT 'file',
ADD COLUMN submission_instructions TEXT DEFAULT NULL;

-- Enrollments
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_enrollment (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Progress tracking
CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecture_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_progress (student_id, lecture_id),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lecture_id) REFERENCES lectures(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Create submissions table
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecture_id INT NOT NULL,
    text_submission TEXT,
    file_path VARCHAR(255),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    grade VARCHAR(10) DEFAULT NULL,
    feedback TEXT DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (lecture_id) REFERENCES lectures(id)
); 