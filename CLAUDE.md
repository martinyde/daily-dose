# Daily Dose

Symfony 7.2 web app that displays a rotating daily image from numbered image collections, with templated text overlays.

## Tech Stack

- **Backend:** PHP 8.2+ / Symfony 7.2 / Twig
- **Frontend:** Stimulus.js, Turbo, AssetMapper (no build step)
- **Infrastructure:** Docker (Nginx + PHP-FPM), Traefik for production

## Development

```bash
# Docker local dev
docker-compose -f docker-compose.local.yml up -d
docker-compose exec phpfpm composer install
docker-compose exec phpfpm php bin/console importmap:install

# Run tests
docker-compose exec phpfpm php bin/phpunit
```

## Key Concepts

- **Access keys:** Base64-encoded strings containing display config (start date, folder, prefix, filetype, digit padding, weekend handling, zero-start flag). Generated via `php bin/console app:get-key`.
- **Daily files:** User-provided numbered images stored in `daily-files/<folder>/`. Text templates in `daily-files/text-templates/`.
- **Routes:** `/display/daily/{key}` renders daily image; `/serve/{folder}/{filename}` serves files privately.

## Project Structure

- `src/Controller/DisplayDailyController.php` — Main display logic
- `src/Controller/ServePrivateFileController.php` — Private file serving
- `src/Command/GetKeyCommand.php` — CLI key generator
- `templates/display_daily/index.html.twig` — Main template
- `daily-files/` — Image collections and text templates
- `config/services.yaml` — Service config, `DISPLAY_KEY` env param

## Conventions

- PSR-4 namespacing (`App\`)
- Attribute-based routing (`#[Route(...)]`)
- Constructor promotion for DI
- Responsive CSS with orientation media queries
