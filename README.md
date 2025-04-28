# Task Management System

A modern task management system built with Laravel and Vue.js, featuring a responsive interface.

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

2. Run docker-composer

```bash
docker composer up -d --build
```

3. Configure environment

```bash
cp .env.example .env
docker exec -it laravel_app php artisan key:generate
```

4. Configure your database in `.env`

```
DB_CONNECTION=pgsql
DB_HOST=postgres_db
DB_PORT=5432
DB_DATABASE=task
DB_USERNAME=root
DB_PASSWORD=root
```

5. Run migrations

```bash
docker exec -it laravel_app php artisan migrate
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
docker exec -it laravel_app php artisan test

```

## Usage
First, create an account.
This project uses Laravel Breeze to provide the authentication structure.
Once logged into the application, you will see two menus: Dashboard and Tasks.

- Dashboard displays all information about tasks and chats to explore.
- Tasks provides full CRUD (Create, Read, Update, Delete) functionality for managing tasks.