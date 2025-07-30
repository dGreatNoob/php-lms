#!/bin/bash

# Production Environment Manager
# Usage: ./scripts/production-manager.sh [deploy|status|logs|backup|rollback]

set -e

COMPOSE_FILE="docker-compose.production.yml"
ENV_FILE=".env.production"
BACKUP_DIR="backups/production"

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
        log_error "Production environment file $ENV_FILE not found."
        log_info "Please create it with proper production credentials."
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
    local backup_file="$BACKUP_DIR/prod_backup_$(date +%Y%m%d_%H%M%S).sql"
    
    log_info "Creating production database backup..."
    
    # Get database credentials from .env.production
    DB_NAME=$(grep "^DB_NAME=" $ENV_FILE | cut -d'=' -f2)
    DB_USER=$(grep "^DB_USER=" $ENV_FILE | cut -d'=' -f2)
    DB_PASS=$(grep "^DB_PASS=" $ENV_FILE | cut -d'=' -f2)
    
    if docker-compose -f $COMPOSE_FILE exec -T db mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_file"; then
        log_success "Production database backup created: $backup_file"
        
        # Compress backup
        gzip "$backup_file"
        log_success "Backup compressed: $backup_file.gz"
        return 0
    else
        log_error "Failed to create production database backup"
        return 1
    fi
}

# Deploy to production with extra safety
deploy_production() {
    log_warning "⚠️  PRODUCTION DEPLOYMENT ⚠️"
    log_warning "This will deploy to the live production environment!"
    echo ""
    
    check_docker
    check_env
    
    # Multiple confirmations for production
    read -p "Are you absolutely sure you want to deploy to PRODUCTION? (type 'DEPLOY' to confirm): " -r
    if [ "$REPLY" != "DEPLOY" ]; then
        log_info "Production deployment cancelled"
        exit 0
    fi
    
    read -p "Have you tested this deployment on staging? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log_error "Please test on staging first!"
        exit 1
    fi
    
    # Backup database before deployment
    log_info "Creating pre-deployment backup..."
    if ! backup_database; then
        log_error "Failed to create backup. Aborting deployment."
        exit 1
    fi
    
    # Pull latest changes from main branch
    log_info "Pulling latest changes from main branch..."
    git fetch origin
    git checkout main
    git pull origin main
    
    # Build and deploy with zero-downtime approach
    log_info "Building new production containers..."
    docker-compose -f $COMPOSE_FILE build
    
    log_info "Starting new containers..."
    docker-compose -f $COMPOSE_FILE up -d --no-deps app
    
    # Wait for new container to be ready
    log_info "Waiting for new application container to be ready..."
    sleep 45
    
    # Health check
    if check_production_health; then
        log_success "New containers are healthy, completing deployment..."
        docker-compose -f $COMPOSE_FILE up -d
    else
        log_error "Health check failed, rolling back..."
        rollback_production
        exit 1
    fi
    
    # Final health check
    log_info "Performing final health check..."
    sleep 30
    if check_production_health; then
        log_success "🎉 Production deployment completed successfully! 🎉"
    else
        log_error "Final health check failed!"
        exit 1
    fi
}

# Check production health
check_production_health() {
    log_info "Checking production health..."
    
    # Check if containers are running
    if ! docker-compose -f $COMPOSE_FILE ps | grep -q "Up"; then
        log_error "Some production containers are not running"
        return 1
    fi
    
    # Check application accessibility
    PROD_URL=$(grep "^BASE_URL=" $ENV_FILE | cut -d'=' -f2)
    if [ -n "$PROD_URL" ]; then
        for i in {1..5}; do
            if curl -f -s -m 10 "$PROD_URL" > /dev/null; then
                log_success "Production application is accessible at $PROD_URL"
                return 0
            else
                log_warning "Attempt $i/5: Production application not accessible, retrying..."
                sleep 10
            fi
        done
        log_error "Production application is not accessible at $PROD_URL"
        return 1
    else
        log_warning "No BASE_URL configured, skipping accessibility check"
        return 0
    fi
}

# Show status
show_status() {
    log_info "Production environment status:"
    docker-compose -f $COMPOSE_FILE ps
    echo ""
    log_info "System resources:"
    docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}" $(docker-compose -f $COMPOSE_FILE ps -q) 2>/dev/null || echo "Unable to get stats"
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

# Rollback production
rollback_production() {
    log_warning "🚨 PRODUCTION ROLLBACK 🚨"
    log_warning "This will rollback the production database!"
    
    # Find the most recent backup
    latest_backup=$(ls -t "$BACKUP_DIR"/prod_backup_*.sql.gz 2>/dev/null | head -n1)
    
    if [ -z "$latest_backup" ]; then
        log_error "No backup files found in $BACKUP_DIR"
        exit 1
    fi
    
    log_info "Most recent backup: $latest_backup"
    read -p "Are you sure you want to rollback PRODUCTION? (type 'ROLLBACK' to confirm): " -r
    
    if [ "$REPLY" != "ROLLBACK" ]; then
        log_info "Production rollback cancelled"
        exit 0
    fi
    
    # Get database credentials
    DB_NAME=$(grep "^DB_NAME=" $ENV_FILE | cut -d'=' -f2)
    DB_USER=$(grep "^DB_USER=" $ENV_FILE | cut -d'=' -f2)
    DB_PASS=$(grep "^DB_PASS=" $ENV_FILE | cut -d'=' -f2)
    
    log_info "Restoring production database from backup..."
    
    # Decompress and restore
    if zcat "$latest_backup" | docker-compose -f $COMPOSE_FILE exec -T db mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"; then
        log_success "Production database restored successfully from $latest_backup"
        
        # Restart containers to ensure everything is fresh
        log_info "Restarting production containers..."
        docker-compose -f $COMPOSE_FILE restart
        
        sleep 30
        if check_production_health; then
            log_success "Production rollback completed successfully"
        else
            log_error "Production rollback completed but health check failed"
        fi
    else
        log_error "Failed to restore production database"
        exit 1
    fi
}

# Main script logic
case "${1:-status}" in
    deploy)
        deploy_production
        ;;
    status)
        show_status
        ;;
    logs)
        show_logs "$@"
        ;;
    health)
        check_production_health
        ;;
    backup)
        backup_database
        ;;
    rollback)
        rollback_production
        ;;
    *)
        echo "Usage: $0 {deploy|status|logs|health|backup|rollback}"
        echo ""
        echo "Commands:"
        echo "  deploy      - Deploy latest main branch to production (with confirmations)"
        echo "  status      - Show status of production services"
        echo "  logs        - Show logs (add service name for specific service)"
        echo "  health      - Check production environment health"
        echo "  backup      - Create production database backup"
        echo "  rollback    - Rollback database to most recent backup (DANGEROUS)"
        exit 1
        ;;
esac