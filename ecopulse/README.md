# EcoPulse MVP V1

**Indian Healthcare Sustainability Management Platform**

## Overview
EcoPulse enables hospitals to submit monthly sustainability data and automatically generates carbon estimates, sustainability scores, recommendations, and downloadable reports.

## Tech Stack
- PHP 8+
- MySQL 8+
- HTML5, CSS3, Vanilla JavaScript
- Chart.js for data visualization
- Lucide Icons
- TCPDF for report generation (optional)

## Installation

### Prerequisites
- XAMPP / WAMP / LAMP with PHP 8+ and MySQL
- Apache with mod_rewrite enabled

### Steps

1. **Clone/Copy** the `EcoPulse` folder to your web server's document root:
   - XAMPP: `C:\xampp\htdocs\EcoPulse`
   - WAMP: `C:\wamp64\www\EcoPulse`

2. **Create Database**:
   - Open phpMyAdmin or MySQL CLI
   - Import `EcpPulse.sql`:
     ```sql
     SOURCE /path/to/EcpPulse.sql;
     ```

3. **Seed Data**:
   - Run the seed script from terminal:
     ```bash
     php database/seed.php
     ```
   - This creates the default admin and demo hospital accounts.

4. **Configure Environment**:
   - Copy `.env.example` to `.env`
   - Update database credentials:
     ```
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=ecopulse_db
     DB_USER=root
     DB_PASS=
     ```

5. **Set Permissions** (Linux/Mac):
   ```bash
   chmod -R 775 storage/
   ```

6. **Enable mod_rewrite** (Apache):
   - Ensure `AllowOverride All` is set in your Apache config
   - Restart Apache

7. **Access the Application**:
   - Admin: `http://localhost/EcoPulse/public/admin/login`
   - Hospital: `http://localhost/EcoPulse/public/hospital/login`

## Default Credentials

| Role | Email/Username | Password |
|------|---------------|----------|
| Admin | admin@ecopulse.in | Admin@123 |
| Hospital | demo@hospital.in or citygenhospital | Hospital@123 |

## Features
- Dual-portal system (Admin + Hospital)
- 6-step monthly sustainability submission wizard
- Carbon calculation engine (Scope 1 & 2)
- Sustainability scoring (0-100)
- Rule-based recommendation engine
- Report generation (HTML/PDF)
- Dark mode
- Responsive design
- Secure authentication with CSRF protection

## Folder Structure
```
EcoPulse/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│       └── partials/
├── database/
├── public/
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── .env
└── README.md
```

## License
Proprietary. All rights reserved.
