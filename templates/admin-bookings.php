<?php if (!defined('ABSPATH')) exit;
global $wpdb;
$db = new Booking_Forms_Database();

// Check if table exists first
if (!$db->table_exists()) {
    $create_url = wp_nonce_url(admin_url('admin.php?page=booking-forms&action=create_table'), 'create_booking_table');
    ?>
    <div class="wrap">
        <h1>Booking Forms</h1>
        <div class="notice notice-error" style="padding: 20px; margin: 20px 0;">
            <h2>⚠️ Database Table Missing</h2>
            <p>The bookings table doesn't exist in your database. This usually happens if the plugin activation didn't complete properly.</p>
            <h3>Quick Fix:</h3>
            <ol>
                <li><strong>Option 1:</strong> <a href="<?php echo $create_url; ?>" class="button button-primary button-hero">Create Table Now (Recommended)</a></li>
                <li><strong>Option 2:</strong> Deactivate and reactivate the plugin</li>
                <li><strong>Option 3:</strong> Run the SQL manually in phpMyAdmin (see below)</li>
            </ol>
            <h3>Manual SQL (if buttons don't work):</h3>
            <textarea readonly style="width: 100%; height: 200px; font-family: monospace; font-size: 12px; padding: 10px;">CREATE TABLE IF NOT EXISTS `<?php echo $wpdb->prefix; ?>bookings` (
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
) <?php echo $wpdb->get_charset_collate(); ?>;</textarea>
            <p style="margin-top: 15px;"><strong>Steps to run manually:</strong></p>
            <ol>
                <li>Copy the SQL code above</li>
                <li>Go to phpMyAdmin in your localhost</li>
                <li>Select your database: <strong><?php echo DB_NAME; ?></strong></li>
                <li>Click the "SQL" tab</li>
                <li>Paste the code and click "Go"</li>
                <li>Refresh this page</li>
            </ol>
        </div>
    </div>
    <?php
    return;
}

// Handle actions
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    check_admin_referer('delete_booking_' . $_GET['id']);
    $db->delete_booking(intval($_GET['id']));
    echo '<div class="notice notice-success"><p>Booking deleted successfully.</p></div>';
}

if (isset($_POST['update_status']) && isset($_POST['booking_id'])) {
    check_admin_referer('update_booking_status');
    $db->update_status(intval($_POST['booking_id']), sanitize_text_field($_POST['status']));
    echo '<div class="notice notice-success"><p>Booking status updated successfully.</p></div>';
}

// Get filters
$booking_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

$filters = array();
if ($booking_type) $filters['type'] = $booking_type;
if ($status) $filters['status'] = $status;

$bookings = $db->get_bookings($filters);
$stats = $db->get_statistics();
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Booking Entries</h1>
    <a href="admin.php?page=booking-forms-settings" class="page-title-action">Settings</a>
    <hr class="wp-header-end">
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
        <div style="background: #fff; padding: 20px; border-left: 4px solid #2271b1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0;">Total Bookings</h3>
            <p style="font-size: 32px; font-weight: bold; margin: 0; color: #2271b1;"><?php echo $stats['total']; ?></p>
        </div>
        <div style="background: #fff; padding: 20px; border-left: 4px solid #00a32a; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0;">Flight Bookings</h3>
            <p style="font-size: 32px; font-weight: bold; margin: 0; color: #00a32a;"><?php echo $stats['flight']; ?></p>
        </div>
        <div style="background: #fff; padding: 20px; border-left: 4px solid #f0b849; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0;">Hotel Bookings</h3>
            <p style="font-size: 32px; font-weight: bold; margin: 0; color: #f0b849;"><?php echo $stats['hotel']; ?></p>
        </div>
        <div style="background: #fff; padding: 20px; border-left: 4px solid #d63638; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0;">Pending</h3>
            <p style="font-size: 32px; font-weight: bold; margin: 0; color: #d63638;"><?php echo $stats['pending']; ?></p>
        </div>
    </div>
    
    <div class="tablenav top">
        <form method="get" style="display: inline-block;">
            <input type="hidden" name="page" value="booking-forms">
            <select name="type" style="margin-right: 10px;">
                <option value="">All Types</option>
                <option value="flight" <?php selected($booking_type, 'flight'); ?>>Flight</option>
                <option value="hotel" <?php selected($booking_type, 'hotel'); ?>>Hotel</option>
            </select>
            <select name="status" style="margin-right: 10px;">
                <option value="">All Status</option>
                <option value="pending" <?php selected($status, 'pending'); ?>>Pending</option>
                <option value="confirmed" <?php selected($status, 'confirmed'); ?>>Confirmed</option>
                <option value="completed" <?php selected($status, 'completed'); ?>>Completed</option>
                <option value="cancelled" <?php selected($status, 'cancelled'); ?>>Cancelled</option>
            </select>
            <input type="submit" value="Filter" class="button">
            <?php if ($booking_type || $status): ?>
                <a href="admin.php?page=booking-forms" class="button">Clear Filters</a>
            <?php endif; ?>
        </form>
    </div>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th style="width: 80px;">Type</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 150px;">Date</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookings)): ?>
                <tr><td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="font-size: 16px; color: #666;">No bookings found.</p>
                </td></tr>
            <?php else: foreach ($bookings as $booking): 
                $status_colors = array('pending' => '#d63638', 'confirmed' => '#00a32a', 'completed' => '#2271b1', 'cancelled' => '#999');
                $color = isset($status_colors[$booking->status]) ? $status_colors[$booking->status] : '#999';
            ?>
                <tr>
                    <td><?php echo $booking->id; ?></td>
                    <td><span class="dashicons dashicons-<?php echo $booking->booking_type === 'flight' ? 'airplane' : 'building'; ?>"></span> <?php echo ucfirst($booking->booking_type); ?></td>
                    <td><strong><?php echo esc_html($booking->customer_name); ?></strong></td>
                    <td><?php echo esc_html($booking->customer_email); ?></td>
                    <td><?php echo esc_html($booking->customer_phone); ?></td>
                    <td><span style="background: <?php echo $color; ?>; color: #fff; padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: bold;"><?php echo ucfirst($booking->status); ?></span></td>
                    <td><?php echo date('M j, Y g:i A', strtotime($booking->created_at)); ?></td>
                    <td>
                        <button type="button" class="button button-small" onclick="viewBooking(<?php echo $booking->id; ?>)">View</button>
                        <button type="button" class="button button-small" onclick="updateStatus(<?php echo $booking->id; ?>, '<?php echo $booking->status; ?>')">Status</button>
                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=booking-forms&action=delete&id=' . $booking->id), 'delete_booking_' . $booking->id); ?>" class="button button-small button-link-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<div id="booking-modal" style="display: none; position: fixed; z-index: 100000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7);">
    <div style="background: #fff; margin: 50px auto; padding: 30px; width: 90%; max-width: 700px; border-radius: 8px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Booking Details</h2>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <div id="booking-details"></div>
    </div>
