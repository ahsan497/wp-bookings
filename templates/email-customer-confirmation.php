<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #ff6b35;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header h2 {
            margin: 0;
            font-size: 26px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .section {
            margin-bottom: 25px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .section h3 {
            color: #ff6b35;
            font-size: 16px;
            margin-bottom: 15px;
            margin-top: 0;
        }
        .field {
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 140px;
        }
        .value {
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 25px;
            color: #777;
            font-size: 13px;
            border-top: 1px solid #ddd;
        }
        .footer a {
            color: #ff6b35;
            text-decoration: none;
        }
        .contact-box {
            background: #fff5f2;
            padding: 15px;
            border-left: 4px solid #ff6b35;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✓ Booking Request Received</h2>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <?php echo esc_html($data['name']); ?>,
            </div>
            
            <div class="message">
                <p>Thank you for choosing Penta Flights! We have received your <?php echo $type === 'flight' ? 'flight' : 'hotel'; ?> booking request and our team will review it shortly.</p>
                <p>We will contact you at <strong><?php echo esc_html($data['phone']); ?></strong> or <strong><?php echo esc_html($data['email']); ?></strong> within 24 hours to confirm the details and provide you with the best options.</p>
            </div>

            <!-- Booking Summary -->
            <div class="section">
                <h3>Your Booking Request Summary</h3>
                
                <?php if ($type === 'flight'): ?>
                    <div class="field">
                        <span class="label">Route:</span>
                        <span class="value"><?php echo esc_html($data['from']); ?> → <?php echo esc_html($data['to']); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Trip Type:</span>
                        <span class="value"><?php echo esc_html(ucfirst(str_replace('-', ' ', $data['trip_type']))); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Departure:</span>
                        <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['departure']))); ?></span>
                    </div>
                    <?php if (!empty($data['return'])): ?>
                    <div class="field">
                        <span class="label">Return:</span>
                        <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['return']))); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="field">
                        <span class="label">Passengers:</span>
                        <span class="value"><?php echo esc_html($data['passengers']); ?></span>
                    </div>
                <?php else: ?>
                    <div class="field">
                        <span class="label">Destination:</span>
                        <span class="value"><?php echo esc_html($data['destination']); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Check-in:</span>
                        <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['checkin']))); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Check-out:</span>
                        <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['checkout']))); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Rooms:</span>
                        <span class="value"><?php echo esc_html($data['rooms']); ?></span>
                    </div>
                    <?php if (!empty($data['guests'])): ?>
                    <div class="field">
                        <span class="label">Guests:</span>
                        <span class="value"><?php echo esc_html($data['guests']); ?></span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="contact-box">
                <strong>What's Next?</strong>
                <p style="margin: 10px 0 0 0;">Our travel experts will contact you during your preferred time (<?php echo esc_html(ucfirst($data['call_time'])); ?>) to discuss the best options and finalize your booking.</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Need immediate assistance?</strong></p>
            <p>Contact us: <a href="mailto:info@pentaflights.co.uk">info@pentaflights.co.uk</a> | Phone: +92 XXX XXXXXXX</p>
            <p style="margin-top: 15px;">This is an automated confirmation email from Penta Flights</p>
            <p><a href="https://pentaflights.co.uk">pentaflights.co.uk</a></p>
        </div>
    </div>
</body>
</html>