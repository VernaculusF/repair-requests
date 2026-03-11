# TODO.md — Task Progress Tracker

Tracks implementation status for each prompt in PROMPTS.md.
Updated manually after each step is completed.

---

## Status Legend
- `[ ]` — not started
- `[~]` — in progress
- `[x]` — done

---

## PROMPT 1 — Architecture analysis and planning
- [x] Full assignment text documented
- [x] Architectural breakdown done (roles, models, status graph, race strategy, layers, routes, seeds, tests, order, pitfalls)

---

## PROMPT 2 — Database setup
- [ ] `.env` updated to use SQLite (`DB_CONNECTION=sqlite`, `DB_DATABASE=database/database.sqlite`)
- [ ] `database/database.sqlite` file created
- [ ] Connection verified (`php artisan db:show`)

---

## PROMPT 3 — Migrations
- [ ] Migration: add `role` column to `users` table
- [ ] Migration: create `repair_requests` table
- [ ] Index on `status` and `assigned_to` columns
- [ ] FK constraint: `assigned_to` → `users.id` (set null on delete)
- [ ] Migrations run successfully (`php artisan migrate`)

---

## PROMPT 4 — Enum and Models
- [ ] `app/Enums/RequestStatus.php` — PHP 8.1 backed enum with cases + `label()` method
- [ ] `app/Models/User.php` — update with `role`, `repairRequests()`, `isDispatcher()`, `isMaster()`
- [ ] `app/Models/RepairRequest.php` — `$fillable`, `status` cast, relationships, scopes

---

## PROMPT 5 — Seeders and Factories
- [ ] `UserFactory.php` — supports `role` state
- [ ] `RepairRequestFactory.php` — realistic fake data
- [ ] `DatabaseSeeder.php` — 1 dispatcher, 2 masters, 7 requests
- [ ] Seeds run successfully (`php artisan db:seed`)

---

## PROMPT 6 — Authentication, middleware, and base layout
- [ ] `app/Http/Controllers/Auth/LoginController.php` — login/logout with role-based redirect
- [ ] `app/Http/Middleware/EnsureRole.php` — role check, abort 403
- [ ] Middleware alias registered in `bootstrap/app.php`: `$middleware->alias(['role' => EnsureRole::class])`
- [ ] `resources/views/layouts/app.blade.php` — nav, logout button, flash containers, `@yield('content')`
- [ ] `resources/views/auth/login.blade.php` — form, CSRF, error display
- [ ] Routes: GET `/login`, POST `/login`, POST `/logout`

---

## PROMPT 7 — Public repair request creation form
- [ ] `app/Http/Requests/StoreRepairRequestRequest.php` — validation for 4 fields
- [ ] `app/Services/RepairRequestService.php` — `create()` method
- [ ] `app/Http/Controllers/RepairRequestController.php` — `create()`, `store()`
- [ ] `resources/views/requests/create.blade.php` — form, inline errors
- [ ] `resources/views/requests/success.blade.php` — success page
- [ ] Routes: GET `/requests/create`, POST `/requests`, GET `/requests/success`

---

## PROMPT 8 — Dispatcher panel
- [ ] `app/Http/Controllers/Dispatcher/RequestController.php` — `index()`, `assign()`, `cancel()`
- [ ] `RepairRequestService` extended — `assignMaster()`, `cancel()`
- [ ] `app/Http/Requests/AssignMasterRequest.php` — validate master exists
- [ ] `resources/views/dispatcher/index.blade.php` — table, badges, filter, assign form, cancel button
- [ ] Routes under `/dispatcher` with `role:dispatcher` middleware
- [ ] Eager loading `with('assignedMaster')` to avoid N+1

---

## PROMPT 9 — Master panel with race condition protection
- [ ] `app/Http/Controllers/Master/RequestController.php` — `index()`, `take()`, `complete()`
- [ ] `RepairRequestService` — `take()` with atomic UPDATE, `complete()`
- [ ] `app/Exceptions/RequestAlreadyTakenException.php` — custom exception with message
- [ ] Exception rendering in `bootstrap/app.php` via `->withExceptions()` to return 409
- [ ] `resources/views/master/index.blade.php` — table, buttons with disabled states, error flash
- [ ] Routes under `/master` with `role:master` middleware

