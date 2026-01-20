<?php
/**
 * Standalone Table Creation Script
 * 
 * If the plugin isn't creating the table automatically, run this file directly:
 * 1. Upload this file to your WordPress root directory
 * 2. Visit: http://localhost/your-site/create-booking-table.php
 * 3. Follow the instructions
 * 4. Delete this file after use
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('You must be an administrator to run this script.');
}

global $wpdb;

$table_name = $wpdb->prefix . 'bookings';
$charset_collate = $wpdb->get_charset_collate();

// Create table SQL
$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
    `id` mediumint(9) NOT NULL AUTO_INCREMENT,
    `booking_type` varchar(20) NOT NULL,
    `booking_data` longtext NOT NULL,
    `customer_name` varchar(255) NOT NULL,
    `customer_email` varchar(255) NOT NULL,
    `customer_phone` varchar(50) NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'pending',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `booking_type` (`booking_type`),
    KEY `status` (`status`),
    KEY `created_at` (`created_at`)
) {$charset_collate};";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Booking Table</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 20px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border: 1px solid #bee5eb; border-radius: 4px; margin: 20px 0; }
        .sql-box { background: #f5f5f5; border: 1px solid #ddd; padding: 15px; font-family: monospace; font-size: 12px; overflow-x: auto; margin: 20px 0; }
        .button { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block; margin: 10px 5px 10px 0; }
        .button:hover { background: #005a87; }
        h1 { color: #333; }
        h2 { color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>🔧 Booking Forms - Table Creation Script</h1>
    
    <?php
    // Check if table already exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
    
    if ($table_exists) {
        echo '<div class="success"><strong>✅ Success!</strong> The table already exists: <code>' . $table_name . '</code></div>';
        echo '<p><a href="' . admin_url('admin.php?page=booking-forms') . '" class="button">Go to Bookings Page</a></p>';
        echo '<p><strong>You can now delete this file.</strong></p>';
    } else {
        if (isset($_GET['create'])) {
            // Try to create table
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            // Verify
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
            
            if ($table_exists) {
                echo '<div class="success"><strong>✅ Success!</strong> Table created successfully: <code>' . $table_name . '</code></div>';
                echo '<p><a href="' . admin_url('admin.php?page=booking-forms') . '" class="button">Go to Bookings Page</a></p>';
                echo '<p><strong>Please delete this file now for security.</strong></p>';
            } else {
                // Try direct query
                $result = $wpdb->query($sql);
                
                if ($result !== false) {
                    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
                    if ($table_exists) {
                        echo '<div class="success"><strong>✅ Success!</strong> Table created using direct query.</div>';
                        echo '<p><a href="' . admin_url('admin.php?page=booking-forms') . '" class="button">Go to Bookings Page</a></p>';
                    } else {
                        echo '<div class="error"><strong>❌ Error!</strong> Table creation failed. Please create manually using phpMyAdmin.</div>';
                    }
                } else {
                    echo '<div class="error"><strong>❌ Error!</strong> Database query failed: ' . $wpdb->last_error . '</div>';
                    echo '<div class="info"><strong>Possible causes:</strong><ul>';
                    echo '<li>Database user doesn\'t have CREATE TABLE permission</li>';
                    echo '<li>Database connection issue</li>';
                    echo '<li>MySQL syntax incompatibility</li>';
                    echo '</ul></div>';
                }
            }
        } else {
            echo '<div class="info"><strong>ℹ️ Table Not Found</strong><br>The table <code>' . $table_name . '</code> doesn\'t exist in database <code>' . DB_NAME . '</code></div>';
            echo '<p><a href="?create=1" class="button">Create Table Automatically</a></p>';
        }
        
        // Always show manual instructions
        echo '<h2>Manual Creation (if automatic fails):</h2>';
        echo '<p>Copy and run this SQL in phpMyAdmin:</p>';
        echo '<div class="sql-box">' . htmlspecialchars($sql) . '</div>';
        
        echo '<h3>Steps:</h3>';
        echo '<ol>';
        echo '<li>Open phpMyAdmin in your browser (usually at <code>http://localhost/phpmyadmin</code>)</li>';
        echo '<li>Select your database: <strong>' . DB_NAME . '</strong></li>';
        echo '<li>Click the "SQL" tab at the top</li>';
        echo '<li>Copy and paste the SQL code above</li>';
        echo '<li>Click "Go" button</li>';
        echo '<li>Refresh this page to verify</li>';
        echo '</ol>';
        
        echo '<h2>Debug Information:</h2>';
        echo '<ul>';
        echo '<li><strong>Database:</strong> ' . DB_NAME . '</li>';
        echo '<li><strong>Table Name:</strong> ' . $table_name . '</li>';
        echo '<li><strong>User:</strong> ' . DB_USER . '</li>';
        echo '<li><strong>Charset:</strong> ' . $charset_collate . '</li>';
        echo '<li><strong>WordPress Version:</strong> ' . get_bloginfo('version') . '</li>';
        echo '<li><strong>PHP Version:</strong> ' . phpversion() . '</li>';
        echo '<li><strong>MySQL Version:</strong> ' . $wpdb->db_version() . '</li>';
        echo '</ul>';
    }
    ?>
    
    <hr style="margin: 40px 0;">
    <p><small><strong>Security Note:</strong> Please delete this file after the table is created.</small></p>
    
</body>
</html>
