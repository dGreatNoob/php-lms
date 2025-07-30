# PHP Learning Management System (LMS)

A modern, secure, and scalable Learning Management System built with vanilla PHP, featuring Docker containerization, CI/CD pipelines, and multi-environment support.

## ✨ Features

### 🎓 Learning Management
- **Course Management**: Create, edit, and organize courses by semester
- **Topic Hierarchy**: Nested topics and subtopics for organized content
- **Lecture System**: Rich content with file/image uploads and submission requirements
- **Progress Tracking**: Monitor student progress through lectures and assignments
- **Submission System**: Text and file submissions with grading capabilities

### 👥 User Management
- **Role-based Access**: Separate interfaces for students and administrators
- **Secure Authentication**: Password hashing and session management
- **User Enrollment**: CSV import and manual enrollment management
- **Archive System**: Soft delete with restore functionality

### 🐳 Modern Infrastructure
- **Dockerized**: Fully containerized application with Docker Compose
- **Multi-Environment**: Development, staging, and production configurations
- **CI/CD Pipeline**: Automated testing and deployment with GitHub Actions
- **Environment Management**: Scripts for seamless environment switching

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose
- Git
- (Optional) PHP 8.1+ for local development without Docker

### 1. Clone and Setup
```bash
git clone <your-repo-url>
cd php-lms

# Copy environment file
cp .env.example .env

# Start development environment
./scripts/dev-manager.sh up
```

### 2. Initialize Database
```bash
# Initialize database with schema and seed data
./scripts/init-db.sh development
```

### 3. Access the Application
- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Database**: localhost:3306

### Default Credentials
After seeding, you can log in with:
- **Admin**: username/password from seed data
- **Student**: username/password from seed data

## 🛠️ Development

### Environment Management

#### Development Environment
```bash
./scripts/dev-manager.sh up      # Start development environment
./scripts/dev-manager.sh down    # Stop development environment
./scripts/dev-manager.sh logs    # View logs
./scripts/dev-manager.sh status  # Check status
./scripts/dev-manager.sh seed    # Seed database
./scripts/dev-manager.sh reset   # Reset database
```

#### Staging Environment
```bash
./scripts/staging-manager.sh deploy    # Deploy to staging
./scripts/staging-manager.sh status    # Check staging status
./scripts/staging-manager.sh backup    # Create database backup
./scripts/staging-manager.sh rollback  # Rollback to previous backup
```

#### Production Environment
```bash
./scripts/production-manager.sh deploy    # Deploy to production (with confirmations)
./scripts/production-manager.sh status    # Check production status
./scripts/production-manager.sh backup    # Create production backup
./scripts/production-manager.sh rollback  # Emergency rollback
```

### Development Workflow

1. **Feature Development**
   ```bash
   git checkout dev
   git pull origin dev
   git checkout -b feature/your-feature-name
   
   # Make your changes
   ./scripts/dev-manager.sh up
   # Test your changes
   
   git add .
   git commit -m "Add your feature"
   git push origin feature/your-feature-name
   ```

2. **Testing on Staging**
   ```bash
   # Merge to staging branch
   git checkout staging
   git merge feature/your-feature-name
   git push origin staging
   
   # Automatic deployment via GitHub Actions
   ```

3. **Production Deployment**
   ```bash
   # Merge to main branch
   git checkout main
   git merge staging
   git push origin main
   
   # Automatic deployment via GitHub Actions
   ```

## 🏗️ Architecture

### Project Structure
```
php-lms/
├── .github/workflows/          # CI/CD pipelines
├── config/                     # Configuration files
│   └── db.php                 # Database configuration
├── database/                   # Database files
│   ├── migrations/            # Database migrations
│   ├── schema.sql            # Database schema
│   └── seed.sql              # Demo data
├── docker/                     # Docker configuration
│   └── apache/               # Apache virtual host config
├── public/                     # Web root
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   ├── uploads/              # File uploads
│   └── index.php            # Application entry point
├── scripts/                    # Management scripts
│   ├── dev-manager.sh        # Development environment manager
│   ├── staging-manager.sh    # Staging environment manager
│   ├── production-manager.sh # Production environment manager
│   └── init-db.sh           # Database initialization
├── src/                        # Application source code
│   ├── controllers/          # MVC controllers
│   ├── models/              # Data models
│   └── views/               # View templates
├── storage/                    # Application storage
│   ├── logs/                # Application logs
│   └── sessions/            # Session files
├── docker-compose.yml          # Development Docker Compose
├── docker-compose.staging.yml  # Staging Docker Compose
├── docker-compose.production.yml # Production Docker Compose
├── Dockerfile                  # Docker image definition
└── composer.json              # PHP dependencies
```

