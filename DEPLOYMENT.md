# Deployment Guide

This guide provides step-by-step instructions for deploying the PHP LMS to different environments.

## 🚀 Quick Deployment

### Local Development
```bash
# Clone repository
git clone <your-repo-url>
cd php-lms

# Start development environment
./scripts/dev-manager.sh up

# Initialize database
./scripts/init-db.sh development

# Access application at http://localhost:8080
```

### Staging Deployment
```bash
# Deploy to staging
./scripts/staging-manager.sh deploy

# Check status
./scripts/staging-manager.sh status

# View logs
./scripts/staging-manager.sh logs
```

### Production Deployment
```bash
# Deploy to production (requires confirmation)
./scripts/production-manager.sh deploy

# Check health
./scripts/production-manager.sh health

# Create backup
./scripts/production-manager.sh backup
```

## 🔧 Server Setup

### Prerequisites
- Docker 20.10+
- Docker Compose 2.0+
- Git
- SSH access to servers

### Staging Server Setup
```bash
# Create application directory
sudo mkdir -p /var/www/php-lms-staging
sudo chown $USER:$USER /var/www/php-lms-staging

# Clone repository
cd /var/www/php-lms-staging
git clone <your-repo-url> .

# Configure environment
cp .env.staging.example .env.staging
nano .env.staging  # Edit with your staging credentials

# Set proper permissions
chmod +x scripts/*.sh
mkdir -p storage/logs storage/sessions backups/staging
chmod 755 storage/logs storage/sessions backups/staging

# Deploy
./scripts/staging-manager.sh deploy
```

### Production Server Setup
```bash
# Create application directory
sudo mkdir -p /var/www/php-lms-production
sudo chown $USER:$USER /var/www/php-lms-production

# Clone repository
cd /var/www/php-lms-production
git clone <your-repo-url> .

# Configure environment
cp .env.production.example .env.production
nano .env.production  # Edit with your production credentials

# Set proper permissions
chmod +x scripts/*.sh
mkdir -p storage/logs storage/sessions backups/production
chmod 755 storage/logs storage/sessions backups/production

# Deploy
./scripts/production-manager.sh deploy
```

## ⚙️ Environment Configuration

### Development (.env)
```env
APP_ENV=development
APP_DEBUG=true
BASE_URL=http://localhost:8080
DB_HOST=db
DB_NAME=php_lms
DB_USER=root
DB_PASS=example
```

### Staging (.env.staging)
```env
APP_ENV=staging
APP_DEBUG=false
BASE_URL=https://staging.yourdomain.com
DB_HOST=db
DB_NAME=php_lms_staging
DB_USER=lms_staging_user
DB_PASS=secure_staging_password
FORCE_HTTPS=true
```

### Production (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://yourdomain.com
DB_HOST=db
DB_NAME=php_lms_prod
DB_USER=lms_prod_user
DB_PASS=very_secure_production_password
FORCE_HTTPS=true
SECURE_COOKIES=true
```

## 🔄 CI/CD Configuration

### GitHub Secrets
Configure these secrets in your GitHub repository:

#### Staging Secrets
- `STAGING_HOST`: Staging server IP/hostname
- `STAGING_USER`: SSH username
- `STAGING_SSH_KEY`: SSH private key
- `STAGING_BASE_URL`: https://staging.yourdomain.com
- `STAGING_DB_NAME`: Database name
- `STAGING_DB_USER`: Database username
- `STAGING_DB_PASS`: Database password

#### Production Secrets
- `PROD_HOST`: Production server IP/hostname
- `PROD_USER`: SSH username
- `PROD_SSH_KEY`: SSH private key
- `PROD_BASE_URL`: https://yourdomain.com
- `PROD_DB_NAME`: Database name
- `PROD_DB_USER`: Database username
- `PROD_DB_PASS`: Database password

#### Optional Secrets
- `SLACK_WEBHOOK_URL`: For deployment notifications

### SSH Key Setup
```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"

# Copy public key to servers
ssh-copy-id user@staging-server
ssh-copy-id user@production-server

# Add private key to GitHub Secrets
cat ~/.ssh/id_rsa | pbcopy  # Copy to clipboard
```

## 🗄️ Database Management

### Manual Database Operations
```bash
# Connect to database
docker-compose exec db mysql -u root -p

# Import SQL file
docker-compose exec -T db mysql -u root -p database_name < backup.sql

