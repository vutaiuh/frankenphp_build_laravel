#!/bin/bash

set -e

echo "=== PHP Version ==="
docker compose exec app php -v

echo "=== Composer Install ==="
docker compose exec app composer install --no-interaction --prefer-dist

echo "=== Generate App Key ==="
docker compose exec app php artisan key:generate --force

echo "=== Run Pint ==="
docker compose exec app ./vendor/bin/pint --test

echo "=== Run Tests ==="
docker compose exec app php artisan test

echo "=================================="
echo "✅ ALL CHECKS PASSED"
echo "=================================="
