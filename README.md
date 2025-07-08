# Blog Daily Admin

A comprehensive content management system for blog administration, built with Laravel 12.

## Overview

Blog Daily Admin is a powerful web application designed to simplify the management of blog content. It provides an intuitive interface for creating, editing, and organizing blog posts and categories, with role-based access control for administrators and regular users.

## Features

- **User Authentication**: Secure login and registration system with role-based permissions
- **Post Management**: Create, edit, delete, and publish blog posts
- **Category Management**: Organize content with customizable categories
- **Admin Dashboard**: Comprehensive overview of site content and user activity
- **Responsive Design**: Built with Tailwind CSS for a seamless experience across devices
- **Interactive UI**: Enhanced with Alpine.js for dynamic frontend functionality

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL (default database)

### Setup Instructions

1. **Clone the repository**
   ```
   git clone <repository-url>
   cd blog-daily-admin
   ```

2. **Install PHP dependencies**
   ```
   composer install
   ```

3. **Install JavaScript dependencies**
   ```
   npm install
   ```

4. **Configure environment**
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. **Set up database**
   
   Update the `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog_daily_admin
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```
   php artisan migrate
   php artisan db:seed
   ```

## Development

### Starting the Development Environment

Run the following command to start all development services:
```
composer dev
```

This will concurrently run:
- Laravel development server
- Queue worker
- Laravel Pail (for logs)
- Vite development server

Alternatively, you can run individual components:
- Laravel server: `php artisan serve`
- Frontend assets: `npm run dev`

### Project Structure

- Standard Laravel 12 project structure
- RESTful controllers
- Blade templates with Tailwind CSS
- Alpine.js for frontend interactivity

## Testing

The project uses Pest PHP for testing (a wrapper around PHPUnit).

### Running Tests

To run all tests:
```
composer test
```

To run specific test suites:
- Unit tests: `composer test:unit`
- Code style checks: `composer test:lint`
- Static analysis: `composer test:types`
- Code refactoring checks: `composer test:refactor`

### Testing Configuration

- Tests use SQLite in-memory database
- Testing environment is defined in `phpunit.xml`

## Code Quality

The project maintains high code quality standards using:

1. **Laravel Pint** - PHP code style fixer
   - Run: `composer lint`
   - Check only: `composer test:lint`

2. **PHPStan/Larastan** - Static analysis
   - Run: `composer test:types`

3. **Rector** - Automated code refactoring
   - Run: `composer refactor`
   - Check only: `composer test:refactor`

## Authentication

- Built-in Laravel authentication
- Two user types: regular users and admins
- Admin users have access to post and category management
- Regular users have limited access

## Database

- Eloquent ORM
- Migrations in `database/migrations/`
- Factory classes in `database/factories/`
- Seeders in `database/seeders/`
