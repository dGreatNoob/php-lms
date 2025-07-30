#!/bin/bash

# Development Environment Manager
# Usage: ./scripts/dev-manager.sh [up|down|restart|logs|status|seed]

set -e

COMPOSE_FILE="docker-compose.yml"
ENV_FILE=".env"

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
        log_warning "Environment file $ENV_FILE not found. Creating from template..."
        if [ -f ".env.example" ]; then
            cp .env.example $ENV_FILE
        else
            log_error "No .env.example found. Please create $ENV_FILE manually."
            exit 1
        fi
    fi
}

# Start development environment
start_dev() {
    log_info "Starting development environment..."
    check_docker
    check_env
    
    docker-compose -f $COMPOSE_FILE up -d
    
    log_info "Waiting for services to be ready..."
    sleep 10
    
    # Check if services are running
    if docker-compose -f $COMPOSE_FILE ps | grep -q "Up"; then
        log_success "Development environment is running!"
        log_info "Application: http://localhost:8080"
        log_info "phpMyAdmin: http://localhost:8081"
        log_info "Database: localhost:3307"
    else
        log_error "Failed to start development environment"
        docker-compose -f $COMPOSE_FILE logs
        exit 1
    fi
}

# Stop development environment
stop_dev() {
    log_info "Stopping development environment..."
    docker-compose -f $COMPOSE_FILE down
    log_success "Development environment stopped"
}

# Restart development environment
restart_dev() {
    log_info "Restarting development environment..."
    stop_dev
    start_dev
}

# Show logs
show_logs() {
    if [ -n "$2" ]; then
        docker-compose -f $COMPOSE_FILE logs -f "$2"
    else
        docker-compose -f $COMPOSE_FILE logs -f
    fi
}

# Show status
show_status() {
    log_info "Development environment status:"
    docker-compose -f $COMPOSE_FILE ps
    echo ""
    log_info "Docker images:"
    docker images | grep php-lms || echo "No php-lms images found"
}

# Seed database
seed_database() {
    log_info "Seeding database with demo data..."
    
    # Check if database container is running
    if ! docker-compose -f $COMPOSE_FILE ps db | grep -q "Up"; then
        log_error "Database container is not running. Please start the development environment first."
        exit 1
    fi
    
    # Import seed data
    if [ -f "database/seed.sql" ]; then
        docker-compose -f $COMPOSE_FILE exec -T db mysql -u root -pexample php_lms < database/seed.sql
        log_success "Database seeded successfully!"
    else
        log_error "Seed file database/seed.sql not found"
        exit 1
    fi
}

# Reset database
reset_database() {
    log_warning "This will reset the database and remove all data!"
    read -p "Are you sure? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        log_info "Resetting database..."
        docker-compose -f $COMPOSE_FILE exec -T db mysql -u root -pexample -e "DROP DATABASE IF EXISTS php_lms; CREATE DATABASE php_lms;"
        docker-compose -f $COMPOSE_FILE exec -T db mysql -u root -pexample php_lms < database/schema.sql
        log_success "Database reset complete!"
        
        read -p "Seed with demo data? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            seed_database
        fi
    else
        log_info "Database reset cancelled"
    fi
}

# Main script logic
case "${1:-up}" in
    up|start)
        start_dev
        ;;
    down|stop)
        stop_dev
        ;;
    restart)
        restart_dev
        ;;
    logs)
        show_logs "$@"
        ;;
    status)
        show_status
        ;;
    seed)
        seed_database
        ;;
    reset)
        reset_database
        ;;
    *)
        echo "Usage: $0 {up|down|restart|logs|status|seed|reset}"
        echo ""
        echo "Commands:"
        echo "  up/start    - Start development environment"
        echo "  down/stop   - Stop development environment"
        echo "  restart     - Restart development environment"
        echo "  logs        - Show logs (add service name for specific service)"
        echo "  status      - Show status of services"
        echo "  seed        - Seed database with demo data"
        echo "  reset       - Reset database (WARNING: removes all data)"
        exit 1
        ;;
esac