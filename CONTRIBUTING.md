# Contributing to PHP LMS

Thank you for your interest in contributing to the PHP Learning Management System! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Issues
- Use GitHub Issues to report bugs or request features
- Search existing issues before creating new ones
- Provide detailed information including steps to reproduce
- Include environment details (OS, PHP version, etc.)

### Code Contributions

#### 1. Fork and Clone
```bash
# Fork the repository on GitHub, then clone your fork
git clone https://github.com/your-username/php-lms.git
cd php-lms

# Add the original repository as upstream
git remote add upstream https://github.com/original-owner/php-lms.git
```

#### 2. Set Up Development Environment
```bash
# Copy environment file
cp .env.example .env

# Start development environment
./scripts/dev-manager.sh up

# Initialize database
./scripts/init-db.sh development
```

#### 3. Create Feature Branch
```bash
# Always branch from dev
git checkout dev
git pull upstream dev

# Create your feature branch
git checkout -b feature/your-feature-name
```

#### 4. Make Changes
- Follow the coding standards (PSR-12)
- Write meaningful commit messages
- Keep commits focused and atomic
- Test your changes thoroughly

#### 5. Run Tests and Linting
```bash
# Install development dependencies
composer install

# Run PHP linting
find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \;

# Run code style check
composer run lint

# Fix code style issues
composer run fix
```

#### 6. Commit and Push
```bash
# Add your changes
git add .

# Commit with descriptive message
git commit -m "Add feature: description of your changes"

# Push to your fork
git push origin feature/your-feature-name
```

#### 7. Create Pull Request
- Create a pull request against the `dev` branch
- Provide a clear title and description
- Reference any related issues
- Wait for review and address feedback

## 📋 Development Guidelines

### Code Style
- Follow PSR-12 coding standards
- Use descriptive variable and function names
- Add comments for complex logic
- Keep functions small and focused
- Use type hints where appropriate

#### Example:
```php
<?php
/**
 * Validates and sanitizes user input
 *
 * @param string $input Raw user input
 * @param int $maxLength Maximum allowed length  
 * @return string Sanitized input
 * @throws InvalidArgumentException If input is invalid
 */
function validateUserInput(string $input, int $maxLength = 255): string
{
    $sanitized = trim(strip_tags($input));
    
    if (strlen($sanitized) > $maxLength) {
        throw new InvalidArgumentException("Input exceeds maximum length");
    }
    
    return $sanitized;
}
```

### Database Guidelines
- Always use prepared statements
- Use meaningful table and column names
- Add proper indexes for performance
- Document schema changes in migrations
- Test migrations with sample data

#### Example:
```php
// Good - Prepared statement
$stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND archived = 0');
$stmt->bind_param('s', $username);
$stmt->execute();

// Bad - Direct query (SQL injection risk)
$result = $conn->query("SELECT * FROM users WHERE username = '$username'");
```

### Security Guidelines
- Never trust user input
- Validate and sanitize all inputs
- Use password hashing (never store plain passwords)
- Implement proper session management
- Use HTTPS in production
- Log security events

#### Example:
```php
// Password hashing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Input validation
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    throw new InvalidArgumentException('Invalid email format');
}
```

### File Structure Guidelines
- Controllers: Handle HTTP requests and responses
- Models: Manage data and business logic
- Views: Contain presentation logic only
- Keep files focused on single responsibility

#### Directory Structure:
```
src/
├── controllers/
│   ├── AuthController.php      # Authentication logic
│   ├── CourseController.php    # Course management
│   └── UserController.php      # User management
├── models/
│   ├── User.php               # User data model
│   ├── Course.php             # Course data model
│   └── BaseModel.php          # Base model functionality
└── views/
    ├── auth/                  # Authentication views
    ├── courses/               # Course management views
    └── layouts/               # Common layouts
```

## 🧪 Testing Guidelines

### Writing Tests
- Write tests for new features
- Test both happy path and edge cases
- Mock external dependencies
- Use descriptive test names

#### Example Test:
```php
<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreationWithValidData(): void
    {
        $user = User::create('John', 'Doe', 'john@example.com', 'password123');
        
        $this->assertNotNull($user);
        $this->assertEquals('John', $user['first_name']);
        $this->assertEquals('john@example.com', $user['email']);
    }
    
    public function testUserCreationWithInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        User::create('John', 'Doe', 'invalid-email', 'password123');
    }
}
```

