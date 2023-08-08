# Test: Appliance API
This is an example of an API developed in PHP with Laravel 10 using TDD, Clean Architecture and Design Patterns, also respecting the principles of SOLID.

## How to run?
1. After cloning this repository,
2. go to directory (`cd test-appliance-api`),
3. and now you need to install Composer dependencies (even if you don't have PHP and Composer installed in your environment):
```cmd
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer create-project --ignore-platform-reqs
```
3. then `./vendor/bin/sail up -d`
4. and for last `./vendor/bin/sail artisan migrate --seed`

## Testing
For execute tests just run:
```cmd
./vendor/bin/sail artisan test
```
