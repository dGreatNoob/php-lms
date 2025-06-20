# PHP-LMS (Learning Management System)

## 🪟 Windows Installation & Setup

### 1. Install Prerequisites
- **PHP 7.4+**: [Download PHP for Windows](https://windows.php.net/download/)
- **MySQL 5.7+** or **MariaDB 10.2+**: [Download MySQL](https://dev.mysql.com/downloads/installer/) or [Download MariaDB](https://mariadb.org/download/)
- **Web Server**: [XAMPP](https://www.apachefriends.org/index.html) (includes Apache, PHP, MySQL) is recommended for beginners.

### 2. Clone the Repository
```sh
git clone <your-repo-url>
cd php-lms
```

### 3. Set Up the Database
- Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
- Open **phpMyAdmin** (usually at http://localhost/phpmyadmin).
- Create a new database, e.g., `php_lms`.
- Import the schema:
  - Click the database name.
  - Go to the **Import** tab.
  - Choose `database/schema.sql` and click **Go**.

### 4. Configure Environment Variables
Create a file named `.env` in the project root with the following content:
```
DB_HOST=localhost
DB_NAME=php_lms
DB_USER=root
DB_PASS=
```
- Adjust `DB_USER` and `DB_PASS` if your MySQL setup uses a different username or password.

### 5. Set Permissions for Uploads
- In Windows, right-click the `public/uploads` folder, go to **Properties > Security**, and ensure your web server user (e.g., `www-data` or `IIS_IUSRS`) has **write** permission.

### 6. Run the Application
- Place the project folder inside your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\php-lms`).
- Access the app at: [http://localhost/php-lms/public/](http://localhost/php-lms/public/)

---

## 🚀 Quick Start Commands (Linux/macOS/WSL)

```sh
# Clone the repository
git clone <your-repo-url>
cd php-lms

# Set up the database (replace credentials as needed)
mysql -u root -p < database/schema.sql

# Create .env file with your DB credentials
echo "DB_HOST=localhost\nDB_NAME=php_lms\nDB_USER=root\nDB_PASS=" > .env

# Set permissions for uploads (Linux/macOS)
chmod 755 public/uploads/

# Start your web server and access the app
# Example for PHP built-in server (for testing only):
php -S localhost:8000 -t public
# Then visit http://localhost:8000/
```

---

A modern, secure, and accessible Learning Management System built with vanilla PHP, MySQL, HTML, CSS, and JavaScript. Features a clean, responsive design system with WCAG 2.1 AA compliance.

## ✨ Features

### 🎓 Learning Management
- **Course Management**: Create, edit, and organize courses by semester
- **Topic Hierarchy**: Nested topics and subtopics for organized content
- **Lecture System**: Rich content with file/image uploads and submission requirements
- **Progress Tracking**: Monitor student progress through lectures and assignments

### 👥 User Management
- **Role-based Access**: Separate interfaces for students and administrators
- **Secure Authentication**: Password hashing and session management
- **User Enrollment**: CSV import and manual enrollment management
- **Archive System**: Soft delete with restore functionality

### 🎨 Modern Design
- **Vanilla CSS Design System**: No frameworks, just clean, semantic CSS
- **Responsive Layout**: Mobile-first design that works on all devices
- **Dark/Light Theme**: User preference with localStorage persistence
- **Accessibility**: WCAG 2.1 AA compliant with keyboard navigation
- **Interactive Components**: Modals, tooltips, toast notifications

### 📁 File Management
- **Upload System**: Support for documents, images, and multimedia
- **Submission Handling**: Text and file submissions with feedback
- **Download Links**: Secure file access with proper permissions

## 🏗️ Architecture

### Frontend Stack
- **HTML5**: Semantic markup with ARIA attributes
- **Vanilla CSS**: Custom design system with CSS custom properties
- **Vanilla JavaScript**: No frameworks, just modern ES6+ features
- **Responsive Design**: Mobile-first with CSS Grid and Flexbox

### Backend Stack
- **PHP**: Modern PHP with OOP and MVC-inspired structure
- **MySQL**: Relational database with proper indexing
- **Security**: Prepared statements, password hashing, CSRF protection

## 📁 Project Structure

```
php-lms/
├── config/
│   └── db.php              # Database configuration
├── database/
│   └── schema.sql          # Database schema
├── public/
│   ├── css/
│   │   └── style.css       # Vanilla CSS design system
│   ├── js/
│   │   ├── theme.js        # Theme management
│   │   └── script.js       # Enhanced functionality
│   ├── uploads/            # File uploads directory
│   └── index.php           # Application entry point
├── src/
│   ├── controllers/        # MVC controllers
│   ├── models/            # Data models
│   └── views/             # View templates
├── .env.example           # Environment configuration template
└── README.md
```

## 🎨 Design System

### CSS Architecture
- **BEM Methodology**: Consistent class naming conventions
- **CSS Custom Properties**: Theme variables for easy customization
- **Component-based**: Reusable UI components
- **Utility Classes**: Helper classes for common patterns

### Accessibility Features
- **Semantic HTML**: Proper heading hierarchy and landmarks
- **ARIA Labels**: Screen reader support
- **Keyboard Navigation**: Full keyboard accessibility
- **Focus Management**: Visible focus indicators
- **Color Contrast**: WCAG AA compliant color ratios

### Responsive Breakpoints
- **Mobile**: 320px - 640px
- **Tablet**: 641px - 1024px
- **Desktop**: 1025px+

## 🔧 Development

### Adding New Views
1. Create view file in `src/views/`
2. Use the design system classes
3. Include proper HTML structure
4. Add accessibility attributes

### Styling Guidelines
- Use CSS custom properties for theming
- Follow BEM naming conventions
- Ensure responsive design
- Test accessibility features

### JavaScript Features
- Theme management with localStorage
- Form validation and error handling
- Modal and toast notifications
- Table sorting and filtering
- File upload enhancements

## 🔒 Security Features

- **Password Hashing**: PHP `password_hash()` with bcrypt
- **SQL Injection Protection**: Prepared statements throughout
- **CSRF Protection**: Form token validation
- **File Upload Security**: Type and size validation
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation for all inputs

## 📱 Browser Support

- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+
- **Accessibility**: Screen readers, keyboard navigation, high contrast

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Follow the coding standards
4. Test accessibility features
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Inter font family for typography
- Modern CSS techniques and best practices
- WCAG 2.1 guidelines for accessibility
