<?php
/**
 * Plugin Name: Professional Booking Forms
 * Description: Flight and Hotel booking forms with AJAX submission and email notifications
 * Version: 1.0.1
 * Author: Penta Flights
 * Author URI: https://pentaflights.co.uk
 * Text Domain: booking-forms
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BOOKING_FORMS_VERSION', '1.0.1');
define('BOOKING_FORMS_PATH', plugin_dir_path(__FILE__));
define('BOOKING_FORMS_URL', plugin_dir_url(__FILE__));

// Include required files
require_once BOOKING_FORMS_PATH . 'includes/class-database.php';
require_once BOOKING_FORMS_PATH . 'includes/class-email-handler.php';
require_once BOOKING_FORMS_PATH . 'includes/class-admin.php';
require_once BOOKING_FORMS_PATH . 'includes/class-shortcode.php';

class BookingForms {
    
    private static $instance = null;
    private $database;
    private $email_handler;
    private $admin;
    private $shortcode;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Initialize components
        $this->database = new Booking_Forms_Database();
        $this->email_handler = new Booking_Forms_Email_Handler();
        $this->admin = new Booking_Forms_Admin($this->database, $this->email_handler);
        $this->shortcode = new Booking_Forms_Shortcode();
        
        // Register hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_submit_booking', array($this, 'handle_booking_submission'));
        add_action('wp_ajax_nopriv_submit_booking', array($this, 'handle_booking_submission'));
        add_action('send_booking_email', array($this->email_handler, 'send_booking_notification'), 10, 2);
        
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate_plugin'));
        
        // Settings link
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }
    
    public function add_settings_link($links) {
        $settings_link = '<a href="admin.php?page=booking-forms-settings">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public function enqueue_assets() {
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'booking_form')) {
            
            wp_enqueue_style(
                'booking-forms-style',
                BOOKING_FORMS_URL . 'assets/style-inline.css',
                array(),
                BOOKING_FORMS_VERSION
            );
            
            wp_enqueue_script(
                'booking-forms-script',
                BOOKING_FORMS_URL . 'assets/script.js',
                array('jquery'),
                BOOKING_FORMS_VERSION,
                true
            );
            
            wp_localize_script('booking-forms-script', 'bookingAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('booking_nonce'),
                'airports_url' => $this->get_airports_json_url()
            ));
        }
    }
    
    private function get_airports_json_url() {
        $upload_dir = wp_upload_dir();
        return $upload_dir['baseurl'] . '/airports.json';
    }
    
    public function handle_booking_submission() {
        check_ajax_referer('booking_nonce', 'nonce');
        
        $booking_type = sanitize_text_field($_POST['booking_type']);
        $form_data = $_POST['form_data'];
        
        $sanitized_data = array();
        foreach ($form_data as $key => $value) {
            $sanitized_data[$key] = sanitize_text_field($value);
        }
        
        // Validate required fields
        if (empty($sanitized_data['name']) || empty($sanitized_data['email']) || empty($sanitized_data['phone'])) {
            wp_send_json_error(array(
                'message' => 'Please fill in all required fields.'
            ));
        }
        
        if (!is_email($sanitized_data['email'])) {
            wp_send_json_error(array(
                'message' => 'Please enter a valid email address.'
            ));
        }
        
        // Save to database
        $booking_id = $this->database->save_booking($booking_type, $sanitized_data);
        
        if ($booking_id) {
            // Schedule email notification
            wp_schedule_single_event(time(), 'send_booking_email', array($booking_type, $sanitized_data));
            
            wp_send_json_success(array(
                'message' => 'Booking request submitted successfully! We will contact you soon.',
                'booking_id' => $booking_id
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Failed to save booking. Please try again.'
            ));
        }
    }
    
    public function activate_plugin() {
        // Force create database table
        global $wpdb;
        $table_name = $wpdb->prefix . 'bookings';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
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
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
        
        if (!$table_exists) {
            // Try direct query as fallback
            $wpdb->query($sql);
            error_log('Booking Forms: Attempted direct table creation for ' . $table_name);
        }
        
        // Set default options
        $default_options = array(
            'send_customer_email' => 1,
            'admin_email' => get_option('admin_email'),
            'email_config_type' => 'wordpress',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_port' => '587',
            'smtp_encryption' => 'tls',
            'smtp_from_name' => 'Penta Flights'
        );
        
        if (!get_option('booking_forms_options')) {
            add_option('booking_forms_options', $default_options);
        }
        
        flush_rewrite_rules();
    }
}

function booking_forms_init() {
    return BookingForms::get_instance();
}

add_action('plugins_loaded', 'booking_forms_init');
