# WebSys Production Deployment Guide

## 🚀 Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Update `.env` file with production values
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL=https://yourdomain.com`
- [ ] Generate secure `APP_KEY`
- [ ] Configure production database credentials
- [ ] Set up production mail configuration
- [ ] Configure Redis/Memcached for caching

### 2. Security Configuration
- [ ] Enable HTTPS/SSL
- [ ] Configure security headers
- [ ] Set up rate limiting
- [ ] Configure firewall rules
- [ ] Set secure file permissions
- [ ] Enable CSRF protection
- [ ] Configure session security

### 3. Server Requirements
- [ ] PHP 8.1+ with required extensions
- [ ] MySQL 8.0+ or PostgreSQL 13+
- [ ] Redis for caching and sessions
- [ ] Web server (Apache/Nginx)
- [ ] SSL certificate
- [ ] Proper file permissions

## 📋 Deployment Steps

### Step 1: Prepare Your Server
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required software
sudo apt install nginx mysql-server redis-server php8.1-fpm php8.1-mysql php8.1-redis php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 2: Deploy Your Application
```bash
# Clone or upload your application
cd /var/www
sudo git clone https://github.com/yourusername/websys.git
sudo chown -R www-data:www-data websys
cd websys

# Run the production deployment script
chmod +x deploy-production.sh
./deploy-production.sh
```

### Step 3: Configure Web Server

#### For Nginx:
```bash
# Copy the Nginx configuration
sudo cp nginx-production.conf /etc/nginx/sites-available/websys
sudo ln -s /etc/nginx/sites-available/websys /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

#### For Apache:
```bash
# Copy the Apache configuration
sudo cp apache-production.conf /etc/apache2/sites-available/websys.conf
sudo a2ensite websys
sudo a2enmod ssl rewrite headers

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### Step 4: Configure Database
```bash
# Create database and user
sudo mysql -u root -p
CREATE DATABASE websys_db;
CREATE USER 'websys_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON websys_db.* TO 'websys_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force
```

### Step 5: Configure Redis
```bash
# Edit Redis configuration
sudo nano /etc/redis/redis.conf

# Set password and security
requirepass your_redis_password
bind 127.0.0.1
protected-mode yes

# Restart Redis
sudo systemctl restart redis
```

### Step 6: Set Up SSL Certificate
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🔒 Security Hardening

### 1. Rate Limiting Configuration
```bash
# Clear any existing rate limiting data
php clear-rate-limits.php

# Check rate limiting configuration
php artisan config:show rate_limiting

# Test rate limiting functionality
php artisan tinker
RateLimiter::remaining('test', 5);
```

### 2. File Permissions
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/websys
sudo chmod -R 755 /var/www/websys
sudo chmod -R 644 /var/www/websys/storage/logs/*.log
sudo chmod 644 /var/www/websys/.env
```

### 2. Firewall Configuration
```bash
# Configure UFW firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 3. Security Headers
The web server configurations already include security headers:
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: 1; mode=block
- Content-Security-Policy
- Strict-Transport-Security
- Referrer-Policy

## 📊 Monitoring and Maintenance

### 1. Log Monitoring
```bash
# Monitor application logs
tail -f /var/www/websys/storage/logs/laravel.log

# Monitor web server logs
tail -f /var/log/nginx/websys_error.log
tail -f /var/log/nginx/websys_access.log
```

### 2. Performance Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Monitor system resources
htop
iotop
nethogs
```

### 3. Backup Strategy
```bash
# Create backup script
nano /var/www/websys/backup.sh

# Add to crontab for automated backups
sudo crontab -e
# Add: 0 2 * * * /var/www/websys/backup.sh
```

## 🚨 Troubleshooting

### Common Issues and Solutions

#### 1. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/websys_error.log

# Verify file permissions
sudo chown -R www-data:www-data /var/www/websys
```

#### 2. Database Connection Issues
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Check database service
sudo systemctl status mysql
```

#### 3. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart Redis
sudo systemctl restart redis
```

## 📈 Performance Optimization

### 1. PHP-FPM Optimization
```bash
# Edit PHP-FPM configuration
sudo nano /etc/php/8.1/fpm/pool.d/www.conf

# Optimize settings
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

### 2. MySQL Optimization
```bash
# Edit MySQL configuration
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add optimization settings
innodb_buffer_pool_size = 1G
query_cache_size = 64M
max_connections = 200
```

### 3. Redis Optimization
```bash
# Edit Redis configuration
sudo nano /etc/redis/redis.conf

# Add optimization settings
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

## 🔄 Update and Maintenance

### 1. Application Updates
```bash
# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. System Updates
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
sudo systemctl restart mysql
sudo systemctl restart redis
```

## 📞 Support and Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Nginx Documentation**: https://nginx.org/en/docs/
- **Apache Documentation**: https://httpd.apache.org/docs/
- **Redis Documentation**: https://redis.io/documentation
- **MySQL Documentation**: https://dev.mysql.com/doc/

## ✅ Post-Deployment Verification

- [ ] Application loads without errors
- [ ] HTTPS redirects work properly
- [ ] Database connections are working
- [ ] File uploads function correctly
- [ ] Email functionality works
- [ ] All routes are accessible
- [ ] Security headers are present
- [ ] Performance is acceptable
- [ ] Monitoring is set up
- [ ] Backup system is working

---

**Remember**: Always test in a staging environment before deploying to production!