### Test Environment Setup
```bash
# Run tests in isolated environment
./scripts/init-db.sh testing

# Run specific test
vendor/bin/phpunit tests/UserTest.php

# Run all tests with coverage
vendor/bin/phpunit --coverage-html coverage/
```

## 🚀 Deployment Guidelines

### Environment Management
- Never commit sensitive data
- Use environment-specific configuration
- Test deployments on staging first
- Monitor application after deployment

### Database Migrations
- Create migration files for schema changes
- Test migrations in both directions (up/down)
- Include sample data for testing
- Document breaking changes

#### Migration Example:
```sql
-- migration: 003_add_user_preferences.sql
ALTER TABLE users ADD COLUMN preferences JSON DEFAULT NULL;
ALTER TABLE users ADD INDEX idx_users_email (email);

-- Add any necessary data transformations
UPDATE users SET preferences = '{}' WHERE preferences IS NULL;
```

## 📝 Documentation Guidelines

### Code Documentation
- Document public methods and classes
- Explain complex algorithms
- Provide usage examples
- Keep documentation updated

### README Updates
- Update README for new features
- Include setup instructions
- Add troubleshooting information
- Document configuration changes

## 🔄 Git Workflow

### Branch Strategy
- `main`: Production-ready code
- `staging`: Pre-production testing
- `dev`: Active development
- `feature/*`: Individual features
- `bugfix/*`: Bug fixes
- `hotfix/*`: Emergency production fixes

### Commit Message Format
```
type(scope): brief description

Detailed explanation of changes if needed

Fixes #123
Closes #456
```

#### Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Test additions/changes
- `chore`: Maintenance tasks

#### Examples:
```
feat(auth): add password reset functionality

Add password reset via email with secure tokens.
Includes email templates and token expiration.

Fixes #45

fix(upload): validate file types more strictly

Prevent potential security issues with file uploads
by checking both extension and MIME type.

Closes #67
```

## 🐛 Bug Reporting

### Issue Template
When reporting bugs, include:

1. **Environment Details**
   - PHP version
   - MySQL version
   - Operating system
   - Browser (if applicable)

2. **Steps to Reproduce**
   - Detailed steps to recreate the issue
   - Expected behavior
   - Actual behavior

3. **Additional Information**
   - Error messages
   - Log files
   - Screenshots (if applicable)

### Example Bug Report:
```markdown
**Environment:**
- PHP 8.1.0
- MySQL 8.0.28
- Ubuntu 22.04
- Chrome 98.0

**Steps to Reproduce:**
1. Log in as student
2. Navigate to course "Mathematics 101"
3. Click on "Submit Assignment"
4. Upload a PDF file

**Expected:** File uploads successfully
**Actual:** Error message "File type not allowed"

**Error Log:**
```
[2024-01-15 10:30:45] ERROR: Invalid file type: application/pdf
```

## 🎯 Feature Requests

### Before Submitting
- Check if feature already exists
- Search existing feature requests
- Consider if it fits project scope
- Think about implementation complexity

### Feature Request Template
```markdown
**Feature Description:**
Brief description of the requested feature

**Use Case:**
Why is this feature needed? What problem does it solve?

**Proposed Solution:**
How should this feature work?

**Alternatives Considered:**
Other ways to solve this problem

**Additional Context:**
Any additional information, mockups, or examples
```

## 🏅 Recognition

### Contributors
We recognize contributors in several ways:
- Credit in release notes
- Contributor list in README
- GitHub contributor graphs
- Optional: contributor profiles

### Contribution Types
We value all types of contributions:
- Code contributions
- Bug reports
- Documentation improvements
- Testing and feedback
- Feature suggestions
- Community support

## 📞 Getting Help

### Resources
- [Development Setup Guide](docs/development.md)
- [API Documentation](docs/api.md)
- [Troubleshooting Guide](docs/troubleshooting.md)

### Communication
- GitHub Issues for bugs and features
- GitHub Discussions for questions
- Pull Request comments for code review

### Code Review Process
1. Automated checks must pass
2. At least one maintainer review
3. Address feedback promptly
4. Squash commits if requested
5. Maintainer will merge when ready

---

Thank you for contributing to PHP LMS! 🙏

Your contributions help make this project better for everyone in the learning community.