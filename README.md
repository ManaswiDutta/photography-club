# Vidyamandira Photography Club Website 📸✨

A premium, high-performance web application designed for a college photography club. This platform features a zero-storage "Link & Stream" architecture for Google Photos integration, immersive parallax effects, and a robust administrative control center.

## 🌟 Key Features

### 🖼️ Zero-Storage "Link & Stream" Mode
- **CDN-First Architecture**: Images stream directly from Google's high-speed CDN (`lh3.googleusercontent.com`), saving gigabytes of server storage while maintaining lightning-fast performance.
- **Auto-Thumbnailing**: Automatically downloads exactly one local "cover image" per event for instant dashboard and landing page rendering.
- **Deep Share Integration**: Individual photos in the gallery feature deep links back to the original Google Photos high-res view.
- **Hotlinking Protection Bypass**: Implements an intelligent `no-referrer` policy to ensure seamless cross-origin image streaming.

### 🎭 Visual & Interactive Excellence
- **Natural Parallax Engine**: A custom-built CSS/JS parallax system where the background moves in sync with page content, creating professional depth.
- **Branded Saffron Aesthetic**: A bespoke design system inspired by RKM Vidyamandira's colors, using glassmorphism, fluid animations (reveal-on-scroll), and custom typography.
- **Responsive Mastery**: Fully optimized for everything from ultra-wide desktops to small mobile touchscreens, including a mobile-friendly slide-out admin menu.

### 🛠️ Administrative Control Center
- **Dual-Mode Importer**: Supports both traditional folder-based uploads and high-speed Google Photos album ingestion.
- **Bulk Scraper**: A multi-threaded Python-based scraper that extracts CDN links and metadata from Google Photos shareable albums in seconds.
- **Event Management**: Complete CRUD operations for photographic collections, including month/year filtering and featured background rotation settings.

### 🚀 Public Utilities
- **Social Sharing**: Branded "Share Event" feature using the Web Share API for mobile and Clipboard fallback for desktop.
- **High-Res Proxy Downloads**: A secure PHP-based download proxy that allows users to save original-quality photos directly from the CDN with one click.

---

## 🏗️ Technical Stack

- **Backend**: PHP (PDO / XAMPP Environment)
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System), JavaScript (ES6+)
- **Database**: MySQL
- **Tooling**:
  - **Python Scraper**: `requests`, `concurrent.futures`, `re`
  - **Icons**: FontAwesome 6+
  - **Fonts**: Google Fonts (Outfit, Inter)

---

## ⚙️ Installation & Setup

### 1. Requirements
- **Server**: XAMPP (or any server with PHP 8.0+ and MySQL)
- **Environment**: Python 3.9+ (for Google Photos bulk ingestion)

### 2. Database Initialization
1. Import `schema.sql` into your MySQL environment.
2. Update `includes/db.php` with your connection credentials.

### 3. Setup Python Environment
```bash
pip install requests
```

### 4. Direct Uploads
Ensure your `php.ini` has adequate `post_max_size` and `upload_max_filesize` limits for local folder uploads.

---

## 📂 Project Structure

```text
/
├── admin/               # Administrative Control Panel
├── assets/              # Premium CSS, JS, and global styles
├── includes/            # Core DB linkage and shared components
├── scripts/             # Python scrapers and PHP ingestion logic
├── uploads/             # local thumbnails and persistent assets
├── event.php            # Dynamic event gallery view
├── gallery.php          # Global searchable collection
└── proxy_download.php   # Secure remote download handler
```

---

## 🏺 Aesthetic Guidelines
This project follows a "Glass-Saffron" design philosophy.
- **Primary Color**: `#f26522` (Saffron)
- **Translucency**: 20px - 40px Backdrop-filter blur for interactive cards.
- **Typography**: Heavyweights (900) for titles, Lightweights (300) for narrative text.

---

## 🛡️ License
Built with passion for the Vidyamandira Photography Club. 🏹🕯️🏺✨
