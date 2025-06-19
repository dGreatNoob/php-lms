# PHP-LMS (Learning Management System) Rewrite

A modern, secure, and portable Learning Management System built with PHP and MySQL. Supports both students and admins, with features for course, topic, and lesson management, progress tracking, archiving, and file/image uploads.

## Features
- Secure login/registration for students and admins (first name, last name, username, password)
- Admin management of courses, topics, subtopics, enrollments
- File/image upload for topics and lessons
- Lesson progress tracking per student
- Archive and restore lessons
- Content organized by semester
- Portable configuration using `.env`
- MVC-inspired structure for maintainability

## Project Structure
```
php-lms/
  ├── config/
  │     └── db.php
  ├── public/
  │     ├── css/
  │     ├── js/
  │     ├── uploads/
  │     └── index.php
  ├── src/
  │     ├── controllers/
  │     ├── models/
  │     ├── views/
  │     └── helpers/
  ├── database/
  │     └── schema.sql
  ├── includes/
  │     ├── header.php
  │     └── footer.php
  ├── .env.example
  └── readme.md
```

## Setup Instructions

### 1. Clone the Repository
```
git clone <your-repo-url>
cd php-lms
```

### 2. Create the Database
- Import `database/schema.sql` into your MySQL server.

### 3. Configure Environment Variables
- Copy `.env.example` to `.env` and fill in your database credentials:
```
cp .env.example .env
```

### 4. Set Up on Windows (XAMPP)
- Place the project in `C:/xampp/htdocs/php-lms`
- Start Apache and MySQL from XAMPP Control Panel
- Access at `http://localhost/php-lms/public/`

### 5. Set Up on Linux (LAMP)
- Place the project in `/var/www/html/php-lms`
- Ensure Apache and MySQL are running
- Access at `http://localhost/php-lms/public/`

### 6. File Uploads
- Ensure `public/uploads/` is writable by the web server.

## Security Notes
- Passwords are hashed using PHP's `password_hash`.
- All database queries use prepared statements.
- CSRF protection is implemented for all forms.

## Contribution
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](LICENSE)
