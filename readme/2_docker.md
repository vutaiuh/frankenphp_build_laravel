# Laravel + FrankenPHP Docker Setup

## 1. Chuẩn bị thư mục Docker

```bash
mkdir -p docker/php
```

## 2. Copy file môi trường

```bash
cp .env.docker .env
```

## 3. Tắt Apache và MySQL local (tránh trùng port)

```bash
sudo service apache2 stop
sudo service mysql stop
```

## 4. Build và khởi động Docker

```bash
docker compose up -d --build
```

## 5. Xem logs

```bash
docker compose logs -f
```

## 6. Cài đặt Composer Dependencies

```bash
docker compose exec app composer install
```

## 7. Khởi tạo Laravel

### Generate Application Key

```bash
docker compose exec app php artisan key:generate
```

### Run Database Migrations

```bash
docker compose exec app php artisan migrate
```

### Clear Cache

```bash
docker compose exec app php artisan optimize:clear
```

## 8. Cấp quyền cho Laravel

```bash
docker compose exec app chown -R www-data:www-data storage/ bootstrap/cache/
docker compose exec app chmod -R 775 storage/ bootstrap/cache/
```

## 9. Kiểm tra Containers

```bash
docker ps
```

## 10. Theo dõi Logs

```bash
docker compose logs -f
```

## 11. Truy cập vào Container

```bash
docker compose exec app bash
```

## Thông tin truy cập

### Laravel

```text
http://localhost:8080
```

### phpMyAdmin

```text
http://localhost:8081
```

### MySQL

```text
Host: localhost
Port: 3306
Database: laravel_db
Username: laravel_user
Password: laravel_password
```

### Redis

```text
Host: localhost
Port: 6379
```
