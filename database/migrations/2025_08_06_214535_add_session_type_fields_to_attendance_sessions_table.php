<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('set null');
            $table->enum('session_type', ['lecture', 'lab', 'online'])->default('lecture')->after('schedule_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('device_type')->nullable()->after('pc_number'); // mobile, desktop, laptop for online sessions
            $table->string('attached_image')->nullable()->after('device_type'); // for lecture sessions with image requirements
        });
    }

    public function down()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn(['schedule_id', 'session_type']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'attached_image']);
        });
    }
};
