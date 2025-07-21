# MyTheresa POC

This project runs a Symfony app (PHP8.2 FPM + Nginx + Postgres) in Docker containers, automatically applies migrations and fixtures on startup.

## Requirements

- Docker
- Docker Compose

## Quick start

### 1. **Clone the repo**
```
   git clone https://github.com/asikranjm/MytheresaPOC.git
   cd MytheresaPOC
````

### 2. **Build & run containers**
````
docker compose -f mytheresa_docker/docker-compose.yaml up --build -d
````
This will:

- Build the PHP image

- Start Postgres, run migrations & load fixtures

- Start PHP‑FPM & Nginx

### 3. **Verify everything is up**

App: http://localhost:8050

DB port (if you need it locally): 8051

### 4. **Run your tests**
````
docker exec mytheresa-php php bin/phpunit
````
or, if you prefer raw Docker:
````
docker exec -it mytheresa-php bash
php bin/phpunit
````

### 5. **Shut down**
````
docker compose down
````

# Architecture
This application follows a hexagonal (ports & adapters) structure to keep domain logic clean and decoupled:

````
src/
├── Application     # UseCases, Handlers, Listeners, Response DTOs & Services
├── Domain          # Core entities (AggregateRoot, ValueObjects) & Repository interfaces
├── Infrastructure  # Doctrine mappings, Repositories, Fixtures
└── UI              # Controllers, request/response layer (API endpoints)
````

**Domain**: holds your business rules, entities like Product and Category, and ProductRepositoryInterface.

**Application**: orchestrates use cases (e.g. ListProductsHandler), defines input/output DTOs, and dispatches events.

**Infrastructure**: provides concrete implementations of repositories (Doctrine ORM), data fixtures for testing, and any external integrations.

**UI**: exposes the functionality via HTTP (e.g. ProductListController), translates requests into application commands, and returns JSON responses.

This separation ensures you can swap out frameworks, persistence layers, or external APIs without touching your domain logic.


#### Notes

For production setups, you’ll likely want to remove fixtures:load and handle migrations separately with:
````
php bin/console d:s:u --dump-sql --complete
````
The Postman collection Environments and EP are inside the repository with the files "Mytheresa API.postman_collection.json" and "MyTheresa-Local.postman_environment.json".

### The Endpoint for products is:

> http://localhost:8050/api/products?category=boots&priceLessThan=34000