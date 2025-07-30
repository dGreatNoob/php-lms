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
    semester VARCHAR(50) NOT NULL,
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
CREATE TABLE `lectures` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `topic_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` text,
    `attachment` varchar(255) DEFAULT NULL,
    `allow_submissions` tinyint(1) NOT NULL DEFAULT '0',
    `due_date` datetime DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `archived` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `topic_id` (`topic_id`),
    CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add submission requirement columns to lectures
-- ALTER TABLE `lectures`
--     DROP COLUMN `file_path`,
--     DROP COLUMN `image_path`,
--     DROP COLUMN `requires_submission`,
--     DROP COLUMN `submission_type`,
--     DROP COLUMN `submission_instructions`;

-- ALTER TABLE `lectures`
--     ADD COLUMN `attachment` varchar(255) DEFAULT NULL AFTER `content`,
--     ADD COLUMN `allow_submissions` tinyint(1) NOT NULL DEFAULT '0' AFTER `attachment`,
--     ADD COLUMN `due_date` datetime DEFAULT NULL AFTER `allow_submissions`;

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

-- Activity logging table
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_type ENUM('create', 'update', 'delete', 'archive', 'restore', 'enroll', 'unenroll', 'submit', 'grade') NOT NULL,
    entity_type ENUM('course', 'topic', 'lecture', 'user', 'enrollment', 'submission') NOT NULL,
    entity_id INT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;