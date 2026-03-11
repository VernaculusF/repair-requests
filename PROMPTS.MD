# PROMPTS.md

This file tracks every AI prompt made during the development of this project,
as required by the test assignment. Each entry includes the full prompt text, date, and time.

---

## PROMPT 1 — Architecture analysis and planning

**Date:** 2026-03-11
**Time:** 12:00
**Model:** GitHub Copilot (Claude Sonnet 4.6)

I'm starting a test assignment for a job application. The task is to build a Laravel web service called "Repair Requests". Before writing any code I want to fully understand the scope and plan the architecture.

Here is the full assignment text:

> Build a small web application for receiving and processing repair service requests.
>
> Roles: dispatcher and master. Auth can be simple — login by name/password with DB seeds.
>
> RepairRequest model fields: clientName, phone, address, problemText, status (new|assigned|in_progress|done|canceled), assignedTo (nullable FK to master), createdAt, updatedAt.
>
> Required pages:
> 1. Create request form — public, sets status to `new`.
> 2. Dispatcher panel — list with status filter, assign master (assigned), cancel (canceled).
> 3. Master panel — list of own requests, "Take" (assigned to in_progress), "Complete" (in_progress to done).
>
> Race condition requirement: "Take" action must be safe under parallel requests. One succeeds, the other gets 409 Conflict. Describe how to test this in README.
>
> Deliverables: source code, migrations, seeds (1 dispatcher, 2 masters, sample requests), README, DECISIONS.md (5-7 points), at least 2 automated tests, PROMPTS.md.
>
> Bonus: audit log, race_test.sh script, deployment.

I have also created `BASICRULES.md` with coding guidelines (PSR-12, SOLID, PHP 8.1+, Laravel best practices) that the AI must follow throughout the project.

**My request to AI:**
Analyse the task. Do not write any code yet. Give me a full architectural breakdown:
- roles and auth approach
- models and fields
- status transition graph
- race condition protection strategy
- application layer structure (controllers, services, middleware)
- list of routes and pages
- seed plan
- test plan
- recommended implementation order step by step
- potential pitfalls to watch out for

---

## PROMPT 2 — Database setup and environment configuration

**Date:** 2026-03-11
**Time:** 12:30
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Architecture is clear after Prompt 1. Starting implementation. First step: configure the database.

I want to use SQLite for simplicity and portability (no external DB server needed, easy to run locally without Docker).

Following `BASICRULES.md`, help me:
1. Update `.env` to use SQLite
2. Create the database file
3. Verify the connection works with a quick artisan command

---

## PROMPT 3 — Migrations: users table update and repair_requests table

**Date:** 2026-03-11
**Time:** 13:00
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Database is configured. Now I need the schema.

Following `BASICRULES.md`, generate:
1. A migration to add a `role` column (`dispatcher|master`) to the existing `users` table
2. A migration to create the `repair_requests` table with all required fields:
   - `id`, `client_name`, `phone`, `address`, `problem_text`
   - `status` (string, default `new`)
   - `assigned_to` (nullable FK to `users.id`, set null on delete)
   - `timestamps`
3. Add an index on `status` and on `assigned_to` for query performance

Use `declare(strict_types=1)` and PHP 8.1+ syntax throughout.

---

## PROMPT 4 — Enum and Models

**Date:** 2026-03-11
**Time:** 13:20
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Migrations are done. Now I need the PHP models and enum.

Following `BASICRULES.md`, generate:
1. `App\Enums\RequestStatus` — a PHP 8.1 backed string enum with cases: `New`, `Assigned`, `InProgress`, `Done`, `Canceled`. Add a helper method `label(): string` for human-readable display.
2. Update `App\Models\User` — add `role` to `$fillable`, add a `repairRequests()` hasMany relationship, add helpers `isDispatcher(): bool` and `isMaster(): bool`.
3. `App\Models\RepairRequest` — set `$fillable`, cast `status` to the `RequestStatus` enum, define `assignedMaster()` belongsTo relationship to `User`, add query scopes `scopeByStatus()` and `scopeAssignedTo()`.

---

## PROMPT 5 — Seeders and Factories

