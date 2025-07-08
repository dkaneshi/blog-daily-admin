# Blog Daily Admin - Developer Guidelines

This document provides essential information for developers working on the Blog Daily Admin project.

## Build/Configuration Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL (default database)

### Initial Setup
1. Clone the repository
2. Install PHP dependencies:
   ```
   composer install
   ```
3. Install JavaScript dependencies:
   ```
   npm install
   ```
4. Create a copy of the environment file:
   ```
   cp .env.example .env
   ```
5. Generate application key:
   ```
   php artisan key:generate
   ```
6. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog_daily_admin
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
7. Run database migrations:
   ```
   php artisan migrate
   ```
8. Seed the database (if needed):
   ```
   php artisan db:seed
   ```

### Development Environment
To start the development environment, use the following command:
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

## Testing Information

### Testing Configuration
- Tests use SQLite in-memory database
- The testing environment is defined in `phpunit.xml`
- The project uses Pest PHP for testing (a wrapper around PHPUnit)

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

To run a specific test file:
```
php artisan test --filter=TestFileName
```

### Creating Tests
1. Create a new PHP file in the appropriate directory:
   - Unit tests: `tests/Unit/`
   - Feature tests: `tests/Feature/`

2. Use the Pest PHP syntax for writing tests:
```php
<?php

declare(strict_types=1);

test('feature description', function () {
    // Arrange
    $user = \App\Models\User::factory()->create();
    
    // Act
    $response = $this->actingAs($user)->get('/');
    
    // Assert
    expect($response)->assertOk();
});
```

3. For tests requiring database access, the `RefreshDatabase` trait is automatically applied to Feature tests through the `tests/Pest.php` configuration.

### Example Test
Here's a simple test that verifies the home page is accessible:

```php
<?php

declare(strict_types=1);

test('home page is accessible for unauthenticated users', function () {
    $response = $this->get('/');

    expect($response)->assertOk();
});

test('home page is accessible for authenticated users', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    expect($response)->assertOk();
});
```

## Additional Development Information

### Code Quality Tools
The project uses several code quality tools:

1. **Laravel Pint** - PHP code style fixer
   - Run: `composer lint`
   - Check only: `composer test:lint`

2. **PHPStan/Larastan** - Static analysis
   - Run: `composer test:types`

3. **Rector** - Automated code refactoring
   - Run: `composer refactor`
   - Check only: `composer test:refactor`

### Authentication
- The application uses Laravel's built-in authentication
- There are two user types: regular users and admins
- Admin users have access to post and category management
- Regular users have limited access

### Project Structure
- Standard Laravel 12 project structure
- Controllers follow RESTful conventions
- Views use Blade templating with Tailwind CSS
- Frontend uses Alpine.js for JavaScript functionality

### Database
- The application uses Eloquent ORM
- Database migrations are in `database/migrations/`
- Factory classes for test data are in `database/factories/`
- Seeders are in `database/seeders/`
