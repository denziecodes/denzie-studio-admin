# DENZIE STUDIO Admin Portal

Admin portal for managing DENZIE STUDIO website content including tools, services, projects, and social links.

## Setup Instructions

1. Create MySQL database:
```sql
   CREATE DATABASE denzie_studio;
   CREATE USER 'denzie_user'@'localhost' IDENTIFIED BY 'StrongPassword123';
   GRANT ALL PRIVILEGES ON denzie_studio.* TO 'denzie_user'@'localhost';
   FLUSH PRIVILEGES;
```

2. Update database credentials in `config.php`

3. Access admin panel at `/admin.php`

## Default Login
- Username: admin
- Password: admin_password