---

## PROMPT 10 — Automated tests and test configuration
- [ ] Update `phpunit.xml` — add env vars for in-memory SQLite
- [ ] `tests/Feature/CreateRepairRequestTest.php` — success, validation, phone validation
- [ ] `tests/Feature/RaceConditionTest.php` — parallel take test, first succeeds, second throws
- [ ] All tests pass (`php artisan test`)

---

## PROMPT 11 — Documentation (README + DECISIONS)
- [ ] `README.md` — description, local setup, credentials, race condition test, `php artisan test`
- [ ] `DECISIONS.md` — 5–7 decisions with Decision + Why format

---

## PROMPT 12 — Audit log for request actions (BONUS)
- [ ] Migration: `request_events` table (id, repair_request_id FK, user_id FK, action, old_status, new_status, comment, timestamps)
- [ ] `app/Models/RequestEvent.php` — relationships with `request()`, `user()`
- [ ] Update `RepairRequest` — add `events()` hasMany
- [ ] `app/Services/AuditService.php` — `logAction()` method
- [ ] Update `RepairRequestService` — log all actions via `AuditService`
- [ ] Add history display in dispatcher/master views (expandable event list)

---

## PROMPT 13 — Docker Compose setup for deployment (BONUS)
- [ ] `Dockerfile` — multi-stage PHP 8.2 FPM Alpine with composer, extensions, workdir
- [ ] `docker-compose.yml` — app (PHP FPM), nginx (port 80), SQLite volume, env vars
- [ ] `nginx.conf` — reverse proxy to app:9000
- [ ] `.dockerignore` — exclude vendor, node_modules, .git, tests, etc.
- [ ] `.env.docker` — `APP_KEY`, `DB_CONNECTION=sqlite`, `DB_DATABASE=/app/database/database.sqlite`
- [ ] Update `README.md` with Docker section: `docker compose up -d`

---

## PROMPT 14 — Git initialization and commit history
- [ ] `.gitignore` created — standard Laravel rules
- [ ] Git initialized: `git init`, `git config`
- [ ] 11 atomic commits in logical order:
   1. Initial migrations setup
   2. Models, enums, factories, seeders
   3. Authentication + middleware
   4. Public request creation
   5. Dispatcher panel
   6. Master panel + race condition
   7. Audit log
   8. Automated tests
   9. Docker Compose
   10. race_test.sh
   11. Complete documentation

---

## PROMPT 15 — Bonus: race_test.sh script
- [ ] `race_test.sh` — accepts REQUEST_ID and SESSION_COOKIE args
- [ ] Fires two parallel curl POST requests to `/master/requests/{id}/take`
- [ ] Prints both HTTP codes with labels
- [ ] Documented expected output (one 200/302, one 409)
- [ ] WSL note for Windows users

---

## Deliverables Checklist (from assignment)

| Item | Status | Prompt |
|---|---|---|
| Source code | `[ ]` | 3–9 |
| DB migrations | `[ ]` | 3, 12 |
| Seeds (1 dispatcher, 2 masters, requests) | `[ ]` | 5 |
| README.md | `[ ]` | 11 |
| DECISIONS.md | `[ ]` | 11 |
| ≥ 2 automated tests | `[ ]` | 10 |
| PROMPTS.md | `[x]` | 1–15 |
| **BONUS:** race_test.sh | `[ ]` | 15 |
| **BONUS:** audit log | `[ ]` | 12 |
| **BONUS:** Docker Compose | `[ ]` | 13 |
| **BONUS:** error messages UI | `[~]` | 6–9 (part of views) |
| **BONUS:** service layer | `[~]` | 7–9 (RepairRequestService) |

---

## Gaps Fixed from Earlier Analysis

- [x] Middleware alias registration → added to Prompt 6
- [x] Base Blade layout → added to Prompt 6
- [x] Success page after request creation → added to Prompt 7
- [x] Exception rendering for 409 → added to Prompt 9
- [x] phpunit.xml test config → added to Prompt 10
- [x] Audit log → PROMPT 12 (bonus)
- [x] Docker Compose → PROMPT 13 (bonus)
- [x] Git commit history → PROMPT 14 (bonus)
