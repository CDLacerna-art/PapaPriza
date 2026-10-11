# Deployment Guide (Docker)

## Requirements
- Docker Desktop (Windows/Mac) or Docker Engine + Compose plugin (Linux)

## First run
1. Copy `.env.docker.example` to `.env.docker`.
2. Generate an app key and paste it in `APP_KEY`:
   `php artisan key:generate --show`
3. Change `DB_PASSWORD`, `DB_ROOT_PASSWORD`, and `ADMIN_EMAIL`.
4. Build and start:
   `docker compose up -d --build`
5. Open http://localhost:8080

Migrations run automatically on start (`RUN_MIGRATIONS=true`).

## Useful commands
| Task | Command |
|---|---|
| See logs | `docker compose logs -f app` |
| Run artisan | `docker compose exec app php artisan <command>` |
| Open phpMyAdmin | `docker compose --profile tools up -d` then http://localhost:8081 |
| Stop | `docker compose down` |
| Stop and delete the database | `docker compose down -v` |
| Rebuild after code changes | `docker compose up -d --build` |

## Services
| Service | What it does | Port |
|---|---|---|
| app | PHP-FPM + Nginx (Laravel) | 8080 |
| db | MySQL 8 | 3307 |
| phpmyadmin (optional) | Database viewer | 8081 |

## Production checklist
- `APP_DEBUG=false` and `APP_ENV=production`
- Strong database passwords
- `.env.docker` is NOT committed to Git
- Put the site behind HTTPS (reverse proxy such as Caddy, Nginx, or the host's SSL) and set `APP_URL` to the https address
- Back up the `db_data` volume
