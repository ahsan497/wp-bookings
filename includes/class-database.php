<?php
/**
 * Database Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class Booking_Forms_Database {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'bookings';
    }
    
    /**
     * Create bookings table with proper structure
     */
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Use backticks for MySQL compatibility
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `booking_type` varchar(20) NOT NULL,
            `booking_data` longtext NOT NULL,
            `customer_name` varchar(255) NOT NULL,
            `customer_email` varchar(255) NOT NULL,
            `customer_phone` varchar(50) NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'pending',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (`id`),
            KEY `booking_type` (`booking_type`),
            KEY `status` (`status`),
            KEY `created_at` (`created_at`)
        ) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Verify table was created
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name;
        
        if (!$table_exists) {
            // Try direct query as fallback
            $wpdb->query($sql);
            error_log('Booking Forms: Used direct query to create table ' . $this->table_name);
        }
        
        return $this->table_exists();
    }
    
    /**
     * Save booking to database
     */
    public function save_booking($type, $data) {
        global $wpdb;
        
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'booking_type' => $type,
                'booking_data' => wp_json_encode($data),
                'customer_name' => $data['name'],
                'customer_email' => $data['email'],
                'customer_phone' => $data['phone'],
                'status' => 'pending',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        // Log error
        if ($wpdb->last_error) {
            error_log('Booking Forms DB Error: ' . $wpdb->last_error);
        }
        
        return false;
    }
    
    /**
     * Get all bookings with optional filters
     */
    public function get_bookings($filters = array()) {
        global $wpdb;
        
        $query = "SELECT * FROM {$this->table_name} WHERE 1=1";
        
        if (!empty($filters['type'])) {
            $query .= $wpdb->prepare(" AND booking_type = %s", $filters['type']);
        }
        
        if (!empty($filters['status'])) {
            $query .= $wpdb->prepare(" AND status = %s", $filters['status']);
        }
        
        $query .= " ORDER BY created_at DESC";
        
        return $wpdb->get_results($query);
    }
    
    /**
     * Get booking by ID
     */
    public function get_booking($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id));
    }
    
    /**
     * Update booking status
     */
    public function update_status($id, $status) {
        global $wpdb;
        return $wpdb->update(
            $this->table_name,
            array('status' => $status),
            array('id' => $id),
            array('%s'),
            array('%d')
        );
    }
    
    /**
     * Delete booking
     */
    public function delete_booking($id) {
        global $wpdb;
        return $wpdb->delete($this->table_name, array('id' => $id), array('%d'));
    }
    
    /**
     * Get statistics
     */
    public function get_statistics() {
        global $wpdb;
        
        return array(
            'total' => $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}"),
            'flight' => $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE booking_type = 'flight'"),
            'hotel' => $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE booking_type = 'hotel'"),
            'pending' => $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE status = 'pending'")
        );
    }
    
    /**
     * Check if table exists
     */
    public function table_exists() {
        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name;
    }
}
