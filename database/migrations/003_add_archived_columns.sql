-- Add archived column to users table for soft delete functionality
ALTER TABLE users ADD COLUMN archived TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE users ADD INDEX idx_users_archived (archived);

-- Add archived column to courses table for consistency
ALTER TABLE courses ADD COLUMN archived TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE courses ADD INDEX idx_courses_archived (archived);

-- Add archived column to topics table for consistency  
ALTER TABLE topics ADD COLUMN archived TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE topics ADD INDEX idx_topics_archived (archived);

-- Update existing records to be non-archived by default
UPDATE users SET archived = 0 WHERE archived IS NULL;
UPDATE courses SET archived = 0 WHERE archived IS NULL;
UPDATE topics SET archived = 0 WHERE archived IS NULL;