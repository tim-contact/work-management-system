# Work Management System (WMS)

Simple work tracking app built for an internship evaluation. Users can create work items, track work sessions, and mark work as completed. The project uses two MySQL databases: one for users/auth and another for work data.

## Features
- User registration, login, and logout.
- Create, edit, and delete work items.
- Start and stop work sessions with duration tracking.
- Mark work as completed.
- Per-user access control via policies.

## Tech Stack
- Laravel 12 (PHP 8.2)
- MySQL (two connections)
- Blade + Tailwind CSS (DaisyUI) + Vite

## Architecture Notes
- Users and auth-related tables live in the `mysql_auth` connection.
- Work and work session tables live in the `mysql_work` connection.
- Models explicitly set their connection in `app/Models/User.php` and `app/Models/Work.php`.

## Setup
1. Install dependencies:
   - `composer install`
   - `npm install`
2. Create `.env`:
   - `cp .env.example .env`
3. Configure database connections in `.env`:
   - `DB_CONNECTION=mysql_auth`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=work_management_auth`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
   - `WORK_DB_HOST=127.0.0.1`
   - `WORK_DB_PORT=3306`
   - `WORK_DB_DATABASE=work_management_work`
   - `WORK_DB_USERNAME=root`
   - `WORK_DB_PASSWORD=`
4. Generate the app key:
   - `php artisan key:generate`
5. Run migrations:
   - `php artisan migrate --path=database/migrations/auth --database=mysql_auth`
   - `php artisan migrate --path=database/migrations/work --database=mysql_work`
   - `php artisan migrate`
6. Build assets:
   - `npm run build`

## Run the App
- `php artisan serve`
- `npm run dev`

App routes:
- `GET /register` and `POST /register`
- `GET /login` and `POST /login`
- `POST /logout`
- `GET /works` (dashboard)

## Usage
1. Register a new user.
2. Create work items from the dashboard.
3. Start/stop sessions to track time.
4. Mark work as completed when finished.


## Project Structure
- `app/Http/Controllers/WorkController.php` - Work CRUD and session logic.
- `app/Policies/WorkPolicy.php` - Authorization rules.
- `app/Models/Work.php` and `app/Models/WorkSession.php` - Work data models.
- `database/migrations/auth` - Users, sessions, and password reset tables.
- `database/migrations/work` - Work and work session tables.
- `resources/views/work` - Work views.
