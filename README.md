# Hoarder

Not much here at the moment, just doing some testing in Laravel.

Warning: The database takes a long time to seed and makes a lot of external API requests.

## Development Setup

1. Clone the repository
2. Install [composer](https://getcomposer.org/) dependencies (`composer install`)
3. Install [node](https://nodejs.org/) dependencies (`npm install`)
4. Build the assets (`npm run production` or `npm run [development/watch/hot]`)
5. Generate an application key (copy `.env.example` to `.env` and run `php artisan key:generate`)
6. Start the [docker](https://docker.io/) container (`docker-compose up -d`)
7. Install and seed the database (`docker-compose exec laravel.test php artisan migrate --seed`).

    The seeding will take a long time, it can be run without `--seed` to skip seeding, but the database will not contain
    any defined collectibles or items (and the UI to create them is not complete). Cancelling the seed will leave you
    with incomplete collectible data, but will not cause any issues if you get tired of waiting for the seed.

8. Browse the dev site at [http://localhost/](http://localhost/)

### Docker, Windows, and WSL2

If you are using Windows with WSL2, and the site is slow to load pages, it can be sped up by moving vendor and storage
to the WSL filesystem using the following steps:

1. Copy `docker-compose.override.yml.example` to `docker-compose.override.yml`
2. Restart containers with `docker-compose stop` and `docker-compose up -d`
3. Setup paths in container with `docker-compose exec laravel.test mkdir -p /var/www/html/storage/app /var/www/html/storage/cache /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs`
4. Fix permissions in container with `docker-compose exec laravel.test chown -R sail:sail /var/www/html/storage /var/www/html/vendor`
5. Install composer dependencies in container with `docker-compose exec laravel.test composer install`
6. Restart containers again with `docker-compose stop` and `docker-compose up -d`

This has the drawback of requiring the `composer install` to be run inside the container each time `composer.json` is
updated.
