<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert existing attendance times from UTC to Philippine time (+8 hours)
        // This fixes the timezone issue where times were stored as UTC instead of PH time
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite compatible syntax
            DB::statement("
                UPDATE attendances 
                SET check_in_time = datetime(check_in_time, '+8 hours')
                WHERE check_in_time IS NOT NULL
            ");
            
            // Also update attendance session times if they exist
            DB::statement("
                UPDATE attendance_sessions 
                SET start_time = datetime(start_time, '+8 hours'),
                    end_time = datetime(end_time, '+8 hours'),
                    scheduled_start_time = datetime(scheduled_start_time, '+8 hours'),
                    scheduled_end_time = datetime(scheduled_end_time, '+8 hours')
                WHERE start_time IS NOT NULL 
                   OR end_time IS NOT NULL 
                   OR scheduled_start_time IS NOT NULL 
                   OR scheduled_end_time IS NOT NULL
            ");
        } else {
            // MySQL compatible syntax
            DB::statement("
                UPDATE attendances 
                SET check_in_time = DATE_ADD(check_in_time, INTERVAL 8 HOUR) 
                WHERE check_in_time IS NOT NULL
            ");
            
            // Also update attendance session times if they exist
            DB::statement("
                UPDATE attendance_sessions 
                SET start_time = DATE_ADD(start_time, INTERVAL 8 HOUR),
                    end_time = DATE_ADD(end_time, INTERVAL 8 HOUR),
                    scheduled_start_time = DATE_ADD(scheduled_start_time, INTERVAL 8 HOUR),
                    scheduled_end_time = DATE_ADD(scheduled_end_time, INTERVAL 8 HOUR)
                WHERE start_time IS NOT NULL 
                   OR end_time IS NOT NULL 
                   OR scheduled_start_time IS NOT NULL 
                   OR scheduled_end_time IS NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite compatible syntax
            DB::statement("
                UPDATE attendances 
                SET check_in_time = datetime(check_in_time, '-8 hours')
                WHERE check_in_time IS NOT NULL
            ");
            
            DB::statement("
                UPDATE attendance_sessions 
                SET start_time = datetime(start_time, '-8 hours'),
                    end_time = datetime(end_time, '-8 hours'),
                    scheduled_start_time = datetime(scheduled_start_time, '-8 hours'),
                    scheduled_end_time = datetime(scheduled_end_time, '-8 hours')
                WHERE start_time IS NOT NULL 
                   OR end_time IS NOT NULL 
                   OR scheduled_start_time IS NOT NULL 
                   OR scheduled_end_time IS NOT NULL
            ");
        } else {
            // MySQL compatible syntax
            DB::statement("
                UPDATE attendances 
                SET check_in_time = DATE_SUB(check_in_time, INTERVAL 8 HOUR) 
                WHERE check_in_time IS NOT NULL
            ");
            
            DB::statement("
                UPDATE attendance_sessions 
                SET start_time = DATE_SUB(start_time, INTERVAL 8 HOUR),
                    end_time = DATE_SUB(end_time, INTERVAL 8 HOUR),
                    scheduled_start_time = DATE_SUB(scheduled_start_time, INTERVAL 8 HOUR),
                    scheduled_end_time = DATE_SUB(scheduled_end_time, INTERVAL 8 HOUR)
                WHERE start_time IS NOT NULL 
                   OR end_time IS NOT NULL 
                   OR scheduled_start_time IS NOT NULL 
                   OR scheduled_end_time IS NOT NULL
            ");
        }
    }
};
