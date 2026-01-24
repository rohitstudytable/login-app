<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> {{ $intern->name }} Attendance</title>
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

/* ============ SIDEBAR ============ */
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

/* ============ MAIN ============ */
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* ============ TOPBAR ============ */
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

.logout-btn:hover {
    background: #b91c1c;
}

/* ============ CONTENT ============ */
.content {
    padding: 25px;
}

/* ============ CARD ============ */
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}

/* ============ TABLE ============ */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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

/* ============ BADGES ============ */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.present { background: #dcfce7; color: #166534; }
.absent { background: #fee2e2; color: #991b1b; }
.half_day { background: #fef3c7; color: #92400e; }

/* ============ RESPONSIVE ============ */
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

    table {
        font-size: 14px;
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
        <h2>{{ $intern->name }} Attendance</h2>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



        <div class="card">
            <h3>Attendance History</h3>

            <table>
                <tr>
                    <th>Sl no</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                @php
                    $i=1;
                @endphp

                @forelse($intern->attendances as $attendance)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>
                        <span class="badge {{ $attendance->status }}">
                            {{ ucfirst(str_replace('_',' ', $attendance->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">No attendance records found.</td>
                </tr>
                @endforelse
            </table>
        </div>

    </div>
</div>




</body>
</html>
