@extends('layouts.app')

@section('title', 'My Requests — RepairHub')

@section('content')
    <div class="page-header">
        <div>
            <h1>My Repair Requests</h1>
            <p>View and manage requests assigned to you.</p>
        </div>
    </div>

    {{-- Statistics --}}
    @if ($requests->count())
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card">
                <div class="stat-card__value" style="color: var(--warning);">{{ $stats['assigned'] }}</div>
                <div class="stat-card__label">Waiting to Accept</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value" style="color: var(--primary);">{{ $stats['in_progress'] }}</div>
                <div class="stat-card__label">In Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value" style="color: var(--success);">{{ $stats['done'] }}</div>
                <div class="stat-card__label">Completed</div>
            </div>
        </div>
    @endif

    @if ($requests->count())
        @foreach ($requests as $request)
            <div class="request-card">
                <div class="request-card__body">
                    {{-- Left Column: Info --}}
                    <div>
                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Request ID</div>
                            <div class="request-card__info-value" style="font-size: 1.25rem; color: var(--primary);">
                                #{{ $request->id }}
                            </div>
                        </div>

                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Client</div>
                            <div class="request-card__info-value">{{ $request->client_name }}</div>
                        </div>

                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Phone</div>
                            <div class="request-card__info-value">
                                <a href="tel:{{ $request->phone }}">{{ $request->phone }}</a>
                            </div>
                        </div>

                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Address</div>
                            <div class="request-card__info-value">{{ $request->address }}</div>
                        </div>

                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Status</div>
                            <span class="badge badge--{{ $request->status->value }}">
                                {{ $request->status->label() }}
                            </span>
                        </div>

                        <div class="request-card__info-item">
                            <div class="request-card__info-label">Created</div>
                            <div class="request-card__info-value text-sm">{{ $request->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    {{-- Right Column: Problem & Actions --}}
                    <div>
                        <div class="request-card__info-label mb-2">Problem Description</div>
                        <div class="request-card__problem">
                            {{ $request->problem_text }}
                        </div>

                        <div class="request-card__actions">
                            @if ($request->status === \App\Enums\RequestStatus::Assigned)
                                <form method="POST" action="{{ route('master.take', $request) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn--accent btn--lg">
                                        Accept Request
                                    </button>
                                </form>
                            @endif

                            @if ($request->status === \App\Enums\RequestStatus::InProgress)
                                <form method="POST" action="{{ route('master.complete', $request) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn--success btn--lg">
                                        Mark as Complete
                                    </button>
                                </form>
                            @endif

                            @if ($request->status === \App\Enums\RequestStatus::Done)
                                <div class="request-card__completed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Completed
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Event History --}}
                @if ($request->events->count())
                    <div class="event-history">
                        <details>
                            <summary>Event History ({{ $request->events->count() }})</summary>
                            <div class="event-timeline">
                                @foreach ($request->events as $event)
                                    <div class="event-timeline__item">
                                        <div class="event-timeline__action">{{ ucfirst($event->action) }}</div>
                                        <div class="event-timeline__meta">
                                            {{ $event->created_at->format('d M Y, H:i:s') }}
                                            &middot;
                                            {{ $event->user ? $event->user->email : 'System' }}
                                        </div>
                                        @if ($event->comment)
                                            <div class="event-timeline__comment">{{ $event->comment }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $requests->links() }}
        </div>
    @else
        <div class="content-card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <p><strong>No assigned requests yet.</strong></p>
                <p class="text-sm text-muted">When a dispatcher assigns a request to you, it will appear here.</p>
            </div>
        </div>
    @endif
@endsection
