#!/bin/sh

echo "Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Waiting for database..."
until nc -z database 5432; do
  sleep 1
done

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

exec php-fpm