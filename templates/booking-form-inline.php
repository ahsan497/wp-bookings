<?php if (!defined('ABSPATH')) exit; ?>
<div class="bkf-container">
    <div class="bkf-tabs">
        <button class="bkf-tab-btn bkf-active" data-tab="flight">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
            </svg>
            Flight
        </button>
        <button class="bkf-tab-btn" data-tab="hotel">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18M3 10h18M5 6h14a2 2 0 0 1 2 2v12H3V8a2 2 0 0 1 2-2z"/>
                <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6"/>
            </svg>
            Hotel
        </button>
    </div>

    <!-- Flight Form Tab -->
    <div id="flight-tab" class="bkf-tab-content bkf-active">
        <div class="bkf-notification bkf-success" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg><span class="bkf-notification-message"></span>
        </div>
        
        <form id="flight-form" class="bkf-form">
            <!-- Trip Type Section - Above Form Rows -->
            <div class="bkf-trip-type-section">
                <div class="bkf-radio-container">
                    <label>Trip Type</label>
                    <div class="bkf-radio-group">
                        <label class="bkf-radio-label">
                            <input type="radio" name="trip_type" value="return" checked>
                            <span>Return</span>
                        </label>
                        <label class="bkf-radio-label">
                            <input type="radio" name="trip_type" value="one-way">
                            <span>One Way</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Row 1: Flight Details -->
            <div class="bkf-row">
                <div class="bkf-field bkf-field-medium bkf-autocomplete">
                    <label>Departure</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input type="text" id="flight-from" name="from" autocomplete="off" required placeholder="Leaving from">
                    </div>
                    <div class="bkf-autocomplete-dropdown"></div>
                </div>

                <div class="bkf-field bkf-field-medium bkf-autocomplete">
                    <label>Destination</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input type="text" id="flight-to" name="to" autocomplete="off" required placeholder="Going to">
                    </div>
                    <div class="bkf-autocomplete-dropdown"></div>
                </div>

                <div class="bkf-field">
                    <label>Departure Date</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" id="flight-departure" name="departure" required>
                    </div>
                </div>

                <div class="bkf-field" id="return-date-group">
                    <label>Return Date</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" id="flight-return" name="return">
                    </div>
                </div>

                <div class="bkf-field bkf-field-small">
                    <label>Passengers</label>
                    <select id="flight-passengers" name="passengers" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10+">10+</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Contact Information -->
            <div class="bkf-row">
                <div class="bkf-field bkf-field-medium">
                    <label>Name</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="flight-name" name="name" required placeholder="Your full name">
                    </div>
                </div>

                <div class="bkf-field bkf-field-medium">
                    <label>Email</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="flight-email" name="email" required placeholder="your@email.com">
                    </div>
                </div>

                <div class="bkf-field">
                    <label>Phone Number</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="tel" id="flight-phone" name="phone" required placeholder="+44 7700 900123">
                    </div>
                </div>

                <div class="bkf-field">
                    <label>Best Time to Call</label>
                    <select name="call_time" id="flight-call-time">
                        <option value="anytime">Anytime</option>
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 5 PM)</option>
                        <option value="evening">Evening (5 PM - 9 PM)</option>
                    </select>
                </div>

                <div class="bkf-field bkf-field-wide">
                    <label>Comments (Optional)</label>
                    <textarea id="flight-comments" name="comments" rows="1" placeholder="Any special requests or additional information..."></textarea>
                </div>

                <button type="submit" class="bkf-submit">
                    <span class="bkf-btn-text">Search Flights</span>
                    <span class="bkf-btn-loader" style="display:none;">
                        <svg class="bkf-spinner" width="18" height="18" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/>
                            <path d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4" fill="none"/>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Hotel Form Tab -->
    <div id="hotel-tab" class="bkf-tab-content">
        <div class="bkf-notification bkf-success" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg><span class="bkf-notification-message"></span>
        </div>
        
        <form id="hotel-form" class="bkf-form">
            <!-- Row 1: Hotel Details -->
            <div class="bkf-row">
                <div class="bkf-field bkf-field-medium bkf-autocomplete">
                    <label>Destination</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input type="text" id="hotel-destination" name="destination" autocomplete="off" required placeholder="City or hotel name">
                    </div>
                    <div class="bkf-autocomplete-dropdown"></div>
                </div>

                <div class="bkf-field">
                    <label>Check-in Date</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" id="hotel-checkin" name="checkin" required>
                    </div>
                </div>

                <div class="bkf-field">
                    <label>Check-out Date</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" id="hotel-checkout" name="checkout" required>
                    </div>
                </div>

                <div class="bkf-field bkf-field-small">
                    <label>Rooms</label>
                    <select id="hotel-rooms" name="rooms" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6+">6+</option>
                    </select>
                </div>

                <div class="bkf-field bkf-field-small">
                    <label>Guests</label>
                    <select id="hotel-guests" name="guests" required>
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7+">7+</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Contact Information -->
            <div class="bkf-row">
                <div class="bkf-field bkf-field-medium">
                    <label>Name</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="hotel-name" name="name" required placeholder="Your full name">
                    </div>
                </div>

                <div class="bkf-field bkf-field-medium">
                    <label>Email</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="hotel-email" name="email" required placeholder="your@email.com">
                    </div>
                </div>

                <div class="bkf-field">
                    <label>Phone Number</label>
                    <div class="bkf-input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="tel" id="hotel-phone" name="phone" required placeholder="+44 7700 900123">
                    </div>
                </div>

                <div class="bkf-field">
                    <label>Best Time to Call</label>
                    <select name="call_time" id="hotel-call-time">
                        <option value="anytime">Anytime</option>
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 5 PM)</option>
                        <option value="evening">Evening (5 PM - 9 PM)</option>
                    </select>
                </div>

                <div class="bkf-field bkf-field-wide">
                    <label>Comments (Optional)</label>
                    <textarea id="hotel-comments" name="comments" rows="1" placeholder="Any special requests or preferences..."></textarea>
                </div>

                <button type="submit" class="bkf-submit">
                    <span class="bkf-btn-text">Search Hotels</span>
                    <span class="bkf-btn-loader" style="display:none;">
                        <svg class="bkf-spinner" width="18" height="18" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/>
                            <path d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4" fill="none"/>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>