**Date:** 2026-03-11
**Time:** 13:45
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Models are ready. I need seed data so the app is usable from the first run.

Following `BASICRULES.md`, generate:
1. `UserFactory` — support creating users with a given role
2. `RepairRequestFactory` — generate realistic fake data for all fields
3. `DatabaseSeeder` — seed exactly:
   - 1 dispatcher: `dispatcher@example.com` / `password`
   - 2 masters: `master1@example.com`, `master2@example.com` / `password`
   - 7 repair requests spread across all statuses, some assigned to masters

Use `fake()` helpers, avoid hardcoded IDs, keep seeder idempotent with `firstOrCreate`.

---

## PROMPT 6 — Authentication, middleware, and base layout

**Date:** 2026-03-11
**Time:** 14:10
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Seeds are ready. Now I need authentication, middleware, and shared layout.

Following `BASICRULES.md`, generate:
1. `App\Http\Controllers\Auth\LoginController` — `showForm()`, `login()`, `logout()` methods. Use `Auth::attempt()`, redirect dispatcher to `/dispatcher`, master to `/master` after login.
2. `App\Http\Middleware\EnsureRole` — middleware that checks `auth()->user()->role` against allowed roles, aborts with 403 if mismatch.
3. Register the middleware in `bootstrap/app.php` via `$middleware->alias(['role' => EnsureRole::class])` so routes can use `->middleware('role:dispatcher')`.
4. `resources/views/layouts/app.blade.php` — base layout with:
   - Navigation showing current user email and role
   - "Logout" button (POST form with CSRF)
   - Links to `/dispatcher` and `/master` (only visible if user has appropriate role)
   - Flash message containers for success/error
   - `@yield('content')`
5. `resources/views/auth/login.blade.php` — clean form with email/password fields, CSRF token, validation error display. Extend `layouts/app`.
6. Routes in `routes/web.php` for GET `/login`, POST `/login`, POST `/logout`.

No Laravel Breeze or Jetstream — plain `Auth::attempt()` only.

---

## PROMPT 7 — Repair request creation (public form)

**Date:** 2026-03-11
**Time:** 14:40
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Auth is working. Now the public-facing part: anyone can submit a repair request without logging in.

Following `BASICRULES.md`, generate:
1. `App\Http\Requests\StoreRepairRequestRequest` — validate `client_name`, `phone`, `address`, `problem_text` (all required, appropriate max lengths, phone format).
2. `App\Services\RepairRequestService` — method `create(StoreRepairRequestRequest $request): RepairRequest` that sets status to `RequestStatus::New`, persists and returns the model.
3. `App\Http\Controllers\RepairRequestController` — `create()` and `store()` methods, inject `RepairRequestService`, redirect to `/requests/success` after store.
4. `resources/views/requests/create.blade.php` — extends `layouts/app`, clean form, inline validation errors, success flash message.
5. `resources/views/requests/success.blade.php` — extends `layouts/app`, confirmation message with request id, link back to `/requests/create`.
6. Routes: GET `/requests/create`, POST `/requests`, GET `/requests/success` — no auth middleware.

---

## PROMPT 8 — Dispatcher panel

**Date:** 2026-03-11
**Time:** 15:10
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Request creation works. Now the dispatcher panel.

Following `BASICRULES.md`, generate:
1. `App\Http\Controllers\Dispatcher\RequestController` — methods:
   - `index()` — paginated list of all requests, filter by `?status=` query param, eager-load `assignedMaster`
   - `assign(Request $httpRequest, RepairRequest $repairRequest)` — validate master id, call service
   - `cancel(RepairRequest $repairRequest)` — call service
2. Add to `RepairRequestService`:
   - `assignMaster(RepairRequest $repairRequest, int $masterId): void` — validates status allows assignment, sets `assigned_to` and status to `assigned`
   - `cancel(RepairRequest $repairRequest): void` — validates status, sets to `canceled`
3. `App\Http\Requests\AssignMasterRequest` — validate `master_id` exists in users table with role `master`
4. Blade view `resources/views/dispatcher/index.blade.php` — table with all columns, status badge colours, filter dropdown, assign form (select master), cancel button, CSRF on all forms.
5. Routes under `/dispatcher` prefix protected by `EnsureRole:dispatcher` middleware.

