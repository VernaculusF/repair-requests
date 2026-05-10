@extends('layouts.app')

@section('title', 'Login — RepairHub')

@section('content')
    <div style="max-width: 420px; margin: 2rem auto;">
        <div class="content-card">
            <div class="content-card__header" style="text-align: center;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--gray-900);">Welcome Back</h1>
                <p class="text-muted text-sm mt-1">Sign in to your account</p>
            </div>
            <div class="content-card__body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            required
                        >
                        @error('password')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary btn--lg" style="width: 100%;">
                        Sign In
                    </button>
                </form>
            </div>
        </div>

        <div class="content-card mt-4">
            <div class="content-card__body" style="padding: 1.25rem;">
                <p class="text-sm text-muted" style="text-align: center; margin-bottom: 0.75rem;">
                    <strong>Demo Credentials</strong> — password: <code class="font-mono" style="background: var(--gray-100); padding: 0.125rem 0.375rem; border-radius: 4px;">password</code>
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8125rem;">
                    <div style="background: var(--gray-50); padding: 0.5rem 0.75rem; border-radius: var(--radius); border: 1px solid var(--gray-200);">
                        <div style="font-weight: 600; color: var(--gray-700);">Dispatcher</div>
                        <div class="text-muted font-mono" style="font-size: 0.75rem;">dispatcher@example.com</div>
                    </div>
                    <div style="background: var(--gray-50); padding: 0.5rem 0.75rem; border-radius: var(--radius); border: 1px solid var(--gray-200);">
                        <div style="font-weight: 600; color: var(--gray-700);">Master</div>
                        <div class="text-muted font-mono" style="font-size: 0.75rem;">master1@example.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
