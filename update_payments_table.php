<?php

require 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require realpath($bootstrap) ?: $bootstrap;

// Get database connection
$db = \Config\Database::connect();

try {
    // Check if columns already exist
    $query = $db->query("SHOW COLUMNS FROM payments LIKE 'payment_method'");
    
    if ($query->getNumRows() > 0) {
        echo "Columns already exist!\n";
    } else {
        // Add columns
        $db->query("ALTER TABLE payments 
            ADD COLUMN payment_method VARCHAR(50) NULL AFTER amount,
            ADD COLUMN reference_number VARCHAR(100) NULL UNIQUE AFTER payment_method,
            MODIFY COLUMN payment_date DATETIME");
        
        echo "✅ Successfully added payment_method and reference_number columns to payments table!\n";
        echo "✅ Modified payment_date to DATETIME!\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