</div>
<div id="status-modal" style="display: none; position: fixed; z-index: 100000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7);">
    <div style="background: #fff; margin: 200px auto; padding: 30px; width: 90%; max-width: 400px; border-radius: 8px;">
        <h2>Update Booking Status</h2>
        <form method="post">
            <?php wp_nonce_field('update_booking_status'); ?>
            <input type="hidden" name="booking_id" id="status-booking-id">
            <p><label><strong>Select Status:</strong></label><br>
            <select name="status" id="status-select" style="width: 100%; padding: 8px; margin-top: 10px;">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select></p>
            <p><button type="submit" name="update_status" class="button button-primary">Update Status</button>
            <button type="button" onclick="closeStatusModal()" class="button">Cancel</button></p>
        </form>
    </div>
</div>
<script>
var bookingsData = <?php echo json_encode($bookings); ?>;
function viewBooking(id) {
    var booking = bookingsData.find(b => b.id == id);
    var data = JSON.parse(booking.booking_data);
    var html = '<div style="background: #f9f9f9; padding: 20px; border-radius: 8px;"><h3 style="margin-top: 0; color: #ff6b35;">Customer Information</h3>';
    html += '<p><strong>Name:</strong> ' + booking.customer_name + '</p><p><strong>Email:</strong> ' + booking.customer_email + '</p><p><strong>Phone:</strong> ' + booking.customer_phone + '</p>';
    html += '<h3 style="margin-top: 30px; color: #ff6b35;">Contact Preferences</h3><p><strong>Best Time to Call:</strong> ' + data.call_time.charAt(0).toUpperCase() + data.call_time.slice(1) + '</p>';
    html += '<h3 style="margin-top: 30px; color: #ff6b35;">' + (booking.booking_type === 'flight' ? 'Flight' : 'Hotel') + ' Details</h3>';
    if (booking.booking_type === 'flight') {
        html += '<p><strong>From:</strong> ' + data.from + '</p><p><strong>To:</strong> ' + data.to + '</p><p><strong>Trip Type:</strong> ' + data.trip_type + '</p><p><strong>Departure:</strong> ' + data.departure + '</p>';
        if (data.return) html += '<p><strong>Return:</strong> ' + data.return + '</p>';
        html += '<p><strong>Passengers:</strong> ' + data.passengers + '</p>';
        if (data.comments) html += '<p><strong>Comments:</strong> ' + data.comments.replace(/\n/g, '<br>') + '</p>';
    } else {
        html += '<p><strong>Destination:</strong> ' + data.destination + '</p><p><strong>Check-in:</strong> ' + data.checkin + '</p><p><strong>Check-out:</strong> ' + data.checkout + '</p><p><strong>Rooms:</strong> ' + data.rooms + '</p>';
        if (data.comments) html += '<p><strong>Comments:</strong> ' + data.comments.replace(/\n/g, '<br>') + '</p>';
    }
    html += '</div>';
    document.getElementById('booking-details').innerHTML = html;
    document.getElementById('booking-modal').style.display = 'block';
}
function closeModal() { document.getElementById('booking-modal').style.display = 'none'; }
function updateStatus(id, currentStatus) {
    document.getElementById('status-booking-id').value = id;
    document.getElementById('status-select').value = currentStatus;
    document.getElementById('status-modal').style.display = 'block';
}
function closeStatusModal() { document.getElementById('status-modal').style.display = 'none'; }
window.onclick = function(event) {
    if (event.target.id === 'booking-modal') closeModal();
    if (event.target.id === 'status-modal') closeStatusModal();
}
</script>