### Technology Stack
- **Backend**: PHP 8.1+ with MySQLi
- **Database**: MySQL 8.0
- **Frontend**: Vanilla HTML5, CSS3, JavaScript
- **Infrastructure**: Docker, Docker Compose
- **CI/CD**: GitHub Actions
- **Web Server**: Apache 2.4

## 🔧 Configuration

### Environment Variables

#### Development (.env)
```env
APP_ENV=development
APP_DEBUG=true
BASE_URL=http://localhost:8080
DB_HOST=db
DB_NAME=php_lms
DB_USER=root
DB_PASS=example
```

#### Staging (.env.staging)
```env
APP_ENV=staging
APP_DEBUG=false
BASE_URL=https://staging.yourdomain.com
DB_HOST=db
DB_NAME=php_lms_staging
DB_USER=lms_user
DB_PASS=staging_secure_password
```

#### Production (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://yourdomain.com
DB_HOST=db
DB_NAME=php_lms_prod
DB_USER=lms_prod_user
DB_PASS=production_super_secure_password
FORCE_HTTPS=true
SECURE_COOKIES=true
```

## 🔄 CI/CD Pipeline

### GitHub Actions Workflows

1. **Continuous Integration** (`.github/workflows/ci.yml`)
   - Runs on every push to `dev`, `staging`, `main`
   - PHP linting and code style checks
   - Automated testing with PHPUnit
   - Database setup and testing

2. **Staging Deployment** (`.github/workflows/deploy-staging.yml`)
   - Triggers on push to `staging` branch
   - Builds Docker image
   - Deploys to staging environment
   - Health checks and notifications

3. **Production Deployment** (`.github/workflows/deploy-production.yml`)
   - Triggers on push to `main` branch
   - Manual confirmation required
   - Database backup before deployment
   - Zero-downtime deployment
   - Health checks and rollback capability

### Required GitHub Secrets

#### Staging
- `STAGING_HOST` - Staging server hostname
- `STAGING_USER` - SSH username
- `STAGING_SSH_KEY` - SSH private key
- `STAGING_BASE_URL` - Staging application URL
- `STAGING_DB_NAME` - Database name
- `STAGING_DB_USER` - Database username
- `STAGING_DB_PASS` - Database password

#### Production
- `PROD_HOST` - Production server hostname
- `PROD_USER` - SSH username
- `PROD_SSH_KEY` - SSH private key
- `PROD_BASE_URL` - Production application URL
- `PROD_DB_NAME` - Database name
- `PROD_DB_USER` - Database username
- `PROD_DB_PASS` - Database password

#### Optional
- `SLACK_WEBHOOK_URL` - Slack notifications

## 🔒 Security

### Implemented Security Features
- **Password Security**: Bcrypt hashing with configurable rounds
- **SQL Injection Protection**: Prepared statements throughout
- **Session Security**: Secure session configuration
- **File Upload Security**: Type and size validation
- **Environment-based Security**: Debug mode disabled in production
- **HTTPS Enforcement**: Configurable for production
- **Input Validation**: Server-side validation for all inputs

### Security Recommendations
- Keep dependencies updated
- Use strong passwords for database users
- Enable HTTPS in production
- Regularly backup databases
- Monitor application logs
- Implement rate limiting
- Use secure headers

## 🧪 Testing

### Running Tests
```bash
# Install development dependencies
composer install

# Run PHP linting
composer run lint

# Run code style fixes
composer run fix

# Run PHPUnit tests (when implemented)
composer run test
```

### Database Testing
```bash
# Create test database
./scripts/init-db.sh testing

# Reset test database
./scripts/dev-manager.sh reset
```

## 📝 Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Comment complex logic
- Write tests for new features

### Pull Request Process
1. Fork the repository
2. Create a feature branch from `dev`
3. Make your changes
4. Ensure tests pass
5. Submit a pull request to `dev` branch

## 🔮 Future Improvements

### Short Term
- [ ] Implement comprehensive PHPUnit tests
- [ ] Add email notifications
- [ ] Implement caching layer
- [ ] Add API endpoints
- [ ] Mobile app support

### Long Term
- [ ] Migration to modern PHP framework (Laravel/Symfony)
- [ ] Microservices architecture
- [ ] Advanced analytics and reporting
- [ ] Integration with external LMS standards (SCORM)
- [ ] Multi-tenant support

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Documentation
- [Development Setup](docs/development.md)
- [Deployment Guide](docs/deployment.md)
- [API Documentation](docs/api.md)
- [Troubleshooting](docs/troubleshooting.md)

### Getting Help
- Create an issue for bugs or feature requests
- Check existing issues for solutions
- Review the troubleshooting guide

## 🙏 Acknowledgments

- PHP community for excellent documentation
- Docker for containerization platform
- GitHub Actions for CI/CD capabilities
- Contributors and testers

---

**Happy Learning! 🎓**