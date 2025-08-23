#!/bin/sh

# Set proper permissions on mounted volumes
chown -R webuser:webuser /var/www
chmod -R 755 /var/www

# Start PHP-FPM in background
php-fpm &

# Start nginx in foreground
nginx -g "daemon off;"