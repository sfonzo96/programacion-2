# PHP Monitor

A simple PHP-based monitoring backend with a simple frontend (static pages in `public/`). It provides basic user
management, metrics storage, SSE support, and network/host tracking.

## What this is

-   API serves system/metric data for hosts and networks.
-   Developed using PHP's Slim Framework.
-   Must work with another backend daemon found [here](https://github.com/sfonzo96/go-monitor-daemons), which is the actual
    collector for the metrics and networks scanner.
-	Most importantly, this is an associate's degree final project of the 2nd Programming's course. Real intention is to later rebuild this all in Golang.
## Requirements

-   PHP 8+
-   Composer
-   MariaDB database
-   A webserver (this uses Apache)

## Quick start

0. Clone the project

```bash
	git clone https://github.com/sfonzo96/php-monitor/
```

1. Move to the root directory of the project

```bash
	cd php-monitor
```

2. Install PHP dependencies

```bash
    composer install
```

3. Copy the example env and edit the variables with data of your own

```bash
    cp src/Config/.example.env src/Config/.env
```

4. Import the database schema:

```bash
    mysql -u <user> -p monitordb < dbschema.sql
```

## Project layout (important folders)

-   `public/` — static frontend files and the webroot (`index.php`, HTML, CSS, JS).
-   `src/` — PHP source code
    -   `Attributes/` — PHP Attributes for incomming HTTP Requests validation
    -   `Controllers/` — HTTP controllers
    -   `Database/` — Database connection class
    -   `DTOs/` — DTO classes (mainly for incomming HTTP Requests modelling)
    -   `Repositories/` — DB access logic
    -   `Models/` — Models for DB entities
    -   `Middlewares/` — Authentication, Authorization, Body validation and other middlewares
    -   `Services/` — Business logic
    -   `Utils/` — Helpers (JWT, API responder, etc.)
    -   `Cache/` — Small JSON cache files
-   `dbschema.sql` — SQL schema to create the required tables
-   `composer.json` — PHP dependencies and autoload

## Notes & tips

-   The webroot is `public/`; keep sensitive files outside this folder.
-   There's a sample `.env` at `src/Config/.example.env`. Copy it and configure DB credentials.
-   Cache JSON files are stored in `src/Cache/` (e.g., `systeminfo.json`, `refresh_tokens.json`).
-   SSE (server-sent events) support is present for notifications to the frontend — see
    `src/Controllers/SSEController.php` and `src/Repositories/SSERepository.php`.

## Troubleshooting

-   If routes don't resolve, ensure your webserver forwards requests to `public/index.php`.
-   Database errors: confirm the credentials in the `.env` and that the schema is imported.

## Contributing

Small fixes and improvements are welcomed. Keep changes minimal and add a short note in the PR describing the intent.
