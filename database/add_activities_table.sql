-- Add Activity logging table
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_type ENUM('create', 'update', 'delete', 'archive', 'restore', 'enroll', 'unenroll', 'submit', 'grade') NOT NULL,
    entity_type ENUM('course', 'topic', 'lecture', 'user', 'enrollment', 'submission') NOT NULL,
    entity_id INT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB; 