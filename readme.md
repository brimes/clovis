# Projeto CLOVIS-chat

This project is new microservices and his first name was MS-CHATDESK.

## Development Environment

### Requirements

* PHP 7.1+
* Postgres 9.4+
* Composer

### Installation

Please check the [installation documentation](docs/installation.md).

## Console usage

* **Run config check** `./bin/setup`
* **Run jobs**: `php artisan queue:work --timeout=3600`
* **Create admin user**: `php artisan users:create`

### Code quality

Check code quality by running `php ./vendor/bin/grumphp run`.

### Testing

After installing, run `php ./vendor/bin/phpunit`.
