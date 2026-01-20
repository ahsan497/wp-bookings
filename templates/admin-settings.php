<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap">
    <h1>Booking Forms Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('booking_forms_settings'); do_settings_sections('booking-forms-settings'); submit_button('Save Settings'); ?>
    </form>
    <hr>
    <h2>Test Email Configuration</h2>
    <p>Send a test email to verify your email settings are working correctly.</p>
    <form method="post" action="">
        <?php wp_nonce_field('booking_forms_test_email', 'test_email_nonce'); ?>
        <input type="email" name="test_email" placeholder="Enter test email address" class="regular-text" required>
        <input type="submit" name="send_test_email" value="Send Test Email" class="button button-secondary">
    </form>
    <?php
    if (isset($_POST['send_test_email']) && check_admin_referer('booking_forms_test_email', 'test_email_nonce')) {
        $test_email = sanitize_email($_POST['test_email']);
        $email_handler = new Booking_Forms_Email_Handler();
        $result = $email_handler->send_test_email($test_email);
        if ($result) {
            echo '<div class="notice notice-success"><p>✅ Test email sent successfully to ' . esc_html($test_email) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>❌ Failed to send test email. Please check your SMTP settings.</p></div>';
        }
    }
    ?>
</div>
<script>
jQuery(document).ready(function($) {
    $('input[name="booking_forms_options[email_config_type]"]').change(function() {
        var isCustom = $(this).val() === 'custom';
        $('#booking_forms_smtp_section').find('input, select').prop('disabled', !isCustom);
    });
});
</script>
