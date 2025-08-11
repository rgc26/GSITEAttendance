#!/bin/bash

# WebSys Production Deployment Script
# This script prepares and deploys the Laravel application for production

set -e  # Exit on any error

echo "🚀 Starting WebSys Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="WebSys"
APP_ENV="production"
BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"

echo -e "${GREEN}📋 Configuration:${NC}"
echo "   App Name: $APP_NAME"
echo "   Environment: $APP_ENV"
echo "   Backup Directory: $BACKUP_DIR"

# Create backup directory
mkdir -p "$BACKUP_DIR"

# 1. Backup current database (if exists)
echo -e "\n${YELLOW}📦 Creating database backup...${NC}"
if [ -f "database/database.sqlite" ]; then
    cp database/database.sqlite "$BACKUP_DIR/database_backup.sqlite"
    echo "   Database backed up to $BACKUP_DIR/database_backup.sqlite"
fi

# 2. Backup current .env file (if exists)
echo -e "\n${YELLOW}📦 Backing up environment file...${NC}"
if [ -f ".env" ]; then
    cp .env "$BACKUP_DIR/env_backup"
    echo "   Environment file backed up to $BACKUP_DIR/env_backup"
fi

# 3. Install/Update Composer dependencies
echo -e "\n${YELLOW}📦 Installing Composer dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
echo "   Dependencies installed successfully"

# 4. Clear all caches
echo -e "\n${YELLOW}🧹 Clearing application caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "   All caches cleared"

# 5. Generate application key (if not exists)
echo -e "\n${YELLOW}🔑 Generating application key...${NC}"
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate
    echo "   Application key generated"
else
    echo "   Application key already exists"
fi

# 6. Run database migrations
echo -e "\n${YELLOW}🗄️  Running database migrations...${NC}"
php artisan migrate --force
echo "   Database migrations completed"

# 7. Optimize for production
echo -e "\n${YELLOW}⚡ Optimizing for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "   Application optimized for production"

# 8. Set proper permissions
echo -e "\n${YELLOW}🔒 Setting file permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log 2>/dev/null || true
echo "   File permissions set"

# 9. Create storage link
echo -e "\n${YELLOW}🔗 Creating storage link...${NC}"
php artisan storage:link
echo "   Storage link created"

# 10. Health check
echo -e "\n${YELLOW}🏥 Running health checks...${NC}"
if php artisan --version > /dev/null 2>&1; then
    echo "   Laravel Artisan is working"
else
    echo -e "${RED}   ❌ Laravel Artisan check failed${NC}"
    exit 1
fi

# 11. Final optimization
echo -e "\n${YELLOW}🎯 Final optimizations...${NC}"
composer dump-autoload --optimize
echo "   Autoloader optimized"

# 12. Create deployment info
echo -e "\n${YELLOW}📝 Creating deployment info...${NC}"
echo "Deployment completed at: $(date)" > "$BACKUP_DIR/deployment_info.txt"
echo "Git commit: $(git rev-parse HEAD 2>/dev/null || echo 'Unknown')" >> "$BACKUP_DIR/deployment_info.txt"
echo "Laravel version: $(php artisan --version)" >> "$BACKUP_DIR/deployment_info.txt"

# 13. Cleanup old backups (keep last 5)
echo -e "\n${YELLOW}🧹 Cleaning up old backups...${NC}"
cd backups
ls -t | tail -n +6 | xargs -r rm -rf
cd ..
echo "   Old backups cleaned up"

echo -e "\n${GREEN}✅ Production deployment completed successfully!${NC}"
echo -e "${GREEN}📁 Backup created in: $BACKUP_DIR${NC}"
echo -e "${GREEN}🌐 Your application is now ready for production use${NC}"

# Display important reminders
echo -e "\n${YELLOW}⚠️  IMPORTANT REMINDERS:${NC}"
echo "   1. Update your .env file with production values"
echo "   2. Configure your web server (Apache/Nginx)"
echo "   3. Set up SSL certificate for HTTPS"
echo "   4. Configure your database credentials"
echo "   5. Set up proper backup procedures"
echo "   6. Monitor application logs"
echo "   7. Set up monitoring and alerting"

echo -e "\n${GREEN}🚀 WebSys is now deployed and ready for production!${NC}"