---

## PROMPT 9 — Master panel with race condition protection

**Date:** 2026-03-11
**Time:** 15:45
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Dispatcher panel is done. Now the master panel — this is the most critical part because of the race condition requirement.

Following `BASICRULES.md`, generate:
1. `App\Http\Controllers\Master\RequestController` — methods:
   - `index()` — requests where `assigned_to = auth()->id()`, eager-load relationships
   - `take(RepairRequest $repairRequest)` — call service, handle conflict gracefully
   - `complete(RepairRequest $repairRequest)` — call service
2. Add to `RepairRequestService`:
   - `take(RepairRequest $repairRequest, int $masterId): void` — use atomic UPDATE to guard against race:
     ```php
     $affected = RepairRequest::where('id', $repairRequest->id)
         ->where('status', RequestStatus::Assigned)
         ->where('assigned_to', $masterId)
         ->update(['status' => RequestStatus::InProgress]);
     if ($affected === 0) {
         throw new RequestAlreadyTakenException('This request is already being worked on.');
     }
     ```
   - `complete(RepairRequest $repairRequest, int $masterId): void` — validates ownership and status, updates to `done`
3. `App\Exceptions\RequestAlreadyTakenException` — custom exception with a clear message
4. Register exception rendering in `bootstrap/app.php`:
   ```php
   ->withExceptions(function (Exceptions $exceptions): void {
       $exceptions->render(function (RequestAlreadyTakenException $e) {
           return response()->json(['message' => $e->getMessage()], 409);
       });
   })
   ```
5. `resources/views/master/index.blade.php` — extends `layouts/app`, table of own requests, "Take" and "Complete" buttons with correct disabled states, flash error message for 409.
6. Routes under `/master` prefix protected by `role:master` middleware.

---

## PROMPT 10 — Automated tests and test configuration

**Date:** 2026-03-11
**Time:** 16:20
**Model:** GitHub Copilot (Claude Sonnet 4.6)

All features are implemented. Now I need at least 2 automated tests as required by the assignment.

First, configure the test environment:
1. Update `phpunit.xml` — add env variables for testing:
   ```xml
   <env name="DB_CONNECTION" value="sqlite" />
   <env name="DB_DATABASE" value=":memory:" />
   <env name="APP_KEY" value="base64:SOME_KEY_HERE_OR_GENERATE_NEW" />
   ```
   This ensures tests use an in-memory SQLite database that's fresh for each test run, not the production database.

Following `BASICRULES.md`, generate PHPUnit feature tests:

1. `tests/Feature/CreateRepairRequestTest.php`:
   - Successful submission with valid data — 302 redirect to `/requests/success`, record in DB with status `new`
   - Validation failure with empty data — validation errors returned, no record created
   - Invalid phone format — validation error on `phone` field
   - All tests use `RefreshDatabase` trait

2. `tests/Feature/RaceConditionTest.php`:
   - Seed 1 master and 1 `assigned` request for that master
   - Call the `take` service method twice in sequence simulating two requests
   - First call succeeds, status becomes `in_progress`
   - Second call throws `RequestAlreadyTakenException` (or HTTP layer returns 409)
   - Assert DB has exactly one record with status `in_progress`
   - Use `RefreshDatabase`, factories for setup, `actingAs()` for auth

Run tests with `php artisan test` — all pass.

---

## PROMPT 11 — README and DECISIONS.md

**Date:** 2026-03-11
**Time:** 16:50
**Model:** GitHub Copilot (Claude Sonnet 4.6)

All features and tests are done. I need the final documentation files.

Generate:

1. `README.md` with:
   - Project description (one paragraph)
   - Local setup without Docker: `composer install`, `.env` config, `php artisan migrate --seed`, `php artisan serve`
   - Test user credentials (dispatcher, master1, master2 — all with `password`)
   - How to verify race condition: two curl commands run in separate terminals targeting the same request id, expected response codes explained
   - How to run automated tests: `php artisan test`

