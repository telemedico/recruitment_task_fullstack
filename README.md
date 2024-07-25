# Currency Exchange Rates Application

This project is a recruitment task to create a currency exchange rates application for employees of currency exchange offices. The application displays a table with buy and sell rates for various currencies on a selected date.

## Overview

The goal of this task is to develop a full-stack application using React for the frontend and PHP Symfony for the backend. The backend fetches currency exchange rates from the NBP (Narodowy Bank Polski) API and provides them to the frontend via an API endpoint. The frontend displays these rates in a user-friendly table and allows users to select a date to view historical rates.

## Features

- Display exchange rates for multiple currencies.
- Fetch rates from the NBP API.
- Show buy and sell rates based on NBP average rates.
- Allow users to select a date to view historical rates.
- Update the URL to share specific date views.
- User-friendly UI with clear presentation of data.
- Handle errors and loading states gracefully.

## Supported Currencies

The application supports the following currencies:
- Euro (EUR)
- US Dollar (USD)
- Czech Koruna (CZK)
- Indonesian Rupiah (IDR)
- Brazilian Real (BRL)

## Exchange Rate Calculations

- For EUR and USD:
     - Buy rate = NBP rate - 0.05 PLN
     - Sell rate = NBP rate + 0.07 PLN
- For CZK, IDR, and BRL:
     - No buy rate (not handled by the exchange office)
     - Sell rate = NBP rate + 0.15 PLN


## Domain-Driven Design (DDD) and Clean Architecture

### Domain-Driven Design (DDD)

The project utilizes Domain-Driven Design principles to ensure that the business logic is well-structured and easily understandable. Key components include:

- **Entities**: Represent the core business objects with unique identifiers, such as `CurrencyRate`.
- **Value Objects**: Immutable objects that define a set of attributes, such as `CurrencyCode`, `CurrencyName`, and `ExchangeRate`.
- **Aggregates**: Clusters of entities and value objects that are treated as a single unit for data changes.

### Clean Architecture

The project follows Clean Architecture principles to separate concerns and create a maintainable and testable codebase. The architecture layers include:

- **Presentation Layer**: Contains the frontend React components and Symfony controllers.
- **Application Layer**: Contains the services and use cases that orchestrate the application's business logic.
- **Domain Layer**: Contains the core business logic, including entities, value objects, and domain services.
- **Infrastructure Layer**: Contains the implementation details for external systems, such as the NBP API client.

### Design Patterns

The project employs several design patterns to promote code reuse and maintainability:

- **Factory Pattern**: Used to create instances of complex objects. The `CurrencyRateFactory` is responsible for creating `CurrencyRate` instances.
- **Composition over Inheritance**: Preferred to achieve flexibility by composing objects with various behaviors, rather than inheriting from a base class.
- **Dependency Injection**: Used to inject dependencies into classes, promoting loose coupling and easier testing.


## Getting Started

### Prerequisites

Ensure you have the following installed:
- Docker
- Docker Compose

### Setup

1. Clone the repository:

```sh
git clone https://github.com/pietrak98/telemedi-exchange.git
cd telemedi-exchange
```

2. Build and run the containers:
```sh
chmod +x scripts/build
./scripts/build
```
3. Access the application:
   http://telemedi-zadanie.localhost/

### Running the Project Manually
If you prefer to run the project manually, follow these steps:
```sh
docker compose up -d --build
docker exec -it telemedi-php composer install --optimize-autoloader
docker exec -it telemedi-node npm install
docker exec -it telemedi-node npm run build
```

### Testing

Run the tests using the following command in php container:


```sh
# Run all tests
./vendor/bin/phpunit
```

## API Endpoints


Get exchange rates for a specific date: `GET /api/exchange-rates/{date}`
