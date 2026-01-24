<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> Interns</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f9;
        min-height: 100vh;
        display: flex;
    }

    /* ================= SIDEBAR ================= */
    .sidebar {
        width: 240px;
        background: linear-gradient(180deg, #1d4ed8, #1e40af);
        color: #fff;
        padding: 20px;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .sidebar a {
        display: block;
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.15);
    }

    /* ================= MAIN ================= */
    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* ================= TOPBAR ================= */
    .topbar {
        background: white;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .logout-btn {
        background: #dc2626;
        border: none;
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        cursor: pointer;
    }

    /* ================= CONTENT ================= */
    .content {
        padding: 25px;
    }

    /* ================= BUTTONS ================= */
    .btn {
        padding: 8px 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        color: white;
        font-size: 14px;
    }

    .btn-primary { background: #2563eb; }
    .btn-danger { background: #dc2626; }

    .btn-primary:hover { background: #1d4ed8; }
    .btn-danger:hover { background: #b91c1c; }

    /* ================= TABLE ================= */
    .table-container {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    th {
        background: #f1f5f9;
    }

    tr:hover {
        background: #f9fafb;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        body {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            display: flex;
            justify-content: space-around;
        }

        .sidebar h2 {
            display: none;
        }
    }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('interns.index') }}">Interns</a>
    <a href="{{ route('attendance.index') }}">Attendance</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Interns</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <a href="{{ route('interns.create') }}" class="btn btn-primary">+ Add Intern</a>

        <br><br>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($interns as $intern)
                    <tr>
                        <td>{{ $intern->name }}</td>
                        <td>{{ $intern->email }}</td>
                        <td>
                            <a href="{{ route('attendance.show', $intern) }}" class="btn btn-primary">
                                Attendance
                            </a>

                            <form action="{{ route('interns.destroy', $intern) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
