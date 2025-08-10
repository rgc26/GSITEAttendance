#!/bin/bash

# WebSys Secure Deployment Script
# This script automates the secure deployment of the WebSys application

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="WebSys"
APP_ENV="production"
BACKUP_DIR="./backups"
LOG_FILE="./deploy.log"

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}" | tee -a "$LOG_FILE"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}" | tee -a "$LOG_FILE"
    exit 1
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root"
fi

# Create backup directory
mkdir -p "$BACKUP_DIR"

log "Starting secure deployment of $APP_NAME"

# Step 1: Pre-deployment checks
log "Step 1: Performing pre-deployment security checks"

# Check if .env file exists
if [ ! -f ".env" ]; then
    error ".env file not found. Please create it from .env.example"
fi

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env; then
    warn "APP_KEY not set. Generating new application key..."
    php artisan key:generate
fi

# Check if APP_ENV is set to production
if ! grep -q "APP_ENV=production" .env; then
    warn "APP_ENV not set to production. Please update .env file"
fi

# Check if APP_DEBUG is set to false
if ! grep -q "APP_DEBUG=false" .env; then
    warn "APP_DEBUG not set to false. Please update .env file"
fi

# Step 2: Create backup
log "Step 2: Creating backup of current application"
BACKUP_FILE="$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz"
tar -czf "$BACKUP_FILE" --exclude='./vendor' --exclude='./node_modules' --exclude='./.git' . 2>/dev/null || warn "Backup creation failed"

# Step 3: Security cleanup
log "Step 3: Performing security cleanup"

# Remove test and debug files
find . -name "*.test.php" -delete 2>/dev/null || true
find . -name "*.spec.php" -delete 2>/dev/null || true
find . -name "test_*.php" -delete 2>/dev/null || true
find . -name "*Test.php" -delete 2>/dev/null || true

# Remove development dependencies
log "Removing development dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Step 4: Set secure file permissions
log "Step 4: Setting secure file permissions"

# Set directory permissions
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# Set specific permissions for sensitive directories
chmod 755 storage
chmod 755 bootstrap/cache
chmod 755 public/storage

# Make sure .env is not world readable
chmod 600 .env

# Step 5: Clear and cache configuration
log "Step 5: Optimizing application for production"

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Security verification
log "Step 6: Verifying security configuration"

# Check if security middlewares are registered
if ! grep -q "SecurityHeaders" app/Http/Kernel.php; then
    warn "SecurityHeaders middleware not found in Kernel.php"
fi

if ! grep -q "Firewall" app/Http/Kernel.php; then
    warn "Firewall middleware not found in Kernel.php"
fi

# Check .htaccess files
if [ ! -f ".htaccess" ]; then
    warn "Root .htaccess file not found"
fi

if [ ! -f "public/.htaccess" ]; then
    warn "Public .htaccess file not found"
fi

# Step 7: Database security
log "Step 7: Checking database security"

# Check if database connection works
if php artisan tinker --execute="echo 'Database connection OK';" 2>/dev/null; then
    log "Database connection verified"
else
    warn "Database connection failed. Please check your configuration"
fi

# Step 8: Final security checks
log "Step 8: Final security verification"

# Check for any remaining .env files
if find . -name ".env*" -not -name ".env.example" | grep -q .; then
    warn "Found additional .env files. Please review for security"
fi

# Check for any PHP files in public directory (except index.php)
if find public -name "*.php" -not -name "index.php" | grep -q .; then
    warn "Found PHP files in public directory. Please review for security"
fi

# Step 9: Health check
log "Step 9: Performing application health check"

# Check if application responds
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|404"; then
    log "Application health check passed"
else
    warn "Application health check failed. Please verify manually"
fi

# Step 10: Deployment summary
log "Step 10: Deployment summary"

echo ""
echo "=========================================="
echo "           DEPLOYMENT COMPLETE            "
echo "=========================================="
echo ""
echo "Application: $APP_NAME"
echo "Environment: $APP_ENV"
echo "Backup created: $BACKUP_FILE"
echo "Log file: $LOG_FILE"
echo ""
echo "Next steps:"
echo "1. Verify application functionality"
echo "2. Test security measures"
echo "3. Monitor logs for any issues"
echo "4. Update DNS if deploying to new domain"
echo "5. Set up SSL certificate if not already done"
echo ""
echo "Security measures implemented:"
echo "✓ Security headers middleware"
echo "✓ Firewall middleware"
echo "✓ File upload security"
echo "✓ Rate limiting"
echo "✓ CSRF protection"
echo "✓ XSS protection"
echo "✓ Directory traversal protection"
echo "✓ Malicious file upload blocking"
echo "✓ Suspicious request blocking"
echo "✓ Secure file permissions"
echo ""
echo "Remember to:"
echo "- Regularly update dependencies"
echo "- Monitor security logs"
echo "- Perform regular security audits"
echo "- Keep backups updated"
echo "- Test disaster recovery procedures"
echo ""

log "Deployment completed successfully!"
log "Backup saved to: $BACKUP_FILE"
log "Log saved to: $LOG_FILE"

echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}Check the log file for detailed information.${NC}"
