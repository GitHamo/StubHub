# StubHub - JSON Stub Generator for REST API Testing

StubHub is a Laravel application designed to streamline the process of generating JSON stubs for REST API testing. This tool provides robust features, including flexible stub generation, sample data seeding, and customizable constraints, making it ideal for developers and testers working with RESTful APIs.

---
### [Demo](https://stubhub-d9efcdb86fda.herokuapp.com/)

---

## Features

- **JSON Stub Generation**: Quickly create JSON stubs with various data types, including personal information, addresses, payment details, and more.
- **Sample Data Seeder**: Populate your application with realistic test data for endpoints, users, and more.
- **Custom Policies and Constraints**: Manage API stub sizes, endpoint limits, and user roles with built-in policies.
- **Category-Based Data Mapping**: Generate stubs based on predefined categories like Internet, Payment, Address, and Personal Info.

---

## Requirements

- PHP: 8.3+
- [pre-commit](https://pre-commit.com/)

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/GitHamo/StubHub.git
   cd StubHub
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up your environment:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure your database and other environment variables in `.env`.

4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Start the development server:
   ```bash
   php artisan serve
   ```

---

## Usage

1. Access the application at `http://localhost:8000`.
2. Use the provided endpoints and interface to create, manage, and test JSON stubs.
3. Customize your stubs by modifying `StubFieldContextMapper` and other related classes.

---

## Development Insights

### Core Business Logic

- **StubFieldContextMapper**: Centralized mapping of data categories, input types, and case methods for stub generation.
  - Categories include:
    - **Personal Info**: Full name, email, phone numbers, etc.
    - **Address**: City, country, postal code, etc.
    - **Internet**: URLs, IP addresses, MIME types, etc.
    - **Payment**: Credit card details, IBANs, etc.
  - Input types such as text, numbers, and booleans are defined for flexibility.

- **Policies**:
  - `UserPolicy` enforces user roles and constraints for creating and deleting endpoints.
  - `EndpointPolicy` manages endpoint ownership and deletion rights.

- **Seeders**:
  - `DatabaseSeeder` initializes the database with default admin and user roles.
  - `SampledataSeeder` and `SampledataWithHamSeeder` populate realistic test data for local environments.

---

## Contributing

We welcome contributions! Please follow these steps:
1. Fork the repository.
2. Create a feature branch.
3. Commit your changes and submit a pull request.

---

## License

This project is licensed under the [MIT License](LICENSE).
