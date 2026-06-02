#!/bin/bash

echo "Performing fresh installation..."

docker compose down -v
docker compose up -d --build

sleep 20

docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan optimize:clear
docker compose exec app chown -R www-data:www-data storage/ bootstrap/cache/

echo "Fresh installation complete!"
