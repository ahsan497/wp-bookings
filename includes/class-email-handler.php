<?php
/**
 * Email Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class Booking_Forms_Email_Handler {
    
    /**
     * Send booking notification email
     */
    public function send_booking_notification($booking_type, $data) {
        $options = get_option('booking_forms_options');
        
        add_action('phpmailer_init', array($this, 'configure_smtp'));
        
        $admin_email = isset($options['admin_email']) ? $options['admin_email'] : get_option('admin_email');
        
        // Admin email
        if ($booking_type === 'flight') {
            $subject = 'New Flight Booking Request - ' . $data['name'];
            $message = $this->get_flight_email_template($data);
        } else {
            $subject = 'New Hotel Booking Request - ' . $data['name'];
            $message = $this->get_hotel_email_template($data);
        }
        
        $headers = $this->get_email_headers();
        $headers[] = 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>';
        
        wp_mail($admin_email, $subject, $message, $headers);
        
        // Customer email
        $send_customer_email = isset($options['send_customer_email']) && $options['send_customer_email'];
        
        if ($send_customer_email) {
            $customer_subject = 'Booking Confirmation - Penta Flights';
            $customer_message = $this->get_customer_confirmation_template($data, $booking_type);
            
            wp_mail($data['email'], $customer_subject, $customer_message, $this->get_email_headers());
        }
        
        remove_action('phpmailer_init', array($this, 'configure_smtp'));
    }
    
    /**
     * Configure SMTP
     */
    public function configure_smtp($phpmailer) {
        $options = get_option('booking_forms_options');
        $config_type = isset($options['email_config_type']) ? $options['email_config_type'] : 'wordpress';
        
        if ($config_type !== 'custom') {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->Host = isset($options['smtp_host']) ? $options['smtp_host'] : 'smtp.hostinger.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = isset($options['smtp_port']) ? $options['smtp_port'] : 587;
        $phpmailer->Username = isset($options['smtp_username']) ? $options['smtp_username'] : '';
        $phpmailer->Password = isset($options['smtp_password']) ? $options['smtp_password'] : '';
        
        $encryption = isset($options['smtp_encryption']) ? $options['smtp_encryption'] : 'tls';
        if ($encryption !== 'none') {
            $phpmailer->SMTPSecure = $encryption;
        }
        
        if (!empty($options['smtp_from_email'])) {
            $phpmailer->From = $options['smtp_from_email'];
            $phpmailer->FromName = isset($options['smtp_from_name']) ? $options['smtp_from_name'] : 'Penta Flights';
        }
    }
    
    /**
     * Get email headers
     */
    private function get_email_headers() {
        $options = get_option('booking_forms_options');
        $config_type = isset($options['email_config_type']) ? $options['email_config_type'] : 'wordpress';
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        if ($config_type === 'custom' && !empty($options['smtp_from_email'])) {
            $from_email = $options['smtp_from_email'];
            $from_name = isset($options['smtp_from_name']) ? $options['smtp_from_name'] : 'Penta Flights';
            $headers[] = 'From: ' . $from_name . ' <' . $from_email . '>';
        } else {
            $headers[] = 'From: Penta Flights <noreply@pentaflights.co.uk>';
        }
        
        return $headers;
    }
    
    /**
     * Send test email
     */
    public function send_test_email($email) {
        add_action('phpmailer_init', array($this, 'configure_smtp'));
        
        $subject = 'Test Email from Penta Flights Booking Forms';
        $message = '<h2>Test Email</h2><p>This is a test email from your booking forms plugin. If you received this, your email configuration is working correctly!</p><p>Sent at: ' . current_time('mysql') . '</p>';
        
        $headers = $this->get_email_headers();
        
        $result = wp_mail($email, $subject, $message, $headers);
        
        remove_action('phpmailer_init', array($this, 'configure_smtp'));
        
        return $result;
    }
    
    /**
     * Flight email template
     */
    private function get_flight_email_template($data) {
        ob_start();
        include BOOKING_FORMS_PATH . 'templates/email-flight.php';
        return ob_get_clean();
    }
    
    /**
     * Hotel email template
     */
    private function get_hotel_email_template($data) {
        ob_start();
        include BOOKING_FORMS_PATH . 'templates/email-hotel.php';
        return ob_get_clean();
    }
    
    /**
     * Customer confirmation template
     */
    private function get_customer_confirmation_template($data, $type) {
        ob_start();
        include BOOKING_FORMS_PATH . 'templates/email-customer-confirmation.php';
        return ob_get_clean();
    }
}
