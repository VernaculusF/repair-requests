# Architectural Decisions

This document describes key design decisions and rationale for the Repair Requests application.

## 1. Database: SQLite for Local Development, MySQL for Docker

**Decision**: Use SQLite as the default database for local development and testing, MySQL 8.0 for Docker deployment.

**Why**:
- **Portability**: SQLite requires no external service, making development frictionless on Windows, Mac, and Linux
- **Testing**: SQLite in-memory database (`:memory:`) enables fast, isolated test runs
- **Docker Production**: MySQL provides better production-readiness for containerized deployments
- **Environment Parity**: Docker configuration mirrors production setup while local dev can use SQLite

**Implementation**:
- `.env` configured for SQLite (local development)
- `.env.docker` configured for MySQL (Docker environment)
- `phpunit.xml` uses in-memory SQLite for all tests

---

## 2. PHP 8.1+ Backed Enum for Request Status

**Decision**: Use PHP 8.1 backed string enum for the `RequestStatus` type instead of simple string values.

**Why**:
- **Type Safety**: Enum cases are type-checked at compile time; misspelled status values cause errors immediately
- **IDE Support**: Autocomplete and refactoring tools understand enum values precisely
- **Self-Documenting**: `RequestStatus::Assigned` is clearer than the magic string `'assigned'`
- **Database Consistency**: Backed enum ensures only valid values can be persisted
- **Helper Methods**: Custom methods like `label()` and `color()` encapsulate status presentation logic

**Implementation**:
```php
enum RequestStatus: string {
    case New = 'new';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Canceled = 'canceled';
    
    public function label(): string { ... }
    public function color(): string { ... }
}
```

---

## 3. Atomic UPDATE for Race Condition Protection

**Decision**: Use atomic database UPDATE to protect against race conditions in the "Take" action instead of pessimistic locking or optimistic locking.

**Why**:
- **Simplicity**: Single atomic UPDATE is simpler than pessimistic locks (slower, deadlock-prone)
- **Performance**: No row locks, no waiting — either one master succeeds or gets exception
- **Correctness**: Atomic operation guarantees only one master transitions `assigned` → `in_progress`
- **Failure Semantics**: Clear: if 0 rows affected, request already taken by someone else

**Implementation**:
```php
$affected = RepairRequest::where('id', $id)
    ->where('status', 'assigned')
    ->where('assigned_to', $masterId)
    ->update(['status' => 'in_progress']);

if ($affected === 0) {
    throw new RequestAlreadyTakenException('...');
}
```

**Trade-off**: Cannot distinguish between "already taken by another master" vs "not assigned to you" in UI (both throw same exception). This is acceptable given the race condition is rare and the error message is clear.

---

## 4. Service Layer for Business Logic

**Decision**: Encapsulate business logic in `RepairRequestService` instead of putting it directly in controllers.

**Why**:
- **Separation of Concerns**: Controllers handle HTTP, services handle domain logic
- **Testability**: Services can be tested in isolation without mocking HTTP/request objects
- **Reusability**: Multiple controllers can use the same service method
- **Maintainability**: Business rules are in one place, not scattered across controllers

**Implementation**:
- `RepairRequestService::create()` — public form submission
- `RepairRequestService::assignMaster()` — dispatcher assignment
- `RepairRequestService::take()` — master accepts request (race condition protected)
- `RepairRequestService::complete()` — master marks done
- `RepairRequestService::cancel()` — dispatcher cancels

---

## 5. Custom EnsureRole Middleware Instead of Gates/Policies

**Decision**: Implement a lightweight custom middleware `EnsureRole` instead of Laravel's Gates or Policies.

**Why**:
- **Simplicity**: Only two roles (dispatcher, master) with no complex authorization rules
- **Performance**: Custom middleware is faster than Policy evaluation for simple role checks
- **Clarity**: `middleware('role:dispatcher')` is self-documenting
- **Context**: Policies shine with resource-based access control; this app uses only role-based control

**Implementation**:
```php
Route::middleware(['auth', 'role:dispatcher'])->group(function () {
    Route::get('/dispatcher', [DispatcherController::class, 'index']);
    // ...
});
```

The middleware checks `auth()->user()->role` against allowed roles and returns 403 if unauthorized.

---

## 6. No Laravel Breeze or Jetstream

**Decision**: Implement authentication from scratch using `Auth::attempt()` instead of scaffolding packages.

**Why**:
- **Simplicity**: Test assignment requires minimal authentication (simple login/logout)
- **Control**: No hidden middleware or views to work around
- **Size**: Breeze adds unnecessary files and dependencies for this scope
- **Learning**: Demonstrates understanding of Laravel auth fundamentals

**Implementation**:
- Plain `LoginController` with `showForm()`, `login()`, `logout()`
- Simple login form without password reset, email verification, or 2FA
- Session-based auth, no tokens
- Test seeds users with hashed passwords directly

---

## 7. Eager Loading to Prevent N+1 Queries

**Decision**: Always use `->with()` to eager-load relationships when listing requests.

**Why**:
- **Performance**: 1 query + 1 per relationship instead of N+1
- **Correctness**: Lazy loading in loops (common bug) is prevented by design
- **Scalability**: Page list remains fast as record count grows

**Implementation**:
```php
RepairRequest::with('assignedMaster')
    ->with('events')
    ->paginate(15)
```

---

## 8. Request Events (Audit Log) as Separate Model

**Decision**: Store audit log entries in a separate `request_events` table instead of a JSON column or event sourcing.

**Why**:
- **Queryability**: Can filter and aggregate events without loading full request
- **Immutability**: Events are append-only (no updates to history)
- **Relationships**: Can join event user data and request data efficiently
- **Simplicity**: Relational model is easier to understand than event sourcing

**Implementation**:
- `RequestEvent` model with: repair_request_id, user_id, action, old_status, new_status, comment, timestamps
- Events created automatically when status changes (can add in service methods)
- Displayed in master/dispatcher views with expansion for details

---

## 9. Query Scopes for Reusable Filtering

**Decision**: Use Laravel query scopes (`scopeByStatus`, `scopeAssignedTo`) instead of inline filter logic in controllers.

**Why**:
- **Reusability**: Same filter logic used in multiple controllers
- **Testability**: Scopes can be unit-tested independently
- **Readability**: `Request::byStatus($status)` is clearer than where clauses

**Implementation**:
```php
class RepairRequest extends Model {
    public function scopeByStatus(Builder $query, RequestStatus $status): Builder {
        return $query->where('status', $status);
    }
}
```

---

## 10. Validation in Form Request Classes

**Decision**: Extract validation rules into dedicated `FormRequest` subclasses instead of inline in controllers.

**Why**:
- **DRY**: Validation logic not duplicated across multiple endpoints
- **Testing**: Form requests can be tested independently
- **Authorization**: Form requests can implement custom `authorize()` logic
- **Maintainability**: All validation in one place per endpoint

**Implementation**:
- `StoreRepairRequestRequest` — validates client_name, phone, address, problem_text
- `AssignMasterRequest` — validates master_id exists and has 'master' role

Phone validation regex:
```php
'phone' => 'required|string|regex:/^\+?([0-9]{1,3})?[\s.-]?[0-9]{6,14}$/|max:20'
```

---

## Summary

These decisions prioritize **simplicity**, **performance**, and **correctness** while demonstrating solid Laravel practices. The final application is easy to understand, test, deploy, and extend.
