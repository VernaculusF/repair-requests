@extends('layouts.app')

@section('content')
    <div style="max-width: 400px; margin: 2rem auto;">
        <h1 style="margin-bottom: 1.5rem; text-align: center;">Login</h1>

        <form method="POST" action="/login">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;"
                >
                @error('email')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;"
                >
                @error('password')
                    <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label>
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button
                type="submit"
                style="width: 100%; padding: 0.75rem; background: #2c3e50; color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s;"
                onmouseover="this.style.background='#34495e'"
                onmouseout="this.style.background='#2c3e50'"
            >
                Login
            </button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: #666;">
            Test credentials:<br>
            <strong>dispatcher@example.com</strong> (dispatcher)<br>
            <strong>master1@example.com</strong> (master)<br>
            <strong>master2@example.com</strong> (master)<br>
            Password: <strong>password</strong>
        </p>
    </div>
@endsection
