@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom: 1.5rem;">Dispatcher Panel</h1>

    <!-- Status Filter -->
    <div style="
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    ">
        <form method="GET" action="/dispatcher" style="display: flex; gap: 1rem; align-items: flex-end;">
            <div>
                <label for="status-filter" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Filter by Status:</label>
                <select
                    id="status-filter"
                    name="status"
                    onchange="this.form.submit()"
                    style="
                        padding: 0.5rem;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        cursor: pointer;
                    "
                >
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}" @if(request('status') === $s->value) selected @endif>
                            {{ $s->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    @if ($requests->count())
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #2c3e50; color: white;">
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">ID</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Client</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Phone</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Address</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Problem</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Status</th>
                        <th style="padding: 0.75rem; text-align: left; border: 1px solid #e0e0e0;">Assigned Master</th>
                        <th style="padding: 0.75rem; text-align: center; border: 1px solid #e0e0e0;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr style="background: {{ $loop->odd ? 'white' : '#f9f9f9' }}; border: 1px solid #e0e0e0;">
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                <strong>#{{ $request->id }}</strong>
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                {{ $request->client_name }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                {{ $request->phone }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                {{ Str::limit($request->address, 30) }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                {{ Str::limit($request->problem_text, 40) }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                <span style="
                                    display: inline-block;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 20px;
                                    font-weight: 600;
                                    background: @switch($request->status->value)
                                        @case('new') #d1ecf1 @break
                                        @case('assigned') #fff3cd @break
                                        @case('in_progress') #d4edff @break
                                        @case('done') #d4edda @break
                                        @case('canceled') #f8d7da @break
                                        @default #e0e0e0 @endswitch;
                                    color: @switch($request->status->value)
                                        @case('new') #0c5460 @break
                                        @case('assigned') #856404 @break
                                        @case('in_progress') #004085 @break
                                        @case('done') #155724 @break
                                        @case('canceled') #721c24 @break
                                        @default #666 @endswitch;
                                ">
                                    {{ $request->status->label() }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0;">
                                @if ($request->assignedMaster)
                                    <strong>{{ $request->assignedMaster->name }}</strong><br>
                                    <small style="color: #666;">{{ $request->assignedMaster->email }}</small>
                                @else
                                    <span style="color: #999;">Unassigned</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e0e0e0; text-align: center;">
                                {{-- Assign Form --}}
                                @if ($request->status === \App\Enums\RequestStatus::New)
                                    <form method="POST" action="/dispatcher/requests/{{ $request->id }}/assign" style="display: inline; margin-bottom: 0.5rem;">
                                        @csrf
                                        <select
                                            name="master_id"
                                            style="
                                                padding: 0.4rem;
                                                border: 1px solid #ccc;
                                                border-radius: 4px;
                                                font-size: 0.85rem;
                                            "
                                        >
                                            <option value="">Select master...</option>
                                            @foreach ($masters as $master)
                                                <option value="{{ $master->id }}">{{ $master->name }}</option>
                                            @endforeach
                                        </select>
                                        <button
                                            type="submit"
                                            style="
                                                padding: 0.4rem 0.75rem;
                                                background: #17a2b8;
                                                color: white;
                                                border: none;
                                                border-radius: 4px;
                                                cursor: pointer;
                                                font-size: 0.85rem;
                                                transition: background 0.3s;
                                            "
                                            onmouseover="this.style.background='#138496'"
                                            onmouseout="this.style.background='#17a2b8'"
                                        >
                                            Assign
                                        </button>
                                    </form>
                                @endif

                                {{-- Cancel Form --}}
                                @if ($request->status !== \App\Enums\RequestStatus::Done && $request->status !== \App\Enums\RequestStatus::Canceled)
                                    <form method="POST" action="/dispatcher/requests/{{ $request->id }}/cancel" style="display: inline;">
                                        @csrf
                                        <button
                                            type="submit"
                                            style="
                                                padding: 0.4rem 0.75rem;
                                                background: #dc3545;
                                                color: white;
                                                border: none;
                                                border-radius: 4px;
                                                cursor: pointer;
                                                font-size: 0.85rem;
                                                transition: background 0.3s;
                                            "
                                            onmouseover="this.style.background='#c82333'"
                                            onmouseout="this.style.background='#dc3545'"
                                            onclick="return confirm('Are you sure you want to cancel this request?');"
                                        >
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $requests->links() }}
        </div>
    @else
        <div style="
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            color: #666;
        ">
            <p>No repair requests found.</p>
        </div>
    @endif
@endsection
