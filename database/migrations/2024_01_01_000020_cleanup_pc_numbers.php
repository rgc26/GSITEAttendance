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
        // Clean up existing PC numbers to ensure they only contain numbers 1-40
        // Get all attendances with PC numbers
        $attendances = DB::table('attendances')
            ->whereNotNull('pc_number')
            ->where('pc_number', '!=', '')
            ->get();

        foreach ($attendances as $attendance) {
            $pcNumber = $attendance->pc_number;
            
            // Extract only numbers from the PC number
            $cleanNumber = preg_replace('/[^0-9]/', '', $pcNumber);
            
            // Check if it's a valid number between 1-40
            if ($cleanNumber !== '' && is_numeric($cleanNumber)) {
                $num = (int)$cleanNumber;
                if ($num >= 1 && $num <= 40) {
                    // Update with clean number
                    DB::table('attendances')
                        ->where('id', $attendance->id)
                        ->update(['pc_number' => $num]);
                } else {
                    // Set to NULL if out of range
                    DB::table('attendances')
                        ->where('id', $attendance->id)
                        ->update(['pc_number' => null]);
                }
            } else {
                // Set to NULL if no valid number found
                DB::table('attendances')
                    ->where('id', $attendance->id)
                    ->update(['pc_number' => null]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be safely reversed as it modifies data
    }
};
