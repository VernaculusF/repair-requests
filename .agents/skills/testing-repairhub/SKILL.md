---
name: testing-repairhub
description: End-to-end testing of the RepairHub repair request management app. Use when verifying UI changes, form submissions, role-based flows, or statistics.
---

# Testing RepairHub

## Prerequisites

- PHP 8.2+ installed (8.3 recommended)
- Composer dependencies installed (`composer install`)
- `.env` file configured (copy from `.env.example`, set `APP_KEY` via `php artisan key:generate`)
- SQLite database at `database/database.sqlite`

## Setup

```bash
cd /home/ubuntu/repair-requests
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0 --port=8000
```

The seed creates 7 requests across all statuses and 3 users.

## Test Credentials

| Role       | Email                    | Password |
|------------|--------------------------|----------|
| Dispatcher | dispatcher@example.com   | password |
| Master 1   | master1@example.com      | password |
| Master 2   | master2@example.com      | password |

## Key Routes

| Route              | Auth Required | Role       | Description                    |
|--------------------|---------------|------------|--------------------------------|
| `/`                | No            | —          | Landing page (unauth) or redirect (auth) |
| `/login`           | No            | —          | Login form with demo creds card |
| `/requests/create` | No            | —          | Public repair request form     |
| `/requests/success`| No            | —          | Success page after submission  |
| `/dispatcher`      | Yes           | dispatcher | Dashboard with stats + table + filter |
| `/master`          | Yes           | master     | Panel with stats + request cards |

## Expected Seed Data Stats

**Dispatcher dashboard (initial):**
- Total = 7, New = 2, Assigned = 1, In Progress = 2, Done = 1

**Master 1 panel (initial):**
- Waiting to Accept = 1, In Progress = 1, Completed = 0

## Test Flows

### 1. Landing Page
- Navigate to `/` while unauthenticated
- Verify: RepairHub navbar, 3 workflow cards, Submit/Login CTAs
- The page should NOT redirect (returns 200)

### 2. Login Flow
- Navigate to `/login`
- Verify demo credentials card is visible
- Login as dispatcher → should redirect to `/dispatcher`
- Navbar should show user avatar with initials and role

### 3. Dispatcher Dashboard
- Verify stats counters match seed data
- Verify table has all expected columns (ID, Client, Phone, Address, Problem, Status, Assigned To, Created, Actions)
- Test status filter dropdown: selecting "New" should show only 2 rows and display "Clear Filter" button
- Verify New rows have master dropdown + Assign button
- Verify Done/Canceled rows have no action buttons

### 4. Request Submission
- Navigate to `/requests/create`
- Fill all fields and submit
- Verify redirect to `/requests/success` with green checkmark and request ID
- Return to dispatcher dashboard → stats should reflect +1 total and +1 new
- New request should appear at top of table (latest() ordering)

### 5. Master Panel
- Logout, login as master1
- Verify stats match expected counts
- Verify card layout shows request details + correct action buttons
- Click "Accept Request" → stats should update, button should change to "Mark as Complete"
- Expand "Event History" → should show timeline with event type, timestamp, and user email

## Tips

- The app uses SQLite locally — `migrate:fresh --seed` resets everything quickly
- Status badges use colored dots + uppercase text in the new design
- Phone numbers render as clickable `tel:` links
- Filter uses query string `?status=new` — verify URL changes
- `latest()` ordering means newest requests appear first in both panels
- Event history uses `<details>` HTML element — click to expand
- The app uses CSRF protection — always test via browser UI, not curl

## Devin Secrets Needed

None — the app runs entirely locally with SQLite and seeded test data.
