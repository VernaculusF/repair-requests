@extends('layouts.app')

@section('title', 'Dispatcher Dashboard — RepairHub')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dispatcher Dashboard</h1>
            <p>Manage and assign repair requests to masters.</p>
        </div>
        <a href="/requests/create" class="btn btn--primary">+ New Request</a>
    </div>

    {{-- Statistics --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__value">{{ $stats['total'] }}</div>
            <div class="stat-card__label">Total Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value" style="color: var(--info);">{{ $stats['new'] }}</div>
            <div class="stat-card__label">New</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value" style="color: var(--warning);">{{ $stats['assigned'] }}</div>
            <div class="stat-card__label">Assigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value" style="color: var(--primary);">{{ $stats['in_progress'] }}</div>
            <div class="stat-card__label">In Progress</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value" style="color: var(--success);">{{ $stats['done'] }}</div>
            <div class="stat-card__label">Done</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <span class="filter-bar__label">Filter by status:</span>
        <form method="GET" action="{{ route('dispatcher.index') }}" style="display: flex; gap: 0.5rem; align-items: center;">
            <select
                name="status"
                class="form-select form-select--inline"
                onchange="this.form.submit()"
            >
                <option value="">All Statuses</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}" @if(request('status') === $s->value) selected @endif>
                        {{ $s->label() }}
                    </option>
                @endforeach
            </select>
        </form>
        @if(request('status'))
            <a href="{{ route('dispatcher.index') }}" class="btn btn--ghost btn--sm">Clear Filter</a>
        @endif
    </div>

    {{-- Table --}}
    @if ($requests->count())
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Problem</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>
                                <span class="table-id">#{{ $request->id }}</span>
                            </td>
                            <td style="font-weight: 600;">{{ $request->client_name }}</td>
                            <td>
                                <a href="tel:{{ $request->phone }}" style="color: var(--primary); text-decoration: none;">
                                    {{ $request->phone }}
                                </a>
                            </td>
                            <td>
                                <span class="truncate" title="{{ $request->address }}">{{ Str::limit($request->address, 30) }}</span>
                            </td>
                            <td>
                                <span class="truncate" title="{{ $request->problem_text }}">{{ Str::limit($request->problem_text, 35) }}</span>
                            </td>
                            <td>
                                <span class="badge badge--{{ $request->status->value }}">
                                    {{ $request->status->label() }}
                                </span>
                            </td>
                            <td>
                                @if ($request->assignedMaster)
                                    <div style="font-weight: 600; font-size: 0.875rem;">{{ $request->assignedMaster->name }}</div>
                                    <div class="table-text-muted">{{ $request->assignedMaster->email }}</div>
                                @else
                                    <span class="text-muted text-sm">—</span>
                                @endif
                            </td>
                            <td class="table-text-muted" style="white-space: nowrap;">
                                {{ $request->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div class="table-actions" style="justify-content: center;">
                                    @if ($request->status === \App\Enums\RequestStatus::New)
                                        <form method="POST" action="{{ route('dispatcher.assign', $request) }}" class="inline-form" style="display: flex; gap: 0.375rem; align-items: center;">
                                            @csrf
                                            <select name="master_id" class="form-select form-select--inline" required>
                                                <option value="">Master...</option>
                                                @foreach ($masters as $master)
                                                    <option value="{{ $master->id }}">{{ $master->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn--accent btn--sm">Assign</button>
                                        </form>
                                    @endif

                                    @if ($request->status !== \App\Enums\RequestStatus::Done && $request->status !== \App\Enums\RequestStatus::Canceled)
                                        <form method="POST" action="{{ route('dispatcher.cancel', $request) }}" class="inline-form">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn--danger btn--sm"
                                                onclick="return confirm('Are you sure you want to cancel request #{{ $request->id }}?');"
                                            >Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $requests->withQueryString()->links() }}
        </div>
    @else
        <div class="content-card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                <p><strong>No repair requests found.</strong></p>
                <p class="text-sm text-muted">Try changing the status filter or create a new request.</p>
            </div>
        </div>
    @endif
@endsection
