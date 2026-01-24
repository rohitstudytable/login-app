<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #1d4ed8;
            padding: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 12px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background: #2563eb;
        }

        /* Main content */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Top header */
        .topbar {
            background: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: bold;
            color: #111827;
        }

        /* Buttons */
        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        /* Page container */
        .container {
            padding: 24px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('interns.index') }}">Interns</a>
        <a href="{{ route('attendance.index') }}">Attendance</a>
    </div>

    <!-- Main Content -->
    <div class="main">

        <!-- Top Header -->
        <div class="topbar">
            <h3>Dashboard</h3>

            <div class="profile">
                <img src="{{ asset('assets/images/users/default.png') }}" alt="Admin Profile">
                <div class="profile-name">{{ Auth::user()?->name ?? 'Admin' }}</div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container">
            @yield('content')
        </div>

    </div>

</body>
</html>
