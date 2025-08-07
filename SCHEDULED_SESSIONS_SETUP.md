# Scheduled Sessions Setup Guide

## Problem
Your scheduled sessions are not activating automatically when their scheduled time arrives. This is because Laravel's task scheduler is not configured to run automatically.

## Solution

### 1. Database Configuration
First, you need to set up your database connection. Create a `.env` file in your project root with the following content:

```env
APP_NAME="Attendance System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Generate Application Key
Run this command to generate an application key:
```bash
php artisan key:generate
```

### 3. Set Up Automatic Scheduling (Recommended)

#### Option A: Using Windows Task Scheduler
1. Open Task Scheduler (search for "Task Scheduler" in Windows)
2. Create a new Basic Task
3. Set it to run every minute
4. Set the action to run: `php artisan schedule:run`
5. Set the working directory to your project folder: `C:\xampp\htdocs\webSys`

#### Option B: Using Cron Job (if you have access to cron)
Add this line to your crontab:
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Manual Activation (Alternative)
If you can't set up automatic scheduling, you can manually activate sessions using the admin dashboard:

1. Visit: `http://localhost/webSys/admin/sessions`
2. Use the "Activate Sessions" and "Deactivate Sessions" buttons
3. Or call the API endpoints directly:
   - `http://localhost/webSys/admin/activate-sessions`
   - `http://localhost/webSys/admin/deactivate-sessions`

### 5. Test the Setup

#### Test the Commands
```bash
# Test activation command
php artisan sessions:activate

# Test deactivation command
php artisan sessions:deactivate

# Check scheduled tasks
php artisan schedule:list
```

#### Test the API Endpoints
Visit these URLs in your browser:
- `http://localhost/webSys/admin/sessions` - Admin dashboard
- `http://localhost/webSys/admin/activate-sessions` - Manual activation
- `http://localhost/webSys/admin/deactivate-sessions` - Manual deactivation

### 6. How It Works

The system now includes:

1. **Automatic Commands**: `sessions:activate` and `sessions:deactivate` that run every minute
2. **Manual Activation**: Web interface and API endpoints for manual control
3. **Admin Dashboard**: Visual interface to manage scheduled sessions

### 7. Troubleshooting

#### Database Connection Issues
- Make sure XAMPP MySQL is running
- Check that the database `attendance_system` exists
- Verify username/password in `.env` file

#### Commands Not Working
- Run `php artisan config:clear`
- Run `php artisan cache:clear`
- Check if the `.env` file exists and has correct values

#### Scheduled Tasks Not Running
- Verify the cron job or Windows Task Scheduler is set up correctly
- Check the Laravel logs in `storage/logs/laravel.log`
- Test manually by running `php artisan schedule:run`

### 8. Files Created/Modified

1. **New Commands**:
   - `app/Console/Commands/ActivateScheduledSessions.php`
   - `app/Console/Commands/DeactivateExpiredSessions.php`

2. **Updated Files**:
   - `app/Console/Kernel.php` - Added scheduled tasks
   - `routes/web.php` - Added admin routes
   - `resources/views/admin/sessions.blade.php` - Admin dashboard

3. **New Routes**:
   - `/admin/sessions` - Admin dashboard
   - `/admin/activate-sessions` - Manual activation
   - `/admin/deactivate-sessions` - Manual deactivation
   - `/admin/sessions-data` - Sessions data API

### 9. Next Steps

1. Create the `.env` file with your database credentials
2. Run `php artisan key:generate`
3. Set up automatic scheduling (cron or Windows Task Scheduler)
4. Test by creating a scheduled session with a time in the near future
5. Monitor the admin dashboard to see if sessions activate automatically

The scheduled sessions should now activate automatically when their scheduled start time arrives!
