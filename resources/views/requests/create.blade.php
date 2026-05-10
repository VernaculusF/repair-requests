@extends('layouts.app')

@section('title', 'Submit Repair Request — RepairHub')

@section('content')
    <div style="max-width: 640px; margin: 0 auto;">
        <div class="page-header" style="justify-content: center; text-align: center; flex-direction: column;">
            <div>
                <h1>Submit Repair Request</h1>
                <p>Fill out the form below and we'll get back to you shortly.</p>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card__body">
                <form method="POST" action="/requests">
                    @csrf

                    <div class="form-group">
                        <label for="client_name" class="form-label">Your Name <span class="required">*</span></label>
                        <input
                            type="text"
                            id="client_name"
                            name="client_name"
                            class="form-input"
                            value="{{ old('client_name') }}"
                            placeholder="Ivan Petrov"
                            required
                        >
                        @error('client_name')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-input"
                            value="{{ old('phone') }}"
                            placeholder="+7-999-123-4567"
                            required
                        >
                        @error('phone')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Address <span class="required">*</span></label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            class="form-input"
                            value="{{ old('address') }}"
                            placeholder="123 Main Street, Apt 5"
                            required
                        >
                        @error('address')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="problem_text" class="form-label">Problem Description <span class="required">*</span></label>
                        <textarea
                            id="problem_text"
                            name="problem_text"
                            rows="5"
                            class="form-textarea"
                            placeholder="Describe the issue you're experiencing..."
                            required
                        >{{ old('problem_text') }}</textarea>
                        @error('problem_text')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <button type="submit" class="btn btn--success btn--lg">
                            Submit Request
                        </button>
                        <a href="/" class="btn btn--ghost btn--lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
