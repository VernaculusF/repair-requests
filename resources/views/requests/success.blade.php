@extends('layouts.app')

@section('title', 'Request Submitted — RepairHub')

@section('content')
    <div style="max-width: 560px; margin: 2rem auto; text-align: center;">
        <div class="content-card">
            <div class="content-card__body" style="padding: 3rem 2rem;">
                <div style="
                    width: 64px; height: 64px; border-radius: 50%;
                    background: var(--success-light); color: var(--success);
                    display: flex; align-items: center; justify-content: center;
                    margin: 0 auto 1.5rem;
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>

                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 0.5rem;">
                    Request Submitted!
                </h1>
                <p class="text-muted" style="margin-bottom: 2rem;">
                    Thank you! Your repair request has been received and will be reviewed by our team shortly.
                </p>

                <div style="
                    background: var(--gray-50);
                    border: 1px solid var(--gray-200);
                    border-radius: var(--radius-lg);
                    padding: 1.25rem;
                    margin-bottom: 2rem;
                ">
                    <div class="text-xs text-muted" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">
                        Request ID
                    </div>
                    <div class="font-mono" style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                        #{{ session('request_id') ?? 'N/A' }}
                    </div>
                    <p class="text-xs text-muted mt-2">
                        Save this ID for tracking your request.
                    </p>
                </div>

                <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                    <a href="/requests/create" class="btn btn--primary btn--lg">
                        Submit Another Request
                    </a>
                    <a href="/" class="btn btn--ghost btn--lg">
                        Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
