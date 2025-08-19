## Test Project (Laravel API)

This repository contains a Laravel-based API with user, task, roles/abilities, jobs, mail, and Octane (RoadRunner) support.

## Setup & Installation

Run the following from the project root:

```bash
# 1) Install PHP dependencies
composer install

# 2) Create your environment file
# If the repo includes .env.save (preferred by this project):
cp .env.save .env
# If not available, fall back to Laravel's default:
# cp .env.example .env

# 3) Generate application key
php artisan key:generate

# 4) Configure your DB/queue/mail in .env (example)
# open .env and set values like:
# APP_NAME="Test Project"
# APP_ENV=local
# APP_DEBUG=true
# APP_URL=http://127.0.0.1:8000
#
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=test_project
# DB_USERNAME=root
# DB_PASSWORD=secret
#
# QUEUE_CONNECTION=database   # or redis
# CACHE_DRIVER=file           # or redis
# SESSION_DRIVER=file

# 5) Create storage symlink for public files
php artisan storage:link

# 6) Run migrations and seeders
php artisan migrate --seed
```

## Running the Application

### Queue worker

```bash
# Process queued jobs (run in a dedicated terminal)
php artisan queue:work --tries=3 --backoff=5

# Or as a daemon with sleep and memory limits
# php artisan queue:work --sleep=1 --memory=256 --timeout=120
```

### Task scheduler (to run scheduled jobs/commands)

```bash
# Dev (keeps scheduler running in foreground)
php artisan schedule:work

# One-off run (executes due tasks once and exits)
php artisan schedule:run

# Production cron (runs every minute)
# * * * * * cd /path/to/test_project && php artisan schedule:run >> /dev/null 2>&1
```

### Real-Time Notifications (Pusher + Broadcasting)

```bash
# 1) Install dependencies (server + frontend)
composer require pusher/pusher-php-server
npm i -D laravel-echo pusher-js

# 2) Configure .env (example)
echo "BROADCAST_DRIVER=pusher" >> .env
echo "PUSHER_APP_ID=your_id" >> .env
echo "PUSHER_APP_KEY=your_key" >> .env
echo "PUSHER_APP_SECRET=your_secret" >> .env
echo "PUSHER_APP_CLUSTER=mt1" >> .env

# 3) Frontend env for Vite (optional if using custom host/port)
echo "VITE_PUSHER_APP_KEY=your_key" >> .env
echo "VITE_PUSHER_APP_CLUSTER=mt1" >> .env
echo "VITE_PUSHER_HOST=127.0.0.1" >> .env
echo "VITE_PUSHER_PORT=6001" >> .env
echo "VITE_PUSHER_SCHEME=http" >> .env

# 4) Build assets
npm run dev
```

This project broadcasts `TaskStatusUpdated` on private channel `users.{userId}` when a task status actually changes. Authenticate via Sanctum to authorize the private channel.

### Serve with Octane (RoadRunner)

Make sure Octane and RoadRunner are available (this project already includes Octane). To start the server:

```bash
# Bind to all interfaces on port 8000
php artisan octane:start --server=roadrunner --workers=4 --max-requests=500 --host=0.0.0.0 --port=8000

# Alternatively, use your LAN IP as host (macOS)
HOST=$(ipconfig getifaddr en0 || ipconfig getifaddr en1 || echo 127.0.0.1)
php artisan octane:start --server=roadrunner --workers=4 --max-requests=500 --host=$HOST --port=8000

# Check Octane status
php artisan octane:status
```

Then open your browser at the host/port shown. If you used the LAN IP, set `APP_URL` in `.env` accordingly, e.g. `APP_URL=http://$HOST:8000`.

## Testing

```bash
# Run the test suite
php artisan test
# or
./vendor/bin/phpunit
```

## Common Commands (Quick Reference)

```bash
# Install dependencies
composer install

# Environment
cp .env.save .env        # or: cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed
# (optional) reset & reseed
# php artisan migrate:fresh --seed

# Storage symlink
php artisan storage:link

# Queue & schedule
php artisan queue:work
php artisan schedule:work    # or: php artisan schedule:run

# Serve (Octane, RoadRunner)
php artisan octane:start --server=roadrunner --workers=4 --max-requests=500 --host=0.0.0.0 --port=8000
php artisan octane:status

# Tests
php artisan test
```

## Project Structure (Brief)

-   **`app/Console/Commands`**: Custom Artisan commands (e.g., daily task sender).
-   **`app/Enums`**: Enumerations like `TaskStatusEnum`.
-   **`app/Exceptions`**: API exception handling and error definitions.
-   **`app/Http/Controllers`**: API controllers for Admin/Auth/User domains.
-   **`app/Http/Middleware`**: HTTP middleware (abilities, ownership checks).
-   **`app/Http/Requests`**: Form request validation for Admin/User/Task endpoints.
-   **`app/Http/Resources`**: API resource transformers for responses.
-   **`app/Jobs`**: Queued jobs (e.g., `SendDailyUserTaskJob`).
-   **`app/Mail`**: Mailable classes (e.g., `SendDailyUserTaskMail`).
-   **`app/Models`**: Eloquent models (`User`, `Task`, `Role`, `Ability`, etc.).
-   **`app/Providers`**: Service providers (`AppServiceProvider`).
-   **`app/Service`**: Application service layer (`AuthService`, `TaskService`, `UserService`).
-   **`app/Traits`**: Reusable traits (filters, file uploads).
-   **`app/Utils`**: Helpers/utilities (e.g., `Logger`).
-   **`config/`**: Framework and package configuration.
-   **`database/migrations`**: Schema definitions; **`database/seeders`**: seed data.
-   **`routes/`**: Route definitions (`api.php`, `web.php`, `console.php`).
-   **`resources/views`**: Blade templates (emails, welcome page).
-   **`tests/`**: Feature and unit tests.

## Notes

-   Ensure your queue driver in `.env` matches your environment (`database` or `redis`). For `database`, run `php artisan queue:table && php artisan migrate` once to create the jobs table (this repo already includes a jobs migration).
-   If you change `.env`, restart running workers/servers so changes take effect (stop and re-run `queue:work`, `octane:start`).