2. `DECISIONS.md` with 5–7 architectural decisions in this format per item: **Decision**, **Why**:
   - SQLite as database
   - PHP 8.1 backed enum for status field
   - Atomic UPDATE instead of pessimistic locking for race condition
   - Service layer to keep business logic out of controllers
   - Custom EnsureRole middleware instead of Gates/Policies (simpler for two-role system)
   - No Breeze/Jetstream (unnecessary overhead for a test task)
   - Eager loading with `with()` to prevent N+1 on list pages

---

## PROMPT 12 — Audit log for request actions

**Date:** 2026-03-11
**Time:** 17:00
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Core features are complete and documented. Now adding the first bonus feature: audit log (history of actions on each request).

Following `BASICRULES.md`, generate:
1. Migration: create `request_events` table:
   - `id`, `repair_request_id` (FK → repair_requests, cascade delete)
   - `user_id` (FK → users, nullable — for system-generated events)
   - `action` (string): `created|assigned|taken|completed|canceled`
   - `old_status`, `new_status` (string, nullable)
   - `comment` (text, nullable — for future notes)
   - `timestamps`
   - Index on `repair_request_id` and `created_at`

2. `App\Models\RequestEvent` — model with `request()` belongsTo, `user()` belongsTo inverse. Cast `old_status`, `new_status` to `RequestStatus` enum.

3. Update `App\Models\RepairRequest` — add `events()` hasMany relationship.

4. Create `App\Services\AuditService` with method:
   ```php
   public function logAction(RepairRequest $request, string $action, ?int $userId = null): void
   ```
   This service is injected into `RepairRequestService` methods and called on every status change.

5. Update `RepairRequestService` — log all actions:
   - `create()` → log `created`
   - `assignMaster()` → log `assigned` with old_status=`new`, new_status=`assigned`, user_id=dispatcher
   - `take()` → log `taken` with old_status=`assigned`, new_status=`in_progress`
   - `complete()` → log `completed` with old_status=`in_progress`, new_status=`done`
   - `cancel()` → log `canceled` with new_status=`canceled`

6. Add to dispatcher/master panels (`resources/views/dispatcher/index.blade.php` and `resources/views/master/index.blade.php`):
   - Optional expandable "history" button for each request showing `RequestEvent` records in a table or chronological list
   ```blade
   @foreach($request->events as $event)
       <li>{{ $event->action }} → {{ $event->new_status?->label() }} ({{ $event->created_at->format('Y-m-d H:i') }})</li>
   @endforeach
   ```

This satisfies the bonus requirement: "история действий по заявке (audit log / events)".

---

## PROMPT 13 — Docker Compose setup for deployment

**Date:** 2026-03-11
**Time:** 17:20
**Model:** GitHub Copilot (Claude Sonnet 4.6)

Audit log is complete. Now adding Docker Compose for easy local development and potential deployment (bonus requirement: "размещение на сервере").

Following `BASICRULES.md`, generate:
1. `Dockerfile` — multi-stage build:
   - Base: `php:8.2-fpm-alpine`
   - Install composer, Laravel dependencies, extensions (pdo_sqlite, zip)
   - WORKDIR `/app`
   - Copy `.dockerignore` (exclude vendor, node_modules, .git, etc.)
   - Final stage: copy vendor and app, set permissions
   - Expose 9000 (fpm)

2. `docker-compose.yml`:
   - `app` service: builds Dockerfile, volumes: `.:/app`
   - `nginx` service: `nginx:alpine`, volumes: static config mounted
   - `nginx.conf`: listen 80, proxy_pass to `app:9000`
   - Database: SQLite in Docker volume `/app/database/database.sqlite`
   - Environment: copy from `.env` or use `.env.docker` with `APP_KEY`, `DB_CONNECTION=sqlite`
   - Expose: `80`
   - Networks: default

3. `.dockerignore`:
   ```
   .git
   .gitignore
   .env
   node_modules
   vendor
   storage/logs
   bootstrap/cache
   tests
   ```

4. In `docker-compose.yml` add build hook:
   ```yaml
   app:
     build: .
     command: sh -c "composer install && php artisan migrate --seed && php-fpm"
   ```

