-- Increase the size of the semester column in the courses table
ALTER TABLE courses MODIFY semester VARCHAR(50) NOT NULL; 