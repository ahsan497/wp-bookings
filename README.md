# 🎫 Professional Booking Forms for WordPress

<div align="center">

![Version](https://img.shields.io/badge/version-1.0.1-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0+-green.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0-red.svg)

**A powerful, modern booking form plugin for WordPress with flight and hotel booking capabilities**

[Features](#-features) • [Installation](#-installation) • [Configuration](#️-configuration) • [Support](#-support)

</div>

---

## 👨‍💻 Author

<div align="center">

**Ahsan Tariq**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/ahsan-tariq528/)
[![Email](https://img.shields.io/badge/Email-Contact-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:ahsantariq497@gmail.com)

*Full Stack WordPress Developer | Plugin Development Specialist*

</div>

---

## ✨ Features

- ✅ **Dual Booking Forms** - Separate forms for flights and hotels
- ✅ **AJAX Submissions** - Smooth, no-page-reload experience
- ✅ **Email Notifications** - Automatic emails to admin and customers
- ✅ **Airport Autocomplete** - Smart search with custom JSON support
- ✅ **Two Layout Styles** - Vertical and horizontal inline layouts
- ✅ **Admin Dashboard** - Manage all bookings from WordPress
- ✅ **Responsive Design** - Perfect on all devices
- ✅ **Custom SMTP Support** - Professional email delivery

---

## 🆕 What's New in v1.0.1

### Bug Fixes
- ✅ Fixed database table creation issue
- ✅ Added one-click table creation button
- ✅ Improved error logging and diagnostics

### New Features
- ✨ Horizontal inline layout (no scrolling!)
- ✨ Radio buttons for trip type selection
- ✨ Custom airport JSON format support
- ✨ Icon-enhanced input fields

### Improvements
- 🔧 Modularized code (5 organized classes)
- 🔧 Enhanced mobile responsiveness
- 🔧 Better table existence verification

---

## 📁 File Structure

```
professional-booking-forms/
├── professional-booking-forms.php    Main plugin file
├── includes/
│   ├── class-database.php           Database operations
│   ├── class-email-handler.php      Email functionality
│   ├── class-admin.php              Admin interface
│   └── class-shortcode.php          Shortcode handler
├── assets/
│   ├── style.css                    Vertical layout
│   ├── style-inline.css             Horizontal layout
│   └── script.js                    Form interactions
└── templates/
    ├── booking-form.php             Vertical form
    ├── booking-form-inline.php      Horizontal form
    ├── admin-settings.php           Settings page
    ├── admin-bookings.php           Bookings management
    └── email-*.php                  Email templates
```

---

## 🚀 Installation

### Quick Install (5 Minutes)

1. **Upload Plugin**
   - Go to: WordPress Admin → Plugins → Add New → Upload Plugin
   - Choose ZIP file → Install → Activate

2. **Create Table** (automatic, or use one-click button if needed)
   - Go to: Admin → Bookings
   - Click "Create Table Now" if prompted

3. **Configure Email**
   - Go to: Admin → Bookings → Settings
   - Enter admin email
   - Choose WordPress Mail or Custom SMTP

4. **Add Airport Data**
   - Upload `airports.json` to `/wp-content/uploads/`

5. **Add to Page**
   - Add shortcode: `[booking_form]`

---

## ⚙️ Configuration

### Email Settings

**WordPress Default Mail** (Easy setup)
- Works on most servers
- No configuration needed

**Custom SMTP** (Professional)
- Better deliverability
- For Hostinger:
  - Host: `smtp.hostinger.com`
  - Port: `587`
  - Encryption: `TLS`

### Layout Options

**Vertical Layout** - Traditional multi-section form  
**Horizontal Layout** - Single-row compact design (see `INLINE-LAYOUT-GUIDE.md`)

### Airport JSON Format

```json
[
  {"LHR - London - United Kingdom": "JFK - New York - USA"},
  {"MAN - Manchester - United Kingdom": "DXB - Dubai - UAE"}
]
```

Upload to `/wp-content/uploads/airports.json`

---

## 🎨 Customization

### Change Colors
Edit `/assets/style.css`:

```css
.submit-btn { background: #ff6b35; }  /* Primary button */
.submit-btn:hover { background: #e55a28; }  /* Hover */
.tab-btn.active { border-bottom-color: #ff6b35; }  /* Active tab */
```

### Add Custom Fields
Edit `/templates/booking-form.php`:

```html
<div class="form-group">
    <label>Custom Field</label>
    <input type="text" name="custom_field" required>
</div>
```

---

## 🐛 Troubleshooting

### Database Table Not Created

**Solution 1: Admin Button**
1. Go to Admin → Bookings
2. Click "Create Table Now"

**Solution 2: phpMyAdmin**
```sql
CREATE TABLE `wp_bookings` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `booking_type` varchar(20) NOT NULL,
  `booking_data` longtext NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Emails Not Sending

1. Test with "Check Email" plugin
2. Verify SMTP credentials
3. Check spam folder
4. Try port 465/SSL instead of 587/TLS

### Autocomplete Not Working

1. Verify `airports.json` in `/wp-content/uploads/`
2. Check JSON format validity
3. Clear browser cache
4. Check browser console for errors

---

## 📚 Documentation

- 📖 [Installation Guide](INSTALLATION.md)
- 📖 [Inline Layout Guide](INLINE-LAYOUT-GUIDE.md)
- 📖 [Localhost Troubleshooting](LOCALHOST-TROUBLESHOOTING.md)

---

## 🔒 Security Features

- ✅ CSRF Protection (WordPress nonces)
- ✅ SQL Injection Prevention (prepared statements)
- ✅ XSS Protection (sanitized output)
- ✅ Input Validation (server-side)
- ✅ Role-Based Access Control

---

## 📊 Requirements

**Minimum:**
- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+

**Recommended:**
- WordPress 6.0+
- PHP 8.0+
- MySQL 8.0+

---

## 🤝 Support

<div align="center">

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Message_Me-0077B5?style=for-the-badge&logo=linkedin)](https://www.linkedin.com/in/ahsan-tariq528/)
[![Email](https://img.shields.io/badge/Email-Contact-D14836?style=for-the-badge&logo=gmail)](mailto:ahsantariq497@gmail.com)

</div>

**When requesting help, include:**
- WordPress, PHP, and MySQL versions
- Error messages and screenshots
- What you've already tried

---

## 📝 Changelog

### Version 1.0.1 (January 2026)
**Bug Fixes:**
- Fixed database table creation on activation
- Resolved AJAX submission errors
- Corrected autocomplete positioning

**New Features:**
- Horizontal inline layout option
- Airport JSON custom format support
- One-click table creation button

**Improvements:**
- Modularized code structure
- Enhanced mobile responsiveness
- Better error logging

### Version 1.0.0 (December 2025)
- Initial release

---

## 📄 License

GPL-2.0 License - Free to use and modify

---

## 🎯 Roadmap

**Planned for v1.1.0:**
- Payment gateway integration
- Multi-currency support
- Booking calendar view
- CSV export functionality
- Multi-language support

---

<div align="center">

**Made with ❤️ by [Ahsan Tariq](https://www.linkedin.com/in/ahsan-tariq528/)**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Follow-0077B5?style=social&logo=linkedin)](https://www.linkedin.com/in/ahsan-tariq528/)

⭐ **Star this repository if it helped you!**

</div>
