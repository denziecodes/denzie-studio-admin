# DENZIE STUDIO Admin Portal Installation Guide

## Prerequisites

- Linux-based operating system (Ubuntu/Debian/CentOS/RHEL)
- MySQL/MariaDB server
- Apache web server
- PHP 7.4 or higher
- Git

## Installation Steps

### 1. Clone the Repository

```bash
git clone git@github.com:denziestudio/denzie-studio-admin.git
cd denzie-studio-admin
```

### 2. Manual Installation

#### A. Create Database

Run the SQL commands from `schema.sql` file:

```bash
mysql -u root -p < schema.sql
```

#### B. Deploy Files

Copy the files to your web server directory:

```bash
sudo cp admin.php config.php /var/www/html/
```

#### C. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/html/admin.php /var/www/html/config.php
sudo chmod 644 /var/www/html/admin.php /var/www/html/config.php
```

#### D. Restart Web Server

```bash
sudo systemctl restart apache2
```

### 3. Automatic Installation

Run the installation script:

```bash
chmod +x install.sh
./install.sh
```

## Access Admin Portal

Navigate to `http://your-server-ip/admin.php`

### Default Login Credentials

- **Username:** admin
- **Password:** admin_password

## Configuration

If you need to change database credentials:

1. Edit `config.php`
2. Update the following constants:
   - `DB_HOST`
   - `DB_USER`
   - `DB_PASS`
   - `DB_NAME`
   - `ADMIN_USER`
   - `ADMIN_PASS`

## Troubleshooting

### Common Issues

1. **Permission denied accessing database**
   - Ensure the database user has proper privileges
   - Check database credentials in config.php

2. **Page not loading**
   - Verify Apache is running
   - Check PHP modules are installed

3. **Login fails**
   - Verify admin credentials
   - Reset password in config.php if needed
