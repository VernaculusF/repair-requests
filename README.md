# RepairHub — Repair Request Management System

A modern Laravel 12 web application for managing repair service requests with role-based access control, audit logging, and race condition protection.

## Features

- **Public Request Submission** — anyone can submit a repair request without registration
- **Dispatcher Dashboard** — view all requests with statistics, filter by status, assign to masters, cancel requests
- **Master Panel** — accept assigned requests, track progress, mark as completed
- **Race Condition Protection** — atomic database updates prevent concurrent "take" conflicts
- **Audit Logging** — all status changes are recorded with timestamps and user info
- **Responsive Design** — modern UI that works on desktop and mobile

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL 8.0 (Docker) / SQLite (local/testing) |
| Web Server | Nginx (Docker) / Artisan (local) |
| Containerization | Docker Compose |

## Quick Start (Local)

```bash
# Install dependencies
composer install --ignore-platform-reqs

# Configure environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations with seed data
php artisan migrate --seed

# Start dev server
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000)

## Docker Setup

See [DOCKER_SETUP.md](DOCKER_SETUP.md) for Docker Compose instructions.

```bash
cp .env.docker .env
docker compose up -d
docker compose exec app php artisan migrate --seed
```

Open [http://localhost](http://localhost)

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Dispatcher | `dispatcher@example.com` | `password` |
| Master | `master1@example.com` | `password` |
| Master | `master2@example.com` | `password` |

## Application Workflow

```
[Client] → Submit Request (public form)
              ↓
[Status: NEW] → Dispatcher assigns a master
              ↓
[Status: ASSIGNED] → Master accepts the request
              ↓
[Status: IN_PROGRESS] → Master completes the work
              ↓
[Status: DONE]

At any point (before DONE): Dispatcher can cancel → [Status: CANCELED]
```

## Running Tests

```bash
# Run all tests (uses in-memory SQLite)
php artisan test

# Run specific test suites
php artisan test --filter=CreateRepairRequestTest
php artisan test --filter=RaceConditionTest
```

## Project Structure

```
app/
├── Enums/RequestStatus.php          # Status enum with labels and colors
├── Exceptions/                      # Custom exceptions (race condition)
├── Http/
│   ├── Controllers/
│   │   ├── Auth/LoginController.php # Authentication
│   │   ├── RepairRequestController.php  # Public request form
│   │   ├── Dispatcher/RequestController.php  # Dispatcher panel
│   │   └── Master/RequestController.php      # Master panel
│   ├── Middleware/EnsureRole.php     # Role-based access
│   └── Requests/                    # Form validation
├── Models/                          # Eloquent models
└── Services/
    ├── RepairRequestService.php     # Business logic
    └── AuditService.php             # Event logging

database/
├── migrations/                      # DB schema
├── factories/                       # Test data factories
└── seeders/                         # Demo data seeder

resources/views/
├── layouts/app.blade.php            # Main layout with design system
├── welcome.blade.php                # Landing page
├── auth/login.blade.php             # Login form
├── requests/                        # Public request views
├── dispatcher/                      # Dispatcher dashboard
└── master/                          # Master panel
```

## Key Design Decisions

See [DECISIONS.md](DECISIONS.md) for detailed architectural rationale.

- **Atomic UPDATE for race protection** — single conditional UPDATE ensures only one master can "take" a request
- **Service layer** — business logic separated from controllers for testability
- **PHP 8.1+ enums** — type-safe status values with helper methods
- **Eager loading** — `with()` on listing pages prevents N+1 queries
- **Audit events table** — immutable event rows for full request history
