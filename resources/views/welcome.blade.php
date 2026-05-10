@extends('layouts.app')

@section('title', 'RepairHub — Repair Request Management')

@section('content')
    <div style="max-width: 800px; margin: 2rem auto; text-align: center;">
        <div style="margin-bottom: 3rem;">
            <div style="
                width: 72px; height: 72px; border-radius: var(--radius-lg);
                background: var(--primary-light); color: var(--primary);
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 1.5rem;
            ">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--gray-900); letter-spacing: -0.025em; margin-bottom: 0.75rem;">
                Repair Request Management
            </h1>
            <p style="font-size: 1.125rem; color: var(--gray-500); max-width: 560px; margin: 0 auto;">
                Submit repair requests, track progress, and manage assignments — all in one place.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="content-card">
                <div class="content-card__body" style="padding: 1.75rem;">
                    <div style="
                        width: 48px; height: 48px; border-radius: var(--radius);
                        background: #dbeafe; color: #2563eb;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 1rem;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <h3 style="font-weight: 700; margin-bottom: 0.5rem; font-size: 1rem;">Submit Request</h3>
                    <p class="text-sm text-muted">Describe your problem and submit a repair request — no registration needed.</p>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card__body" style="padding: 1.75rem;">
                    <div style="
                        width: 48px; height: 48px; border-radius: var(--radius);
                        background: #fef3c7; color: #d97706;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 1rem;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    </div>
                    <h3 style="font-weight: 700; margin-bottom: 0.5rem; font-size: 1rem;">Dispatcher Assigns</h3>
                    <p class="text-sm text-muted">Dispatchers review incoming requests, assign them to available masters.</p>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card__body" style="padding: 1.75rem;">
                    <div style="
                        width: 48px; height: 48px; border-radius: var(--radius);
                        background: #d1fae5; color: #059669;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 1rem;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <h3 style="font-weight: 700; margin-bottom: 0.5rem; font-size: 1rem;">Work & Complete</h3>
                    <p class="text-sm text-muted">Masters accept requests, complete the work, and mark them as done.</p>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/requests/create" class="btn btn--primary btn--lg" style="padding: 0.875rem 2rem; font-size: 1.0625rem;">
                Submit a Request
            </a>
            <a href="{{ route('login') }}" class="btn btn--ghost btn--lg" style="padding: 0.875rem 2rem; font-size: 1.0625rem;">
                Staff Login
            </a>
        </div>
    </div>
@endsection
