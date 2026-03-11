# Architectural Decisions

This document lists the key architectural decisions for the Repair Requests application.

## 1. Database Strategy
**Decision**: SQLite for local/testing, MySQL for Docker runtime.
**Why**: Fast local start, portable tests, production-like container setup.

## 2. Request Status as Enum
**Decision**: Use PHP backed enum for request status values.
**Why**: Type safety, fewer magic strings, cleaner status transitions.

## 3. Race Condition Protection via Atomic UPDATE
**Decision**: Protect take action with single conditional UPDATE.
**Why**: Correct concurrent behavior with minimal complexity; one success, one refusal.

## 4. Service Layer for Business Rules
**Decision**: Keep workflow logic in RepairRequestService.
**Why**: Thin controllers, reusable domain logic, easier automated testing.

## 5. Role Middleware for Authorization
**Decision**: Use custom EnsureRole middleware for dispatcher/master access.
**Why**: Simple and explicit for two-role system without policy overhead.

## 6. Audit Log in Separate request_events Table
**Decision**: Store actions as immutable event rows linked to requests.
**Why**: Clear history, easy filtering, straightforward UI display.

## 7. Eager Loading on Listing Screens
**Decision**: Load related master/events with with() on list pages.
**Why**: Prevent N+1 queries and keep dispatcher/master panels performant.
