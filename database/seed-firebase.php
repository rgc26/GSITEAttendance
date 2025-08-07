<?php

/**
 * Firebase Seeder Script
 * 
 * This script seeds your Firestore database with sample data.
 * Run this after setting up your Firebase configuration.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Database\Seeders\FirebaseDataSeeder;

echo "🌱 Starting Firebase Seeder...\n\n";

try {
    $seeder = new FirebaseDataSeeder();
    $seeder->run();
    
    echo "\n🎉 Seeding completed successfully!\n";
    echo "📋 Sample data created:\n";
    echo "   - 5 users (2 teachers, 3 students)\n";
    echo "   - 3 subjects\n";
    echo "   - 4 schedules\n";
    echo "   - 3 attendance sessions\n";
    echo "   - 3 attendance records\n\n";
    
    echo "🔑 Default login credentials:\n";
    echo "   Teacher: john.doe@example.com / password123\n";
    echo "   Student: alice.johnson@student.com / password123\n\n";
    
    echo "💡 Next steps:\n";
    echo "   1. Test your application with the sample data\n";
    echo "   2. Deploy to Firebase Hosting\n";
    echo "   3. Configure Firebase Authentication\n";
    
} catch (Exception $e) {
    echo "❌ Seeding failed: " . $e->getMessage() . "\n\n";
    
    echo "🔧 Troubleshooting:\n";
    echo "   1. Check your Firebase configuration in .env\n";
    echo "   2. Verify your Firebase project exists\n";
    echo "   3. Ensure Firestore is enabled\n";
    echo "   4. Check your internet connection\n";
    
    exit(1);
} 