/**
 * Booking Forms JavaScript - Updated for 2 Row Layout with bkf- prefix
 */

jQuery(document).ready(function($) {
    let airports = [];

    // Load airports from JSON file
    const airportsUrl = bookingAjax.airports_url || '/wp-content/uploads/airports.json';
    
    $.ajax({
        url: airportsUrl,
        dataType: 'json',
        success: function(data) {
            airports = parseAirportsData(data);
            console.log('Airports loaded:', airports.length);
        },
        error: function() {
            console.warn('Airports JSON not found, using demo data');
            // Fallback demo data
            airports = [
                { code: 'LHR', city: 'London', name: 'Heathrow Airport', country: 'UK' },
                { code: 'LGW', city: 'London', name: 'Gatwick Airport', country: 'UK' },
                { code: 'MAN', city: 'Manchester', name: 'Manchester Airport', country: 'UK' },
                { code: 'JFK', city: 'New York', name: 'John F Kennedy International', country: 'USA' },
                { code: 'LAX', city: 'Los Angeles', name: 'Los Angeles International', country: 'USA' },
                { code: 'DXB', city: 'Dubai', name: 'Dubai International', country: 'UAE' }
            ];
        }
    });

    /**
     * Parse airports data from JSON format
     */
    function parseAirportsData(data) {
        const parsed = [];
        
        data.forEach(item => {
            Object.entries(item).forEach(([key, value]) => {
                // Parse the key (source airport)
                const keyParts = key.split(' - ');
                if (keyParts.length >= 3) {
                    parsed.push({
                        code: keyParts[0].trim(),
                        city: keyParts[1].trim(),
                        country: keyParts[2].trim(),
                        name: keyParts[1].trim() + ' Airport'
                    });
                }
                
                // Parse the value (destination airport)
                const valueParts = value.split(' - ');
                if (valueParts.length >= 3) {
                    const exists = parsed.some(a => a.code === valueParts[0].trim());
                    if (!exists) {
                        parsed.push({
                            code: valueParts[0].trim(),
                            city: valueParts[1].trim(),
                            country: valueParts[2].trim(),
                            name: valueParts[1].trim() + ' Airport'
                        });
                    }
                }
            });
        });
        
        // Remove duplicates and sort
        const unique = Array.from(new Map(parsed.map(item => [item.code, item])).values());
        return unique.sort((a, b) => a.city.localeCompare(b.city));
    }

    // Tab switching functionality
    $('.bkf-tab-btn').on('click', function() {
        const tabId = $(this).data('tab');
        
        $('.bkf-tab-btn').removeClass('bkf-active');
        $(this).addClass('bkf-active');
        
        $('.bkf-tab-content').removeClass('bkf-active');
        $('#' + tabId + '-tab').addClass('bkf-active');
    });

    // Autocomplete setup function
    function setupAutocomplete(inputId) {
        const $input = $('#' + inputId);
        const $dropdown = $input.closest('.bkf-autocomplete').find('.bkf-autocomplete-dropdown');

        $input.on('input', function() {
            const value = $(this).val().toLowerCase().trim();
            
            if (value.length < 2) {
                $dropdown.hide().empty();
                return;
            }

            // Filter airports
            const matches = airports.filter(airport =>
                airport.city.toLowerCase().includes(value) ||
                airport.code.toLowerCase().includes(value) ||
                airport.name.toLowerCase().includes(value) ||
                airport.country.toLowerCase().includes(value)
            ).slice(0, 8);

            // Display matches
            if (matches.length > 0) {
                const html = matches.map(airport => 
                    `<div class="bkf-autocomplete-item" data-value="${airport.city} (${airport.code})">
                        <strong>${airport.city}</strong> (${airport.code})
                        <div class="bkf-airport-details">${airport.name}, ${airport.country}</div>
                    </div>`
                ).join('');
                
                $dropdown.html(html).show();
            } else {
                $dropdown.hide().empty();
            }
        });

        // Select airport from dropdown
        $dropdown.on('click', '.bkf-autocomplete-item', function() {
            const value = $(this).data('value');
            $input.val(value);
            $dropdown.hide().empty();
        });
    }

    // Initialize autocomplete
    setupAutocomplete('flight-from');
    setupAutocomplete('flight-to');
    setupAutocomplete('hotel-destination');

    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.bkf-autocomplete').length) {
            $('.bkf-autocomplete-dropdown').hide().empty();
        }
    });

    // Form submission handler
    function handleFormSubmit(formId, bookingType) {
        $('#' + formId).on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $btn = $form.find('.bkf-submit');
            const $notification = $form.closest('.bkf-tab-content').find('.bkf-notification');
            
            // Serialize form data
            const formData = {};
            $form.serializeArray().forEach(function(field) {
                formData[field.name] = field.value;
            });

            // Show loading state
            $btn.prop('disabled', true);
            $btn.find('.bkf-btn-text').hide();
            $btn.find('.bkf-btn-loader').show();
            $notification.hide();

            // AJAX request
            $.ajax({
                url: bookingAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'submit_booking',
                    nonce: bookingAjax.nonce,
                    booking_type: bookingType,
                    form_data: formData
                },
                success: function(response) {
                    if (response.success) {
                        $notification
                            .removeClass('bkf-error')
                            .addClass('bkf-success')
                            .find('.bkf-notification-message')
                            .text(response.data.message);
                        
                        $notification.fadeIn();
                        $form[0].reset();
                        
                        $('html, body').animate({
                            scrollTop: $notification.offset().top - 100
                        }, 500);
                        
                        setTimeout(function() {
                            $notification.fadeOut();
                        }, 5000);
                    } else {
                        $notification
                            .removeClass('bkf-success')
                            .addClass('bkf-error')
                            .find('.bkf-notification-message')
                            .text(response.data.message || 'An error occurred. Please try again.');
                        
                        $notification.fadeIn();
                    }
                },
                error: function() {
                    $notification
                        .removeClass('bkf-success')
                        .addClass('bkf-error')
                        .find('.bkf-notification-message')
                        .text('Network error. Please check your connection and try again.');
                    
                    $notification.fadeIn();
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btn.find('.bkf-btn-text').show();
                    $btn.find('.bkf-btn-loader').hide();
                }
            });
        });
    }

    // Trip type change handler - show/hide return date
    $('input[name="trip_type"]').on('change', function() {
        const tripType = $(this).val();
        if (tripType === 'return') {
            $('#return-date-group').show();
            $('#flight-return').prop('required', true);
        } else {
            $('#return-date-group').hide();
            $('#flight-return').prop('required', false).val('');
        }
    });

    // Initialize form handlers
    handleFormSubmit('flight-form', 'flight');
    handleFormSubmit('hotel-form', 'hotel');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);

    // Return date validation
    $('#flight-departure').on('change', function() {
        $('#flight-return').attr('min', $(this).val());
    });

    // Checkout date validation
    $('#hotel-checkin').on('change', function() {
        $('#hotel-checkout').attr('min', $(this).val());
    });

    // Date validation on blur
    $('input[type="date"]').on('blur', function() {
        const $this = $(this);
        const min = $this.attr('min');
        const value = $this.val();
        
        if (min && value && value < min) {
            alert('Please select a valid date.');
            $this.val('');
        }
    });

    // Phone number formatting (allow +, spaces, numbers, dashes, parentheses)
    $('input[type="tel"]').on('input', function() {
        // Allow international format: +, spaces, numbers, dashes, parentheses
        this.value = this.value.replace(/[^0-9+\s\-()]/g, '');
    });

    // Auto-resize textarea
    $('textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});