cd /app

echo "\n----- folder permissions -----"
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache

echo "\n ----- run migrations -----"
php artisan migrate

echo "\n----- start app -----"
service nginx start
php-fpm