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
