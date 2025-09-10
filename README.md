# MVC Skeleton

Minimal PHP 8.2 MVC template following PSR-4 and SOLID basics.

## Kurulum

```bash
composer install
cp .env.example .env
composer migrate
composer seed
```

## Komutlar

```bash
composer start   # php -S 127.0.0.1:8000 -t public
composer test    # phpunit
composer cs      # php-cs-fixer
composer stan    # psalm
composer migrate # run migrations
composer seed    # seed sample data
```

## Klasör Yapısı

```
app/
  Core/
  Controllers/
  Http/
  Interfaces/
  Routing/
  Services/
  Views/
config/
public/
storage/logs/
tests/
```

## Test

```bash
composer test
```

## Docker

```bash
docker-compose up --build
```