5. Create `.env.docker` with:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:... (or leave empty, docker-compose will generate)
   DB_CONNECTION=sqlite
   DB_DATABASE=/app/database/database.sqlite
   ```

6. Update `README.md` with Docker section:
   - `docker compose up -d` starts app at `http://localhost`
   - `docker compose logs -f` to see logs
   - `docker compose down` to stop
   - Migrations and seeds run automatically on first boot

This satisfies the bonus requirement: "размещение на сервере (деплой)".

---

## PROMPT 14 — Git initialization and commit history

**Date:** 2026-03-11
**Time:** 17:35
**Model:** GitHub Copilot (Claude Sonnet 4.6)

All features and deployment config are complete. Initialize Git and create a clean, meaningful commit history.

1. Initialize Git (if not already):
   ```bash
   git init
   git config user.name "Your Name"
   git config user.email "your.email@example.com"
   ```

2. Create `.gitignore` (standard Laravel):
   ```
   /node_modules
   /vendor
   /.env
   /.env.*.local
   /database/database.sqlite
   /storage/logs/*
   /bootstrap/cache/*
   .DS_Store
   ```

3. Commit in this order, with meaningful messages:
   - **Commit 1:** "Initial project setup with migrations"
     - Add: migrations files (users, repair_requests, request_events)
     
   - **Commit 2:** "Add models, enums, factories and seeders"
     - Add: `app/Enums/`, `app/Models/`, `database/factories/`, `database/seeders/`
     
   - **Commit 3:** "Implement authentication and middleware"
     - Add: `app/Http/Controllers/Auth/`, `app/Http/Middleware/EnsureRole`, `resources/views/auth/`, `resources/views/layouts/`
     
   - **Commit 4:** "Implement public repair request form"
     - Add: `app/Http/Controllers/RepairRequestController`, `app/Http/Requests/StoreRepairRequestRequest`, `resources/views/requests/`
     
   - **Commit 5:** "Implement dispatcher panel"
     - Add: `app/Http/Controllers/Dispatcher/`, `app/Http/Requests/AssignMasterRequest`, dispatcher views
     
   - **Commit 6:** "Implement master panel with race condition protection"
     - Add: `app/Http/Controllers/Master/`, `app/Exceptions/RequestAlreadyTakenException`, master views
     - Modify: `bootstrap/app.php` with exception handler
     
   - **Commit 7:** "Implement audit log for request actions"
     - Add: migration for request_events, `app/Models/RequestEvent`, `app/Services/AuditService`
     - Modify: services, controllers, views to log events
     
   - **Commit 8:** "Implement automated tests"
     - Add: `tests/Feature/CreateRepairRequestTest.php`, `tests/Feature/RaceConditionTest.php`
     - Modify: `phpunit.xml`
     
   - **Commit 9:** "Add Docker Compose for deployment"
     - Add: `Dockerfile`, `docker-compose.yml`, `.env.docker`, `nginx.conf`, `.dockerignore`
     
   - **Commit 10:** "Add race_test.sh script for manual testing"
     - Add: `race_test.sh`
     
   - **Commit 11:** "Complete documentation"
     - Add: `README.md`, `DECISIONS.md`, `BASICRULES.md`, `PROMPTS.md`, `TODO.md`

Each commit should be atomic and logical, so the reviewer can follow the development process step by step.

---

## PROMPT 15 — Bonus: race_test.sh script

**Date:** 2026-03-11
**Time:** 17:45
**Model:** GitHub Copilot (Claude Sonnet 4.6)

All required deliverables and first bonus (audit log) and deployment (Docker) are done. Adding the final bonus: race condition test script.

Generate `race_test.sh` — a bash script that:
1. Accepts REQUEST_ID and SESSION_COOKIE as arguments
2. Fires two parallel curl POST requests to `/master/requests/{id}/take` simultaneously using `&` and `wait`
3. Prints both HTTP response codes with labels (Request 1 / Request 2)
4. Includes comments explaining the expected output (one 200 or 302, one 409) and how to obtain a session cookie from the browser
5. Adds a note for Windows users to run it under WSL or Git Bash
