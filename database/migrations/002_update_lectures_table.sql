ALTER TABLE `lectures`
  DROP COLUMN `file_path`,
  DROP COLUMN `image_path`,
  DROP COLUMN `requires_submission`,
  DROP COLUMN `submission_type`,
  DROP COLUMN `submission_instructions`;

ALTER TABLE `lectures`
  ADD COLUMN `attachment` varchar(255) DEFAULT NULL AFTER `content`,
  ADD COLUMN `allow_submissions` tinyint(1) NOT NULL DEFAULT '0' AFTER `attachment`;
-- Note: The due_date column was already present, so it is not added again. 