@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom: 1.5rem;">My Repair Requests</h1>

    @if ($requests->count())
        <div style="space-y: 1.5rem;">
            @foreach ($requests as $request)
                <div style="
                    background: white;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 1.5rem;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                ">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                        <!-- Left Column: Info -->
                        <div>
                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Request ID</small><br>
                                <strong style="font-size: 1.2rem;">#{{ $request->id }}</strong>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Client</small><br>
                                <strong>{{ $request->client_name }}</strong>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Phone</small><br>
                                <a href="tel:{{ $request->phone }}" style="color: #2c3e50; text-decoration: none;">
                                    {{ $request->phone }}
                                </a>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Address</small><br>
                                <strong>{{ $request->address }}</strong>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Status</small><br>
                                <span style="
                                    display: inline-block;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 20px;
                                    font-weight: 600;
                                    background: @switch($request->status->value)
                                        @case('assigned') #fff3cd @break
                                        @case('in_progress') #d4edff @break
                                        @case('done') #d4edda @break
                                        @default #e0e0e0 @endswitch;
                                    color: @switch($request->status->value)
                                        @case('assigned') #856404 @break
                                        @case('in_progress') #004085 @break
                                        @case('done') #155724 @break
                                        @default #666 @endswitch;
                                ">
                                    {{ $request->status->label() }}
                                </span>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <small style="color: #999;">Created</small><br>
                                <strong>{{ $request->created_at->format('Y-m-d H:i') }}</strong>
                            </div>
                        </div>

                        <!-- Right Column: Problem & Actions -->
                        <div>
                            <div style="margin-bottom: 1.5rem;">
                                <small style="color: #999; display: block; margin-bottom: 0.5rem;">Problem Description</small>
                                <p style="
                                    background: #f8f9fa;
                                    padding: 1rem;
                                    border-radius: 4px;
                                    border-left: 4px solid #2c3e50;
                                    margin: 0;
                                    line-height: 1.6;
                                ">
                                    {{ $request->problem_text }}
                                </p>
                            </div>

                            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                {{-- Take Button --}}
                                @if ($request->status === \App\Enums\RequestStatus::Assigned)
                                    <form method="POST" action="/master/requests/{{ $request->id }}/take" style="display: inline;">
                                        @csrf
                                        <button
                                            type="submit"
                                            style="
                                                padding: 0.75rem 1.5rem;
                                                background: #17a2b8;
                                                color: white;
                                                border: none;
                                                border-radius: 4px;
                                                cursor: pointer;
                                                font-weight: 600;
                                                transition: background 0.3s;
                                            "
                                            onmouseover="this.style.background='#138496'"
                                            onmouseout="this.style.background='#17a2b8'"
                                        >
                                            Accept Request
                                        </button>
                                    </form>
                                @endif

                                {{-- Complete Button --}}
                                @if ($request->status === \App\Enums\RequestStatus::InProgress)
                                    <form method="POST" action="/master/requests/{{ $request->id }}/complete" style="display: inline;">
                                        @csrf
                                        <button
                                            type="submit"
                                            style="
                                                padding: 0.75rem 1.5rem;
                                                background: #28a745;
                                                color: white;
                                                border: none;
                                                border-radius: 4px;
                                                cursor: pointer;
                                                font-weight: 600;
                                                transition: background 0.3s;
                                            "
                                            onmouseover="this.style.background='#218838'"
                                            onmouseout="this.style.background='#28a745'"
                                        >
                                            Mark as Complete
                                        </button>
                                    </form>
                                @endif

                                {{-- Status Badge --}}
                                @if ($request->status === \App\Enums\RequestStatus::Done)
                                    <div style="
                                        padding: 0.75rem 1.5rem;
                                        background: #d4edda;
                                        color: #155724;
                                        border-radius: 4px;
                                        font-weight: 600;
                                    ">
                                        ✓ Completed
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Event History (if available) --}}
                    @if ($request->events->count())
                        <div style="
                            margin-top: 1.5rem;
                            padding-top: 1.5rem;
                            border-top: 1px solid #e0e0e0;
                        ">
                            <details style="cursor: pointer;">
                                <summary style="font-weight: 600; color: #2c3e50;">
                                    Event History ({{ $request->events->count() }})
                                </summary>
                                <div style="margin-top: 1rem; background: #f8f9fa; padding: 1rem; border-radius: 4px;">
                                    @foreach ($request->events as $event)
                                        <div style="padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                                            <strong>{{ $event->action }}</strong><br>
                                            <small style="color: #666;">
                                                {{ $event->created_at->format('Y-m-d H:i:s') }}
                                                @if ($event->user)
                                                    by {{ $event->user->email }}
                                                @else
                                                    by System
                                                @endif
                                            </small>
                                            @if ($event->comment)
                                                <p style="margin-top: 0.25rem; color: #666;">{{ $event->comment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @endif
                </div>
            @endforeach
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
            <p>You have no assigned repair requests at the moment.</p>
        </div>
    @endif
@endsection
