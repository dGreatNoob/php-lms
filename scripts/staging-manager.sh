#!/bin/bash

# Staging Environment Manager
# Usage: ./scripts/staging-manager.sh [deploy|status|logs|rollback]

set -e

COMPOSE_FILE="docker-compose.staging.yml"
ENV_FILE=".env.staging"
BACKUP_DIR="backups/staging"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        log_error "Docker is not running. Please start Docker and try again."
        exit 1
    fi
}

# Check if environment file exists
check_env() {
    if [ ! -f "$ENV_FILE" ]; then
        log_error "Staging environment file $ENV_FILE not found."
        log_info "Please create it with proper staging credentials."
        exit 1
    fi
}

# Create backup directory
ensure_backup_dir() {
    mkdir -p "$BACKUP_DIR"
}

# Create database backup
backup_database() {
    ensure_backup_dir
    local backup_file="$BACKUP_DIR/staging_backup_$(date +%Y%m%d_%H%M%S).sql"
    
    log_info "Creating database backup..."
    
    # Get database credentials from .env.staging
    DB_NAME=$(grep "^DB_NAME=" $ENV_FILE | cut -d'=' -f2)
    DB_USER=$(grep "^DB_USER=" $ENV_FILE | cut -d'=' -f2)
    DB_PASS=$(grep "^DB_PASS=" $ENV_FILE | cut -d'=' -f2)
    
    if docker-compose -f $COMPOSE_FILE exec -T db mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_file"; then
        log_success "Database backup created: $backup_file"
        return 0
    else
        log_error "Failed to create database backup"
        return 1
    fi
}

# Deploy to staging
deploy_staging() {
    log_info "Deploying to staging environment..."
    check_docker
    check_env
    
    # Backup database before deployment
    if docker-compose -f $COMPOSE_FILE ps db | grep -q "Up"; then
        backup_database
    fi
    
    # Pull latest changes
    log_info "Pulling latest changes from staging branch..."
    git fetch origin
    git checkout staging
    git pull origin staging
    
    # Build and deploy
    log_info "Building and starting staging containers..."
    docker-compose -f $COMPOSE_FILE down
    docker-compose -f $COMPOSE_FILE up -d --build
    
    # Wait for services to start
    log_info "Waiting for services to start..."
    sleep 30
    
    # Check health
    check_staging_health
}

# Check staging health
check_staging_health() {
    log_info "Checking staging health..."
    
    # Check if containers are running
    if docker-compose -f $COMPOSE_FILE ps | grep -q "Up"; then
        log_success "Staging containers are running"
        
        # Try to access the application (if URL is configured)
        STAGING_URL=$(grep "^BASE_URL=" $ENV_FILE | cut -d'=' -f2)
        if [ -n "$STAGING_URL" ]; then
            if curl -f -s "$STAGING_URL" > /dev/null; then
                log_success "Staging application is accessible at $STAGING_URL"
            else
                log_warning "Staging application may not be accessible at $STAGING_URL"
            fi
        fi
    else
        log_error "Some staging containers are not running"
        show_status
        return 1
    fi
}

# Show status
show_status() {
    log_info "Staging environment status:"
    docker-compose -f $COMPOSE_FILE ps
    echo ""
    log_info "Recent logs:"
    docker-compose -f $COMPOSE_FILE logs --tail=20
}

# Show logs
show_logs() {
    if [ -n "$2" ]; then
        docker-compose -f $COMPOSE_FILE logs -f "$2"
    else
        docker-compose -f $COMPOSE_FILE logs -f
    fi
}

# Rollback to previous backup
rollback_staging() {
    log_warning "This will rollback the staging database to the most recent backup!"
    
    # Find the most recent backup
    latest_backup=$(ls -t "$BACKUP_DIR"/staging_backup_*.sql 2>/dev/null | head -n1)
    
    if [ -z "$latest_backup" ]; then
        log_error "No backup files found in $BACKUP_DIR"
        exit 1
    fi
    
    log_info "Most recent backup: $latest_backup"
    read -p "Are you sure you want to rollback? (y/N): " -n 1 -r
    echo
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        # Get database credentials
        DB_NAME=$(grep "^DB_NAME=" $ENV_FILE | cut -d'=' -f2)
        DB_USER=$(grep "^DB_USER=" $ENV_FILE | cut -d'=' -f2)
        DB_PASS=$(grep "^DB_PASS=" $ENV_FILE | cut -d'=' -f2)
        
        log_info "Restoring database from backup..."
        if docker-compose -f $COMPOSE_FILE exec -T db mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$latest_backup"; then
            log_success "Database restored successfully from $latest_backup"
        else
            log_error "Failed to restore database"
            exit 1
        fi
    else
        log_info "Rollback cancelled"
    fi
}

# Main script logic
case "${1:-status}" in
    deploy)
        deploy_staging
        ;;
    status)
        show_status
        ;;
    logs)
        show_logs "$@"
        ;;
    health)
        check_staging_health
        ;;
    backup)
        backup_database
        ;;
    rollback)
        rollback_staging
        ;;
    *)
        echo "Usage: $0 {deploy|status|logs|health|backup|rollback}"
        echo ""
        echo "Commands:"
        echo "  deploy      - Deploy latest staging branch to staging environment"
        echo "  status      - Show status of staging services"
        echo "  logs        - Show logs (add service name for specific service)"
        echo "  health      - Check staging environment health"
        echo "  backup      - Create database backup"
        echo "  rollback    - Rollback database to most recent backup"
        exit 1
        ;;
esac