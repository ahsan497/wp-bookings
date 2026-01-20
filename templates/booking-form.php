<?php if (!defined('ABSPATH')) exit; ?>
<div class="booking-form-container">
    <div class="booking-tabs">
        <button class="tab-btn active" data-tab="flight">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
            </svg>Flight Booking
        </button>
        <button class="tab-btn" data-tab="hotel">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18M3 10h18M5 6h14a2 2 0 0 1 2 2v12H3V8a2 2 0 0 1 2-2z"/>
                <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6"/>
            </svg>Hotel Booking
        </button>
    </div>
    <div id="flight-tab" class="tab-content active">
        <div class="notification success" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg><span class="notification-message"></span>
        </div>
        <form id="flight-form" class="booking-form">
            <h3 class="section-title">Personal Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="flight-name">Full Name *</label>
                    <input type="text" id="flight-name" name="name" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label for="flight-email">Email Address *</label>
                    <input type="email" id="flight-email" name="email" required placeholder="your@email.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="flight-phone">Mobile Number with Country Code *</label>
                    <input type="tel" id="flight-phone" name="phone" required placeholder="+44 7123 456789">
                    <small class="field-hint">Include country code (e.g., +44 for UK)</small>
                </div>
                <div class="form-group">
                    <label for="flight-call-time">Best Time to Call *</label>
                    <select id="flight-call-time" name="call_time" required>
                        <option value="">Select Time</option>
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 5 PM)</option>
                        <option value="evening">Evening (5 PM - 8 PM)</option>
                        <option value="anytime">Anytime</option>
                    </select>
                </div>
            </div>
            <h3 class="section-title">Flight Details</h3>
            <div class="form-row">
                <div class="form-group autocomplete-wrapper">
                    <label for="flight-from">Departure From *</label>
                    <input type="text" id="flight-from" name="from" autocomplete="off" required placeholder="City or Airport">
                    <div class="autocomplete-dropdown"></div>
                </div>
                <div class="form-group autocomplete-wrapper">
                    <label for="flight-to">Destination *</label>
                    <input type="text" id="flight-to" name="to" autocomplete="off" required placeholder="City or Airport">
                    <div class="autocomplete-dropdown"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="flight-type">Trip Type *</label>
                    <select id="flight-type" name="trip_type" required>
                        <option value="">Select Trip Type</option>
                        <option value="one-way">One Way</option>
                        <option value="return">Return</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="flight-passengers">Number of Passengers *</label>
                    <select id="flight-passengers" name="passengers" required>
                        <option value="">Select Passengers</option>
                        <option value="1">1 Passenger</option>
                        <option value="2">2 Passengers</option>
                        <option value="3">3 Passengers</option>
                        <option value="4">4 Passengers</option>
                        <option value="5">5 Passengers</option>
                        <option value="6">6 Passengers</option>
                        <option value="7+">7+ Passengers</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="flight-departure">Departure Date *</label>
                    <input type="date" id="flight-departure" name="departure" required>
                </div>
                <div class="form-group" id="return-date-group" style="display:none;">
                    <label for="flight-return">Return Date *</label>
                    <input type="date" id="flight-return" name="return">
                </div>
            </div>
            <div class="form-group">
                <label for="flight-comments">Any Comments (Optional)</label>
                <textarea id="flight-comments" name="comments" rows="4" placeholder="Please let us know if you have any special requirements or preferences..."></textarea>
            </div>
            <button type="submit" class="submit-btn">
                <span class="btn-text"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>Submit Flight Booking Request</span>
                <span class="btn-loader" style="display:none;"><svg class="spinner" width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4" fill="none"/></svg>Processing...</span>
            </button>
        </form>
    </div>
    <div id="hotel-tab" class="tab-content">
        <div class="notification success" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg><span class="notification-message"></span>
        </div>
        <form id="hotel-form" class="booking-form">
            <h3 class="section-title">Personal Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="hotel-name">Full Name *</label>
                    <input type="text" id="hotel-name" name="name" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label for="hotel-email">Email Address *</label>
                    <input type="email" id="hotel-email" name="email" required placeholder="your@email.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="hotel-phone">Mobile Number with Country Code *</label>
                    <input type="tel" id="hotel-phone" name="phone" required placeholder="+44 7123 456789">
                    <small class="field-hint">Include country code (e.g., +44 for UK)</small>
                </div>
                <div class="form-group">
                    <label for="hotel-call-time">Best Time to Call *</label>
                    <select id="hotel-call-time" name="call_time" required>
                        <option value="">Select Time</option>
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 5 PM)</option>
                        <option value="evening">Evening (5 PM - 8 PM)</option>
                        <option value="anytime">Anytime</option>
                    </select>
                </div>
            </div>
            <h3 class="section-title">Hotel Details</h3>
            <div class="form-row">
                <div class="form-group autocomplete-wrapper">
                    <label for="hotel-destination">Destination *</label>
                    <input type="text" id="hotel-destination" name="destination" autocomplete="off" required placeholder="City or Location">
                    <div class="autocomplete-dropdown"></div>
                </div>
                <div class="form-group">
                    <label for="hotel-rooms">Number of Rooms *</label>
                    <select id="hotel-rooms" name="rooms" required>
                        <option value="">Select Rooms</option>
                        <option value="1">1 Room</option>
                        <option value="2">2 Rooms</option>
                        <option value="3">3 Rooms</option>
                        <option value="4">4 Rooms</option>
                        <option value="5">5 Rooms</option>
                        <option value="6+">6+ Rooms</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="hotel-checkin">Check-in Date *</label>
                    <input type="date" id="hotel-checkin" name="checkin" required>
                </div>
                <div class="form-group">
                    <label for="hotel-checkout">Check-out Date *</label>
                    <input type="date" id="hotel-checkout" name="checkout" required>
                </div>
            </div>
            <div class="form-group">
                <label for="hotel-comments">Any Comments (Optional)</label>
                <textarea id="hotel-comments" name="comments" rows="4" placeholder="Please let us know if you have any special requirements or preferences..."></textarea>
            </div>
            <button type="submit" class="submit-btn">
                <span class="btn-text"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>Submit Hotel Booking Request</span>
                <span class="btn-loader" style="display:none;"><svg class="spinner" width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4" fill="none"/></svg>Processing...</span>
            </button>
        </form>
    </div>
</div>
