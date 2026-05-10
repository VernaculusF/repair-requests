<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repair Requests')</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --primary-50: #e0e7ff;
            --secondary: #0f172a;
            --secondary-light: #1e293b;
            --accent: #06b6d4;
            --accent-hover: #0891b2;
            --success: #10b981;
            --success-hover: #059669;
            --success-light: #ecfdf5;
            --success-border: #a7f3d0;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --warning-border: #fde68a;
            --info: #3b82f6;
            --info-light: #eff6ff;
            --info-border: #bfdbfe;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --radius: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
            --transition: 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── Navigation ── */
        .navbar {
            background: var(--secondary);
            color: white;
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-md);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            letter-spacing: -0.025em;
        }

        .navbar-brand svg {
            width: 28px;
            height: 28px;
            color: var(--accent);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.375rem 0.75rem;
            background: rgba(255,255,255,0.08);
            border-radius: var(--radius);
            font-size: 0.875rem;
        }

        .navbar-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .navbar-user-role {
            font-size: 0.75rem;
            color: var(--gray-400);
            text-transform: capitalize;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all var(--transition);
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .nav-link--active {
            color: white;
            background: rgba(255,255,255,0.15);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
            line-height: 1.5;
        }

        .btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .btn--primary {
            background: var(--primary);
            color: white;
        }
        .btn--primary:hover { background: var(--primary-hover); }

        .btn--accent {
            background: var(--accent);
            color: white;
        }
        .btn--accent:hover { background: var(--accent-hover); }

        .btn--success {
            background: var(--success);
            color: white;
        }
        .btn--success:hover { background: var(--success-hover); }

        .btn--danger {
            background: var(--danger);
            color: white;
        }
        .btn--danger:hover { background: var(--danger-hover); }

        .btn--ghost {
            background: transparent;
            color: var(--gray-600);
            border: 1px solid var(--gray-300);
        }
        .btn--ghost:hover {
            background: var(--gray-100);
            border-color: var(--gray-400);
        }

        .btn--logout {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.15);
            font-size: 0.8125rem;
        }
        .btn--logout:hover {
            background: rgba(239,68,68,0.2);
            color: white;
            border-color: rgba(239,68,68,0.4);
        }

        .btn--sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn--lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        /* ── Container & Content ── */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .content-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .content-card__header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .content-card__body {
            padding: 2rem;
        }

        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .page-header p {
            color: var(--gray-500);
            font-size: 0.9375rem;
            margin-top: 0.25rem;
        }

        /* ── Alerts ── */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border: 1px solid transparent;
        }

        .alert svg {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            margin-top: 1px;
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border-color: var(--success-border);
        }

        .alert-error {
            background: var(--danger-light);
            color: #991b1b;
            border-color: var(--danger-border);
        }

        .alert-error ul {
            margin: 0.5rem 0 0 1.25rem;
            padding: 0;
        }

        /* ── Status Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge--new { background: #dbeafe; color: #1e40af; }
        .badge--new::before { background: #3b82f6; }

        .badge--assigned { background: #fef3c7; color: #92400e; }
        .badge--assigned::before { background: #f59e0b; }

        .badge--in_progress { background: #e0e7ff; color: #3730a3; }
        .badge--in_progress::before { background: #6366f1; }

        .badge--done { background: #d1fae5; color: #065f46; }
        .badge--done::before { background: #10b981; }

        .badge--canceled { background: #fee2e2; color: #991b1b; }
        .badge--canceled::before { background: #ef4444; }

        /* ── Stats Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1.25rem;
            text-align: center;
            transition: all var(--transition);
        }

        .stat-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .stat-card__value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
        }

        .stat-card__label {
            font-size: 0.8125rem;
            color: var(--gray-500);
            margin-top: 0.375rem;
            font-weight: 500;
        }

        /* ── Tables ── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .table thead {
            background: var(--gray-50);
        }

        .table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-600);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }

        .table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background var(--transition);
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-id {
            font-weight: 700;
            color: var(--primary);
        }

        .table-text-muted {
            color: var(--gray-500);
            font-size: 0.8125rem;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* ── Forms ── */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-700);
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--gray-900);
            background: white;
            transition: all var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .form-input::placeholder, .form-textarea::placeholder {
            color: var(--gray-400);
        }

        .form-error {
            color: var(--danger);
            font-size: 0.8125rem;
            margin-top: 0.375rem;
            display: block;
        }

        .form-select--inline {
            width: auto;
            padding: 0.375rem 0.625rem;
            font-size: 0.8125rem;
        }

        .form-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
        }

        .form-checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-bar__label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            white-space: nowrap;
        }

        /* ── Cards (Master panel) ── */
        .request-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition);
            margin-bottom: 1rem;
        }

        .request-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--gray-300);
        }

        .request-card__body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .request-card__info-item {
            margin-bottom: 1rem;
        }

        .request-card__info-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .request-card__info-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .request-card__info-value a {
            color: var(--primary);
            text-decoration: none;
        }
        .request-card__info-value a:hover {
            text-decoration: underline;
        }

        .request-card__problem {
            background: var(--gray-50);
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            border-left: 3px solid var(--primary);
            line-height: 1.7;
            color: var(--gray-700);
        }

        .request-card__actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }

        .request-card__completed {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--success-light);
            color: #065f46;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* ── Event History ── */
        .event-history {
            margin-top: 0;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
        }

        .event-history summary {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-600);
            cursor: pointer;
            user-select: none;
        }

        .event-history summary:hover {
            color: var(--primary);
        }

        .event-timeline {
            margin-top: 1rem;
            padding-left: 1rem;
            border-left: 2px solid var(--gray-200);
        }

        .event-timeline__item {
            position: relative;
            padding: 0.5rem 0 0.5rem 1rem;
        }

        .event-timeline__item::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 0.75rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gray-300);
            border: 2px solid white;
        }

        .event-timeline__action {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-800);
        }

        .event-timeline__meta {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.125rem;
        }

        .event-timeline__comment {
            font-size: 0.8125rem;
            color: var(--gray-600);
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        /* ── Pagination ── */
        .pagination-wrapper {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
            gap: 0.25rem;
        }

        .pagination-wrapper nav a,
        .pagination-wrapper nav span {
            padding: 0.5rem 0.875rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all var(--transition);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 1rem;
                height: auto;
                min-height: 56px;
                flex-wrap: wrap;
                gap: 0.5rem;
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .navbar-user {
                display: none;
            }

            .container {
                padding: 1rem 0.75rem;
            }

            .content-card__body {
                padding: 1rem;
            }

            .request-card__body {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-header h1 {
                font-size: 1.375rem;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .table-wrapper {
                font-size: 0.8125rem;
            }
        }

        /* ── Utilities ── */
        .text-muted { color: var(--gray-500); }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .font-mono { font-family: 'SFMono-Regular', Consolas, monospace; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .inline { display: inline; }
        .inline-form { display: inline; margin: 0; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .justify-center { justify-content: center; }
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
            RepairHub
        </a>

        @auth
            <div class="navbar-right">
                <div class="navbar-user">
                    <div class="navbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 500;">{{ auth()->user()->name }}</div>
                        <div class="navbar-user-role">{{ auth()->user()->role }}</div>
                    </div>
                </div>
                <div class="navbar-links">
                    @if (auth()->user()->isDispatcher())
                        <a href="{{ route('dispatcher.index') }}" class="nav-link {{ request()->is('dispatcher*') ? 'nav-link--active' : '' }}">
                            Dashboard
                        </a>
                    @elseif (auth()->user()->isMaster())
                        <a href="{{ route('master.index') }}" class="nav-link {{ request()->is('master*') ? 'nav-link--active' : '' }}">
                            My Requests
                        </a>
                    @endif
                    <a href="/requests/create" class="nav-link">
                        + New Request
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn--logout btn--sm">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="navbar-links">
                <a href="/requests/create" class="nav-link">Submit Request</a>
                <a href="{{ route('login') }}" class="btn btn--primary btn--sm">Login</a>
            </div>
        @endauth
    </nav>

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
