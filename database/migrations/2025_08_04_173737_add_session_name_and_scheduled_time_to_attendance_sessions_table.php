<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('name')->nullable()->after('subject_id'); // Session name (e.g., "Week 1 - Introduction")
            $table->dateTime('scheduled_start_time')->nullable()->after('start_time'); // When session should start
            $table->dateTime('scheduled_end_time')->nullable()->after('scheduled_start_time'); // When session should end
            $table->integer('grace_period_minutes')->default(15)->after('scheduled_end_time'); // Grace period for late attendance
        });
    }

    public function down()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn(['name', 'scheduled_start_time', 'scheduled_end_time', 'grace_period_minutes']);
        });
    }
};
