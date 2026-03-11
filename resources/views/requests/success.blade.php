@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 2rem auto; text-align: center;">
        <div style="
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        ">
            <h1 style="color: #155724; margin-bottom: 1rem;">✓ Request Submitted Successfully</h1>
            <p style="color: #155724; font-size: 1.1rem; margin-bottom: 0;">
                Thank you for submitting your repair request!
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <p style="margin-bottom: 1rem;">
                <strong>Request ID:</strong><br>
                <span style="
                    display: inline-block;
                    background: white;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    font-family: monospace;
                    font-size: 1.2rem;
                    margin-top: 0.5rem;
                ">{{ session('request_id') ?? 'N/A' }}</span>
            </p>
            <p style="color: #666; font-size: 0.9rem;">
                Keep this ID for reference. A dispatcher will review your request shortly and assign a master to handle it.
            </p>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="/requests/create" style="
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background: #2c3e50;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                transition: background 0.3s;
            " onmouseover="this.style.background='#34495e'" onmouseout="this.style.background='#2c3e50'">
                Submit Another Request
            </a>
            <a href="/" style="
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background: #95a5a6;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                transition: background 0.3s;
            " onmouseover="this.style.background='#7f8c8d'" onmouseout="this.style.background='#95a5a6'">
                Go Home
            </a>
        </div>
    </div>
@endsection
