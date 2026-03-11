@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="margin-bottom: 1.5rem;">Submit Repair Request</h1>

        <form method="POST" action="/requests">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label for="client_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Your Name *</label>
                <input
                    type="text"
                    id="client_name"
                    name="client_name"
                    value="{{ old('client_name') }}"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;"
                >
                @error('client_name')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="phone" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Phone Number *</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="+1-234-567-8900"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;"
                >
                @error('phone')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="address" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Address *</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    value="{{ old('address') }}"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;"
                >
                @error('address')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="problem_text" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Problem Description *</label>
                <textarea
                    id="problem_text"
                    name="problem_text"
                    rows="6"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; font-family: inherit;"
                >{{ old('problem_text') }}</textarea>
                @error('problem_text')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <button
                type="submit"
                style="padding: 0.75rem 2rem; background: #27ae60; color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s;"
                onmouseover="this.style.background='#229954'"
                onmouseout="this.style.background='#27ae60'"
            >
                Submit Request
            </button>

            <a href="/" style="display: inline-block; margin-left: 1rem; color: #2c3e50; text-decoration: none;">
                Cancel
            </a>
        </form>
    </div>
@endsection
