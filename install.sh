#!/bin/bash

# DENZIE STUDIO Admin Portal Installation Script
# Run this script to set up the database and configure the application

echo "DENZIE STUDIO Admin Portal Installation"
echo "====================================="

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "MySQL is not installed. Please install MySQL first."
    exit 1
fi

# Check if Apache is installed
if ! command -v apache2 &> /dev/null && ! command -v httpd &> /dev/null; then
    echo "Web server (Apache) is not installed. Please install Apache first."
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "PHP is not installed. Please install PHP first."
    exit 1
fi

echo "Creating database and tables..."

# Import the schema
mysql -u root -p < schema.sql

if [ $? -eq 0 ]; then
    echo "Database setup completed successfully!"
else
    echo "Database setup failed!"
    exit 1
fi

# Determine web server path
if command -v apache2 &> /dev/null; then
    WEB_PATH="/var/www/html"
elif command -v httpd &> /dev/null; then
    WEB_PATH="/var/www/html"
else
    echo "Could not determine web server path"
    exit 1
fi

echo "Copying files to $WEB_PATH..."

# Copy files to web directory
sudo cp admin.php config.php "$WEB_PATH/"

if [ $? -eq 0 ]; then
    echo "Files copied successfully!"
else
    echo "Failed to copy files!"
    exit 1
fi

# Set permissions
sudo chown -R www-data:www-data "$WEB_PATH/admin.php" "$WEB_PATH/config.php"
sudo chmod 644 "$WEB_PATH/admin.php" "$WEB_PATH/config.php"

echo "Setting permissions..."

# Restart web server
if command -v systemctl &> /dev/null; then
    sudo systemctl restart apache2
    echo "Apache restarted"
fi

echo ""
echo "Installation completed!"
echo "Access your admin portal at: http://localhost/admin.php"
echo "Default login:"
echo "  Username: admin"
echo "  Password: admin_password"
