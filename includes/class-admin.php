<?php
/**
 * Admin Area Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class Booking_Forms_Admin {
    
    private $database;
    private $email_handler;
    
    public function __construct($database, $email_handler) {
        $this->database = $database;
        $this->email_handler = $email_handler;
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'handle_manual_table_creation'));
        add_action('admin_notices', array($this, 'check_table_exists_notice'));
    }
    
    /**
     * Check if table exists and show notice
     */
    public function check_table_exists_notice() {
        if (!$this->database->table_exists()) {
            $create_url = wp_nonce_url(admin_url('admin.php?page=booking-forms&action=create_table'), 'create_booking_table');
            ?>
            <div class="notice notice-error">
                <p><strong>Booking Forms Error:</strong> The database table is missing!</p>
                <p>
                    <a href="<?php echo $create_url; ?>" class="button button-primary">Create Table Now</a>
                    <span style="margin-left: 15px;">or deactivate and reactivate the plugin.</span>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Handle manual table creation
     */
    public function handle_manual_table_creation() {
        if (isset($_GET['action']) && $_GET['action'] === 'create_table' && isset($_GET['page']) && $_GET['page'] === 'booking-forms') {
            check_admin_referer('create_booking_table');
            
            $result = $this->database->create_table();
            
            if ($result) {
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> Database table created successfully.</p></div>';
                });
            } else {
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-error"><p><strong>Error!</strong> Failed to create database table. Please check your database permissions.</p></div>';
                });
            }
            
            wp_redirect(admin_url('admin.php?page=booking-forms'));
            exit;
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Booking Forms',
            'Bookings',
            'manage_options',
            'booking-forms',
            array($this, 'admin_bookings_page'),
            'dashicons-calendar-alt',
            30
        );
        
        add_submenu_page(
            'booking-forms',
            'All Bookings',
            'All Bookings',
            'manage_options',
            'booking-forms',
            array($this, 'admin_bookings_page')
        );
        
        add_submenu_page(
            'booking-forms',
            'Settings',
            'Settings',
            'manage_options',
            'booking-forms-settings',
            array($this, 'admin_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('booking_forms_settings', 'booking_forms_options');
        
        // Email Settings Section
        add_settings_section(
            'booking_forms_email_section',
            'Email Settings',
            array($this, 'email_section_callback'),
            'booking-forms-settings'
        );
        
        add_settings_field('send_customer_email', 'Send Email to Customer', array($this, 'send_customer_email_callback'), 'booking-forms-settings', 'booking_forms_email_section');
        add_settings_field('admin_email', 'Admin Email Address', array($this, 'admin_email_callback'), 'booking-forms-settings', 'booking_forms_email_section');
        add_settings_field('email_config_type', 'Email Configuration', array($this, 'email_config_type_callback'), 'booking-forms-settings', 'booking_forms_email_section');
        
        // SMTP Settings Section
        add_settings_section(
            'booking_forms_smtp_section',
            'Custom SMTP Settings',
            array($this, 'smtp_section_callback'),
            'booking-forms-settings'
        );
        
        add_settings_field('smtp_host', 'SMTP Host', array($this, 'smtp_host_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_port', 'SMTP Port', array($this, 'smtp_port_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_username', 'SMTP Username', array($this, 'smtp_username_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_password', 'SMTP Password', array($this, 'smtp_password_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_encryption', 'Encryption', array($this, 'smtp_encryption_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_from_email', 'From Email', array($this, 'smtp_from_email_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
        add_settings_field('smtp_from_name', 'From Name', array($this, 'smtp_from_name_callback'), 'booking-forms-settings', 'booking_forms_smtp_section');
    }
    
    // Settings Callbacks
    public function email_section_callback() {
        echo '<p>Configure email notification settings for booking forms.</p>';
    }
    
    public function smtp_section_callback() {
        $options = get_option('booking_forms_options');
        $config_type = isset($options['email_config_type']) ? $options['email_config_type'] : 'wordpress';
        
        if ($config_type === 'wordpress') {
            echo '<p style="color: #999;">Custom SMTP settings are disabled. Using WordPress default mail function.</p>';
        } else {
            echo '<p>Configure your custom SMTP server settings. For Hostinger, use: <strong>smtp.hostinger.com</strong>, Port: <strong>587</strong>, Encryption: <strong>TLS</strong></p>';
        }
    }
    
    public function send_customer_email_callback() {
        $options = get_option('booking_forms_options');
        $checked = isset($options['send_customer_email']) && $options['send_customer_email'] ? 'checked' : '';
        echo '<label><input type="checkbox" name="booking_forms_options[send_customer_email]" value="1" ' . $checked . '> Send confirmation email to customer</label>';
    }
    
    public function admin_email_callback() {
        $options = get_option('booking_forms_options');
        $value = isset($options['admin_email']) ? esc_attr($options['admin_email']) : get_option('admin_email');
        echo '<input type="email" name="booking_forms_options[admin_email]" value="' . $value . '" class="regular-text" required>';
        echo '<p class="description">Email address where booking notifications will be sent.</p>';
    }
    
    public function email_config_type_callback() {
        $options = get_option('booking_forms_options');
        $value = isset($options['email_config_type']) ? $options['email_config_type'] : 'wordpress';
        ?>
        <label>
            <input type="radio" name="booking_forms_options[email_config_type]" value="wordpress" <?php checked($value, 'wordpress'); ?>>
            Use WordPress Default Mail
        </label><br>
        <label>
            <input type="radio" name="booking_forms_options[email_config_type]" value="custom" <?php checked($value, 'custom'); ?>>
            Use Custom SMTP
        </label>
        <?php
    }
    
    public function smtp_host_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_host']) ? esc_attr($options['smtp_host']) : 'smtp.hostinger.com';
        echo '<input type="text" name="booking_forms_options[smtp_host]" value="' . $value . '" class="regular-text" ' . $disabled . '>';
    }
    
    public function smtp_port_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_port']) ? esc_attr($options['smtp_port']) : '587';
        echo '<input type="text" name="booking_forms_options[smtp_port]" value="' . $value . '" class="small-text" ' . $disabled . '>';
    }
    
    public function smtp_username_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_username']) ? esc_attr($options['smtp_username']) : '';
        echo '<input type="text" name="booking_forms_options[smtp_username]" value="' . $value . '" class="regular-text" ' . $disabled . '>';
    }
    
    public function smtp_password_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_password']) ? esc_attr($options['smtp_password']) : '';
        echo '<input type="password" name="booking_forms_options[smtp_password]" value="' . $value . '" class="regular-text" ' . $disabled . '>';
    }
    
    public function smtp_encryption_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_encryption']) ? $options['smtp_encryption'] : 'tls';
        ?>
        <select name="booking_forms_options[smtp_encryption]" <?php echo $disabled; ?>>
            <option value="tls" <?php selected($value, 'tls'); ?>>TLS</option>
            <option value="ssl" <?php selected($value, 'ssl'); ?>>SSL</option>
            <option value="none" <?php selected($value, 'none'); ?>>None</option>
        </select>
        <?php
    }
    
    public function smtp_from_email_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_from_email']) ? esc_attr($options['smtp_from_email']) : '';
        echo '<input type="email" name="booking_forms_options[smtp_from_email]" value="' . $value . '" class="regular-text" ' . $disabled . '>';
    }
    
    public function smtp_from_name_callback() {
        $options = get_option('booking_forms_options');
        $disabled = (isset($options['email_config_type']) && $options['email_config_type'] === 'wordpress') ? 'disabled' : '';
        $value = isset($options['smtp_from_name']) ? esc_attr($options['smtp_from_name']) : 'Penta Flights';
        echo '<input type="text" name="booking_forms_options[smtp_from_name]" value="' . $value . '" class="regular-text" ' . $disabled . '>';
    }
    
    /**
     * Admin Settings Page
     */
    public function admin_settings_page() {
        include BOOKING_FORMS_PATH . 'templates/admin-settings.php';
    }
    
    /**
     * Admin Bookings Page
     */
    public function admin_bookings_page() {
        include BOOKING_FORMS_PATH . 'templates/admin-bookings.php';
    }
}
