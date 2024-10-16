# Laravel DDD Pattern with DTO

This project demonstrates the implementation of Domain-Driven Design (DDD) in a Laravel application, utilizing Data Transfer Objects (DTOs) for data encapsulation and transfer.

## Project Structure

The project is organized into several layers, each with a specific responsibility:

- **Domain**: Contains the core business logic and entities.
- **Application**: Handles the application logic and orchestrates the use cases.
- **Infrastructure**: Manages the interaction with external systems (e.g., database, third-party services).
- **Presentation**: Deals with the user interface and API endpoints.

### Example Structure

```plaintext
src/
├── app/
│   ├── Application/
│   │   └── Actions/
│   │       └── CreateUserAction.php
│   ├── Domain/
│   │   ├── User/
│   │   │   ├── Handlers/
│   │   │   │   └── CreateUserHandler.php
│   │   │   ├── Actions/
│   │   │   │   └── CreateUserAction.php
│   │   │   └── Data/
│   │   │       └── CreateUserData.php
│   ├── Infrastructure/
│   │   └── Database/
│   │       └── Models/
│   │           └── User.php
│   └── Presentation/
├── .env
└── README.md
```

## Key Components

### Handlers

Handlers are responsible for executing specific actions. They receive data through DTOs and delegate the processing to actions.

#### Example: `CreateUserHandler`

```php
namespace App\Domain\User\Handlers;

use Domain\User\Actions\CreateUserAction;
use Domain\User\Data\CreateUserData;
use Domain\User\Models\User;

class CreateUserHandler
{
    private CreateUserAction $createUserAction;

    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;
    }

    public function handle(CreateUserData $data): User
    {
        return $this->createUserAction->handle($data->toArray());
    }
}
```

### Actions

Actions contain the business logic for a specific use case.

#### Example: `CreateUserAction`

```php
namespace Domain\User\Actions;

use App\Infrastructure\Database\Models\User;

class CreateUserAction
{
    public function handle(array $data): User
    {
        // Business logic to create a user
    }
}
```

### Data Transfer Objects (DTOs)

DTOs are used to encapsulate data and ensure type safety.

#### Example: `CreateUserData`

```php
namespace Domain\User\Data;

class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
```

## Getting Started

### Prerequisites

- PHP
- Composer
- Laravel
- Node.js
- Yarn or npm

### Installation

1. Clone the repository:
    ```sh
    git clone <repository-url>
    cd <repository-directory>
    ```

2. Install PHP dependencies:
    ```sh
    composer install
    ```

3. Install JavaScript dependencies:
    ```sh
    yarn install
    # or
    npm install
    ```

4. Set up the environment:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

5. Run migrations:
    ```sh
    php artisan migrate
    ```

### Running the Application

Start the Laravel development server:
```sh
php artisan serve
```

### Running Tests

To run the tests, use:
```sh
php artisan test
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss any changes.

## License

This project is licensed under the MIT License.
