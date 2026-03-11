<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Requests</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        nav {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        nav .user-info span {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        nav .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: rgba(255,255,255,0.1);
        }

        nav .logout-form {
            margin: 0;
        }

        nav button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        nav button:hover {
            background: #c0392b;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .flash-messages {
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
            }

            nav .user-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            nav .nav-links {
                flex-direction: column;
                width: 100%;
            }

            nav a {
                display: block;
                width: 100%;
            }

            .container {
                padding: 0 0.5rem;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="brand">Repair Requests</div>
        @auth
            <div class="user-info">
                <span>{{ auth()->user()->email }} ({{ auth()->user()->role }})</span>
                <div class="nav-links">
                    @if (auth()->user()->isDispatcher())
                        <a href="/dispatcher">Dispatcher Panel</a>
                    @elseif (auth()->user()->isMaster())
                        <a href="/master">My Requests</a>
                    @endif
                    <form method="POST" action="/logout" class="logout-form" style="display: inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="nav-links">
                <a href="/requests/create">Submit Request</a>
                <a href="/login">Login</a>
            </div>
        @endauth
    </nav>

    <div class="container">
        <div class="flash-messages">
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>
