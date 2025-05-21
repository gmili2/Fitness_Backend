# Fitness Backend

<p align="center"><img src="https://via.placeholder.com/400" alt="Project Logo"></p>

## Description

Fitness Backend is a robust backend application designed to manage fitness-related data and operations. Built using the Laravel framework, it provides a scalable and efficient solution for fitness applications.

## Features

- User management (Clients, Admins)
- Fitness data tracking (e.g., scans, activities)
- API endpoints for frontend integration
- Secure authentication and authorization
- Database migrations and seeders for easy setup

## Installation

### Using Docker

1. Build and start the Docker containers:
   ```bash
   docker-compose up --build
   ```

2. Run database migrations and seeders inside the application container:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

3. Access the application at `http://localhost`.

### Without Docker

1. Clone the repository:
   ```bash
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```bash
   cd Fitness_Backend
   ```

3. Install dependencies:
   ```bash
   composer install
   npm install
   ```

4. Set up the environment file:
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database and other configurations.

5. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

- Access the API endpoints via `http://localhost`.
- Use tools like Postman to test the API.

## Contributing

Contributions are welcome! Please follow the [contribution guidelines](CONTRIBUTING.md).

## License

This project is licensed under the [MIT License](LICENSE).
