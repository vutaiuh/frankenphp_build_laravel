#!/bin/bash

echo "Setting up Laravel FrankenPHP environment..."

if ! docker info > /dev/null 2>&1; then
    echo "Docker is not running. Please start Docker first."
    exit 1
fi

if [ ! -f .env ]; then
    echo "Copying environment file..."
    cp .env.docker .env
fi

echo "Building and starting Docker containers..."
docker compose up -d --build

echo "Waiting for services to initialize..."
sleep 20

if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    docker compose exec app php artisan key:generate
fi

echo "Running database migrations..."
docker compose exec app php artisan migrate --force

echo "Setting permissions..."
docker compose exec app chown -R www-data:www-data storage/ bootstrap/cache/
docker compose exec app chmod -R 775 storage/ bootstrap/cache/

echo "Clearing caches..."
docker compose exec app php artisan optimize:clear

echo ""
echo "Setup complete!"
echo "Application: http://localhost:8080"
echo "Database:    localhost:3306"
echo "Redis:       localhost:6379"
