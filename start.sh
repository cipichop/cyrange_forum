#!/bin/sh

# Wait for MySQL to be ready
# while ! 127.0.0.1:3306 ping -h db --silent; do
#     sleep 1
# done

# Run the SQL script
mysql -h db -u root -proot forum_db < /var/www/html/db_init.sql

# Start Apache
sleep 10
php -S 0.0.0.0:8080