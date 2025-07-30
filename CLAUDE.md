# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Database Setup
```bash
# Import schema (Linux/macOS/WSL)
mysql -u root -p php_lms < database/schema.sql

# Import demo data
mysql -u root -p php_lms < database/seed.sql

# Set upload permissions
chmod 755 public/uploads/
```

### Local Development
```bash
# PHP built-in server (testing only)
php -S localhost:8000 -t public

# Or place in XAMPP htdocs and access via:
# http://localhost/php-lms/public/
```

### Docker Development
```bash
# Build and run with Docker Compose
docker-compose up -d

# Access at http://localhost:8080
```

## Architecture Overview

### MVC-Inspired Structure
- **Entry Point**: `public/index.php` - main router with session-based authentication
- **Controllers**: Handle HTTP requests and business logic in `src/controllers/`
- **Models**: Database interaction layer using MySQLi with prepared statements in `src/models/`
- **Views**: PHP templates with embedded HTML in `src/views/`

### Routing System
Simple query-parameter based routing via `public/index.php`:
- `?page=admin&section=courses&action=create&id=1`
- Admin routes require `$_SESSION['role'] === 'admin'`
- Automatic controller instantiation and method calling

### Database Architecture
- **Soft Delete Pattern**: Most tables use `archived` column instead of hard deletes
- **Hierarchical Topics**: Self-referencing `topics` table with `parent_topic_id`
- **Progress Tracking**: `progress` table tracks student completion status
- **Activity Logging**: `activities` table for audit trail
- **File Management**: Upload paths stored in database, files in `public/uploads/`

### Security Features
- Password hashing with `password_hash()` and `PASSWORD_DEFAULT`
- Prepared statements throughout for SQL injection prevention
- Session-based authentication with role checking
- File upload validation and secure storage

### Frontend Stack
- **No Build Process**: Pure vanilla HTML, CSS, and JavaScript
- **CSS Design System**: Custom properties and BEM methodology in `public/css/style.css`
- **Theme Management**: Dark/light theme with localStorage persistence
- **Accessibility**: WCAG 2.1 AA compliant with ARIA attributes

## Key Models and Controllers

### Core Models
- `User`: Authentication, role management, soft delete with archive/restore
- `Course`: Course management with semester organization
- `Topic`: Hierarchical content structure (topics → subtopics)
- `Lecture`: Content delivery with file attachments and submission requirements
- `Enrollment`: Student-course relationships
- `Submission`: Student assignment submissions with grading

### Controller Patterns
- Controllers use `require_once` for model dependencies
- Views included directly with `include __DIR__ . '/../views/...'`
- Form processing in same method as display (POST vs GET)
- Redirect-after-POST pattern for form submissions

## Database Connection
- Environment variables loaded from `.env` file
- MySQLi connection in `config/db.php`
- Global `$conn` variable used throughout models
- Default credentials: `root` user, `php_lms` database

## File Upload System
- Files stored in `public/uploads/` directory
- Database stores relative file paths
- Upload validation by file type and size
- Secure filename generation to prevent conflicts

## Development Notes
- No package manager or build tools required
- Pure PHP without frameworks or dependencies
- Database migrations in `database/migrations/`
- Seed data available in `database/seed.sql`
- XAMPP recommended for Windows development
- Archive functionality provides soft delete with restore capability