# Create backup
docker-compose exec db mysqldump -u root -p database_name > backup.sql

# Run migrations
php database/migrate.php
```

### Automated Backups
```bash
# Add to crontab for daily backups
0 2 * * * /var/www/php-lms-production/scripts/production-manager.sh backup

# Clean old backups (keep 30 days)
0 3 * * 0 find /var/www/php-lms-production/backups -name "*.sql.gz" -mtime +30 -delete
```

## 🔒 Security Checklist

### Pre-Deployment Security
- [ ] Update all environment passwords
- [ ] Enable HTTPS in production
- [ ] Configure secure session settings
- [ ] Set up firewall rules
- [ ] Enable fail2ban or similar
- [ ] Configure log rotation
- [ ] Set up monitoring

### Post-Deployment Security
- [ ] Test health check endpoint
- [ ] Verify HTTPS certificates
- [ ] Check file permissions
- [ ] Test backup/restore procedures
- [ ] Monitor security logs
- [ ] Update default passwords
- [ ] Test rate limiting

## 📊 Monitoring Setup

### Health Checks
```bash
# Check application health
curl https://yourdomain.com/health.php

# Monitor with external service
# Configure uptimerobot.com or similar
```

### Log Monitoring
```bash
# Monitor application logs
tail -f storage/logs/app_$(date +%Y-%m-%d).log

# Monitor security logs
tail -f storage/logs/security.log

# Monitor error logs
tail -f storage/logs/error_$(date +%Y-%m-%d).log
```

### Automated Monitoring
```bash
# Add to crontab for log alerts
*/15 * * * * grep -i "error\|critical" /var/www/php-lms-production/storage/logs/error_$(date +%Y-%m-%d).log | tail -n 10 | mail -s "LMS Errors" admin@yourdomain.com
```

## 🚨 Troubleshooting

### Common Issues

#### Database Connection Failed
```bash
# Check database container
docker-compose ps db

# Check database logs
docker-compose logs db

# Restart database
docker-compose restart db
```

#### File Permission Issues
```bash
# Fix upload permissions
chmod 755 public/uploads/
chown www-data:www-data public/uploads/

# Fix storage permissions
chmod 755 storage/logs storage/sessions
chown www-data:www-data storage/logs storage/sessions
```

#### SSL Certificate Issues
```bash
# Check certificate expiry
openssl x509 -in /path/to/certificate.crt -text -noout | grep "Not After"

# Renew Let's Encrypt certificate
certbot renew --nginx
```

### Recovery Procedures

#### Database Recovery
```bash
# Stop application
docker-compose down

# Restore from backup
docker-compose up -d db
sleep 30
docker-compose exec -T db mysql -u root -p database_name < backup.sql

# Start application
docker-compose up -d
```

#### Complete System Recovery
```bash
# Restore from git
git fetch origin
git reset --hard origin/main

# Rebuild containers
docker-compose down
docker-compose up -d --build

# Restore database
./scripts/production-manager.sh rollback
```

## 📈 Performance Tuning

### Docker Optimization
```bash
# Enable Docker BuildKit
export DOCKER_BUILDKIT=1

# Use multi-stage builds
# (Already implemented in Dockerfile)

# Optimize MySQL settings
# Add to docker-compose.yml:
# environment:
#   - MYSQL_INNODB_BUFFER_POOL_SIZE=256M
```

### PHP Optimization
```bash
# Enable OPcache in production
# Add to Dockerfile:
# RUN docker-php-ext-install opcache
```

### Web Server Optimization
```bash
# Enable compression in Apache
# Add to apache config:
# LoadModule deflate_module modules/mod_deflate.so
# SetOutputFilter DEFLATE
```

## 🔄 Maintenance

### Regular Maintenance Tasks
```bash
# Weekly tasks
./scripts/production-manager.sh backup
docker system prune -f

# Monthly tasks
# Update dependencies
composer update
docker-compose pull
docker-compose up -d --build

# Quarterly tasks
# Review security logs
# Update SSL certificates
# Performance analysis
```

### Updates and Patches
```bash
# Update application
git pull origin main
docker-compose down
docker-compose up -d --build
php database/migrate.php

# Update system packages
sudo apt update && sudo apt upgrade
```

---

For more detailed information, see:
- [README.md](README.md) - General information
- [CONTRIBUTING.md](CONTRIBUTING.md) - Development guidelines
- [Security Documentation](docs/security.md) - Security best practices