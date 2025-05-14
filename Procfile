web: vendor/bin/heroku-php-apache2 -l log/error.log public/
release: php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force
