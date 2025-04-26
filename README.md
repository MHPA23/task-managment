# Task Management System

A modern task management system built with Laravel and Vue.js, featuring a responsive interface and real-time updates.

## Features

-   Create, read, update, and delete tasks
-   Search tasks by title
-   Sort tasks by date or title
-   Filter tasks by completion status
-   Responsive design
-   Clean and modern UI with Tailwind CSS
-   Pagination support

## Tech Stack

-   **Backend**: Laravel 10.x
-   **Frontend**: Vue.js 3.x
-   **State Management**: Pinia
-   **Styling**: Tailwind CSS
-   **Database**: PostgreSQL
-   **Notifications**: Vue Toastification

## Installation

1. Clone the repository

```bash
git clone <repository-url>
cd task-managment
```

2. Install PHP dependencys and Run project

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d --build
```

3. Build assets 

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

4. Configure environment

```bash
cp .env.example .env
./vendor/bin/sail php artisan key:generate
```

5. Configure your database in `.env`

```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations

```bash
./vendor/bin/sail php artisan migrate
```

## API Endpoints

| Method | Endpoint        | Description       |
| ------ | --------------- | ----------------- |
| GET    | /api/tasks      | List all tasks    |
| POST   | /api/tasks      | Create a new task |
| PUT    | /api/tasks/{id} | Update a task     |
| DELETE | /api/tasks/{id} | Delete a task     |

### Query Parameters

-   `sort_by`: Sort field (created_at, title)
-   `sort_dir`: Sort direction (asc, desc)
-   `completed`: Filter by completion status (true, false)
-   `title`: Search by title
-   `page`: Page number for pagination
-   `per_page`: Items per page (default: 10)

## Testing

```bash
./vendor/bin/sail php artisan test
```
