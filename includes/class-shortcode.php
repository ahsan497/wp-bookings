<?php
/**
 * Shortcode Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class Booking_Forms_Shortcode {
    
    public function __construct() {
        add_shortcode('booking_form', array($this, 'render_booking_form'));
    }
    
    /**
     * Render booking form shortcode
     */
    public function render_booking_form() {
        ob_start();
        include BOOKING_FORMS_PATH . 'templates/booking-form-inline.php';
        return ob_get_clean();
    }
}
