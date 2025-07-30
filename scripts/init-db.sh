#!/bin/bash

# Database Initialization Script
# Usage: ./scripts/init-db.sh [environment]

set -e

# Default to development if no environment specified
ENVIRONMENT=${1:-development}

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

# Set environment-specific variables
case $ENVIRONMENT in
    development|dev)
        COMPOSE_FILE="docker-compose.yml"
        ENV_FILE=".env"
        ;;
    staging)
        COMPOSE_FILE="docker-compose.staging.yml"
        ENV_FILE=".env.staging"
        ;;
    production|prod)
        COMPOSE_FILE="docker-compose.production.yml"
        ENV_FILE=".env.production"
        ;;
    *)
        log_error "Invalid environment: $ENVIRONMENT"
        log_info "Valid environments: development, staging, production"
        exit 1
        ;;
esac

log_info "Initializing database for $ENVIRONMENT environment..."

# Check if environment file exists
if [ ! -f "$ENV_FILE" ]; then
    log_error "Environment file $ENV_FILE not found"
    exit 1
fi

# Check if Docker Compose file exists
if [ ! -f "$COMPOSE_FILE" ]; then
    log_error "Docker Compose file $COMPOSE_FILE not found"
    exit 1
fi

# Check if database container is running
if ! docker-compose -f $COMPOSE_FILE ps db | grep -q "Up"; then
    log_error "Database container is not running"
    log_info "Please start the environment first:"
    log_info "  For development: ./scripts/dev-manager.sh up"
    log_info "  For staging: ./scripts/staging-manager.sh deploy"
    exit 1
fi

# Get database credentials from environment file
DB_NAME=$(grep "^DB_NAME=" $ENV_FILE | cut -d'=' -f2 | tr -d '"')
DB_USER=$(grep "^DB_USER=" $ENV_FILE | cut -d'=' -f2 | tr -d '"')
DB_PASS=$(grep "^DB_PASS=" $ENV_FILE | cut -d'=' -f2 | tr -d '"')

log_info "Database: $DB_NAME"
log_info "User: $DB_USER"

# Function to execute SQL
execute_sql() {
    local sql_file=$1
    local description=$2
    
    if [ ! -f "$sql_file" ]; then
        log_error "SQL file not found: $sql_file"
        return 1
    fi
    
    log_info "$description..."
    if docker-compose -f $COMPOSE_FILE exec -T db mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$sql_file"; then
        log_success "$description completed"
        return 0
    else
        log_error "$description failed"
        return 1
    fi
}

# Initialize schema
if [ -f "database/schema.sql" ]; then
    log_warning "This will recreate the database schema and remove all existing data!"
    read -p "Are you sure you want to continue? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        # Drop and recreate database
        log_info "Recreating database..."
        docker-compose -f $COMPOSE_FILE exec -T db mysql -u "$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;"
        
        # Execute schema
        execute_sql "database/schema.sql" "Creating database schema"
    else
        log_info "Schema initialization cancelled"
        exit 0
    fi
else
    log_error "Schema file database/schema.sql not found"
    exit 1
fi

# Apply migrations
if [ -d "database/migrations" ] && [ "$(ls -A database/migrations)" ]; then
    log_info "Applying database migrations..."
    for migration in database/migrations/*.sql; do
        if [ -f "$migration" ]; then
            migration_name=$(basename "$migration")
            execute_sql "$migration" "Applying migration: $migration_name"
        fi
    done
else
    log_info "No migrations found in database/migrations/"
fi

# Seed data (only for development and staging)
if [ "$ENVIRONMENT" != "production" ] && [ -f "database/seed.sql" ]; then
    read -p "Would you like to seed the database with demo data? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        execute_sql "database/seed.sql" "Seeding database with demo data"
    fi
elif [ "$ENVIRONMENT" = "production" ]; then
    log_warning "Skipping demo data seeding for production environment"
fi

# Create application-specific tables or data
if [ -f "database/app_setup.sql" ]; then
    execute_sql "database/app_setup.sql" "Setting up application-specific data"
fi

log_success "Database initialization completed for $ENVIRONMENT environment!"

# Show some helpful information
log_info "Database connection details:"
log_info "  Host: localhost (or 'db' from within containers)"
log_info "  Port: 3306"
log_info "  Database: $DB_NAME"
log_info "  Username: $DB_USER"

if [ "$ENVIRONMENT" = "development" ]; then
    log_info ""
    log_info "You can access phpMyAdmin at: http://localhost:8081"
fi