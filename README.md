# Repair Requests Web Application

A Laravel 11 web application for managing repair service requests with role-based access control (dispatcher and master roles).

## Features

- **Public Request Submission**: Any user can submit a repair request without authentication
- **Dispatcher Panel**: Dispatchers can view all requests, filter by status, assign to masters, and cancel requests
- **Master Panel**: Masters can view requests assigned to them, accept requests, and mark them as completed
- **Race Condition Protection**: The "Take" action uses atomic database updates to safely handle concurrent requests
- **Audit Logging**: All actions are logged with timestamps and user information (bonus feature)

## Technology Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.2
- **Database**: MySQL 8.0 (Docker) or SQLite (testing/local)
- **Web Server**: Nginx (Docker) / Laravel Artisan (local)
- **Containerization**: Docker Compose

## Local Setup (Without Docker)

### Prerequisites
- PHP 8.2+ with extensions: pdo, pdo_sqlite, mbstring, json, bcmath, fileinfo, curl
- Composer
- SQLite (built-in, no installation needed)

### Installation Steps

1. **Clone or download the project**
   ```bash
   cd repair-requests
   ```

2. **Install dependencies**
   ```bash
   composer install --ignore-platform-reqs
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # .env is already configured to use SQLite
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations**
   ```bash
   php artisan migrate --seed
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

   Application will be available at `http://localhost:8000`

## Docker Setup (Recommended)

See [DOCKER_SETUP.md](DOCKER_SETUP.md) for complete Docker Compose installation and usage instructions.

## Test Credentials

All test users have password: **`password`**

| Email | Role | Purpose |
|-------|------|---------|
| `dispatcher@example.com` | Dispatcher | Manage and assign requests |
| `master1@example.com` | Master | Work on assigned requests |
| `master2@example.com` | Master | Work on assigned requests |

## Application Workflow

### 1. Submit Request (Public)
- Navigate to `/requests/create`
- Fill in: client name, phone, address, problem description
- System creates request with status `new`

### 2. Dispatcher Assigns Request
- Login as dispatcher@example.com
- Go to `/dispatcher`
- View all requests, filter by status
- Select a master and click "Assign" to change status to `assigned`
- Click "Cancel" to mark request as `canceled`

### 3. Master Accepts and Completes
- Login as master1@example.com or master2@example.com
- Go to `/master`
- Click "Accept Request" to change status from `assigned` to `in_progress`
- Click "Mark as Complete" to change status from `in_progress` to `done`

## Race Condition Testing

The "Take" (Accept) action demonstrates race condition protection. When a master clicks "Accept Request", the system uses an atomic database UPDATE to ensure only one master can take the request.

### Running Automated Tests

```bash
php artisan test
```

Tests use in-memory SQLite database and cover both successful submissions and race condition scenarios.

### Manual Test with curl

See [race_test.sh](race_test.sh) for an automated testing script, or test manually:

```bash
# Get session cookie
curl -c cookies.txt -X POST http://localhost:8000/login \
  -d "email=master1@example.com&password=password&_token=<CSRF_TOKEN>"

# First take (should succeed)
curl -b cookies.txt -X POST http://localhost:8000/master/requests/1/take -L

# Second take (should get error)
curl -b cookies.txt -X POST http://localhost:8000/master/requests/1/take -L
```

**Expected Result:**
- First request: Status changes to `in_progress` (200 with success message)
- Second request: Error "This request is already being worked on." (302 redirect with error flash)

## Running Tests

Tests use SQLite in-memory database for speed and isolation.

```bash
php artisan test
```

### Available Tests

- `CreateRepairRequestTest`: Validation and successful submission of repair requests
- `RaceConditionTest`: Concurrent "Take" action protection

## Project Structure

```
app/
  Enums/RequestStatus.php
  Exceptions/RequestAlreadyTakenException.php
  Http/Controllers/
    Auth/LoginController.php
    RepairRequestController.php
    Dispatcher/RequestController.php
    Master/RequestController.php
  Http/Middleware/EnsureRole.php
  Http/Requests/
    StoreRepairRequestRequest.php
    AssignMasterRequest.php
  Models/
    User.php
    RepairRequest.php
    RequestEvent.php
  Services/RepairRequestService.php

database/
  migrations/
  factories/
  seeders/

resources/views/
  layouts/app.blade.php
  auth/login.blade.php
  requests/
  dispatcher/
  master/

routes/web.php
```

## Key Design Decisions

See [DECISIONS.md](DECISIONS.md) for detailed architectural decisions and rationale.

## Bonus Features

- ✅ Audit log system (request events with timestamps and user tracking)
- ✅ race_test.sh script for manual race condition testing  
- ✅ Docker Compose for production-ready deployment
- ✅ Atomic UPDATE protection against race conditions
- ✅ Clean git history with atomic commits per feature

## License

MIT License
