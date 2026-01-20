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
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            color: #ff6b35;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ff6b35;
        }
        .field {
            margin-bottom: 12px;
            padding: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 150px;
        }
        .value {
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }
        .footer a {
            color: #ff6b35;
            text-decoration: none;
        }
        .timestamp {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✈️ New Flight Booking Request</h2>
        </div>
        
        <div class="content">
            <!-- Customer Information -->
            <div class="section">
                <h3>Customer Information</h3>
                <div class="field">
                    <span class="label">Name:</span>
                    <span class="value"><?php echo esc_html($data['name']); ?></span>
                </div>
                <div class="field">
                    <span class="label">Email:</span>
                    <span class="value"><a href="mailto:<?php echo esc_attr($data['email']); ?>"><?php echo esc_html($data['email']); ?></a></span>
                </div>
                <div class="field">
                    <span class="label">Phone:</span>
                    <span class="value"><a href="tel:<?php echo esc_attr($data['phone']); ?>"><?php echo esc_html($data['phone']); ?></a></span>
                </div>
                <div class="field">
                    <span class="label">Best Time to Call:</span>
                    <span class="value"><?php echo esc_html(ucfirst(str_replace('-', ' ', $data['call_time']))); ?></span>
                </div>
            </div>

            <!-- Flight Details -->
            <div class="section">
                <h3>Flight Details</h3>
                <div class="field">
                    <span class="label">From:</span>
                    <span class="value"><?php echo esc_html($data['from']); ?></span>
                </div>
                <div class="field">
                    <span class="label">To:</span>
                    <span class="value"><?php echo esc_html($data['to']); ?></span>
                </div>
                <div class="field">
                    <span class="label">Trip Type:</span>
                    <span class="value"><?php echo esc_html(ucfirst(str_replace('-', ' ', $data['trip_type']))); ?></span>
                </div>
                <div class="field">
                    <span class="label">Departure Date:</span>
                    <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['departure']))); ?></span>
                </div>
                <?php if (!empty($data['return'])): ?>
                <div class="field">
                    <span class="label">Return Date:</span>
                    <span class="value"><?php echo esc_html(date('F j, Y', strtotime($data['return']))); ?></span>
                </div>
                <?php endif; ?>
                <div class="field">
                    <span class="label">Passengers:</span>
                    <span class="value"><?php echo esc_html($data['passengers']); ?></span>
                </div>
                <?php if (!empty($data['airline'])): ?>
                <div class="field">
                    <span class="label">Preferred Airline:</span>
                    <span class="value"><?php echo esc_html($data['airline']); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Additional Information -->
            <?php if (!empty($data['comments'])): ?>
            <div class="section">
                <h3>Additional Comments</h3>
                <div class="field">
                    <span class="value"><?php echo nl2br(esc_html($data['comments'])); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Timestamp -->
            <div class="timestamp">
                <p><strong>Submitted:</strong> <?php echo current_time('F j, Y g:i A'); ?></p>
            </div>
        </div>
        
        <div class="footer">
            <p>Automated notification from Penta Flights Booking System</p>
            <p><a href="https://pentaflights.co.uk">pentaflights.co.uk</a></p>
        </div>
    </div>
</body>
</html>