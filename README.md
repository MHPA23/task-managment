# Task Management System

A modern task management system built with Laravel and Vue.js, featuring a responsive interface and real-time updates.

## Features

-   ✨ Create, read, update, and delete tasks
-   🔍 Search tasks by title
-   🔄 Sort tasks by date or title
-   ⚡ Filter tasks by completion status
-   📱 Responsive design
-   🎨 Clean and modern UI with Tailwind CSS
-   📄 Pagination support

## Tech Stack

-   **Backend**: Laravel 10.x
-   **Frontend**: Vue.js 3.x
-   **State Management**: Pinia
-   **Styling**: Tailwind CSS
-   **Database**: PostgreSQL
-   **Notifications**: Vue Toastification

## Prerequisites

-   PHP >= 8.1
-   Node.js >= 16
-   PostgreSQL
-   Composer

## Installation

1. Clone the repository

```bash
git clone <repository-url>
cd tasks
```

2. Install PHP dependencies

```bash
composer install
```

3. Install JavaScript dependencies

```bash
npm install
```

4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations

```bash
php artisan migrate
```

7. Build assets

```bash
npm run dev
```

8. Start the server

```bash
php artisan serve
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

## Development

For hot-reload during development:

```bash
npm run dev
```

## Testing

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
