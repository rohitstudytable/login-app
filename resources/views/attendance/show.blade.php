<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $intern->name }} Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
* {margin:0; padding:0; box-sizing:border-box;}
body {font-family:'Segoe UI',sans-serif; background:#f4f6f9; min-height:100vh; display:flex;}

.sidebar {
    width:240px;
    background:linear-gradient(180deg,#1d4ed8,#1e40af);
    color:#fff;
    padding:20px;
}
.sidebar h2 {text-align:center; margin-bottom:30px;}
.sidebar a {
    display:block;
    padding:12px 15px;
    margin-bottom:10px;
    border-radius:8px;
    text-decoration:none;
    color:white;
}
.sidebar a:hover {background:rgba(255,255,255,.15);}

.main {flex:1; display:flex; flex-direction:column;}

.topbar {
    background:white;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
}

.logout-btn {
    background:#dc2626;
    border:none;
    padding:8px 14px;
    color:white;
    border-radius:6px;
    cursor:pointer;
}
.logout-btn:hover {background:#b91c1c;}

.content {padding:25px;}

.card {
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 18px rgba(0,0,0,.08);
    margin-bottom:25px;
}

.stats {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
    margin-bottom:20px;
}

.stat-box {
    padding:18px;
    border-radius:10px;
    text-align:center;
    font-weight:600;
}
.total {background:#e0e7ff; color:#1e40af;}
.present {background:#dcfce7; color:#166534;}
.absent {background:#fee2e2; color:#991b1b;}
.half_day {background:#fef3c7; color:#92400e;}

.filter-form {
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

input, select {
    padding:7px 10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.btn {
    padding:7px 14px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.btn-primary {background:#2563eb; color:white;}
.btn-primary:hover {background:#1d4ed8;}
.btn-reset {background:#6b7280; color:white;}
.btn-reset:hover {background:#4b5563;}

table {
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th, td {
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}
th {background:#f1f5f9;}
tr:hover {background:#f9fafb;}

.badge {
    padding:4px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}
.present {background:#dcfce7; color:#166534;}
.absent {background:#fee2e2; color:#991b1b;}
.half_day {background:#fef3c7; color:#92400e;}
</style>
</head>

<body>

@include('layouts.sidebar')

<div class="main">

    <div class="topbar">
        <h2>{{ $intern->name }} Attendance</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">

        <!-- STATS -->
        <div class="stats">
            <div class="stat-box total">Total Days<br>{{ $totalDays }}</div>
            <div class="stat-box present">Present<br>{{ $presentCount }}</div>
            <div class="stat-box absent">Absent<br>{{ $absentCount }}</div>
            <div class="stat-box half_day">Half Day<br>{{ $halfDayCount }}</div>
        </div>

        <div class="card">
            <h3>Attendance History</h3>

            <!-- FILTER -->
            <form method="GET" class="filter-form">
                <input type="date" name="start_date" value="{{ request('start_date') }}">
                <input type="date" name="end_date" value="{{ request('end_date') }}">

                <select name="status">
                    <option value="">All Status</option>
                    <option value="present" {{ request('status')=='present'?'selected':'' }}>Present</option>
                    <option value="absent" {{ request('status')=='absent'?'selected':'' }}>Absent</option>
                    <option value="half_day" {{ request('status')=='half_day'?'selected':'' }}>Half Day</option>
                </select>

                <button class="btn btn-primary">Search</button>
                <a href="{{ route('attendance.show',$intern->id) }}" class="btn btn-reset">Reset</a>
            </form>

            <!-- TABLE -->
            <table>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                @forelse($attendances as $i => $attendance)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge {{ $attendance->status }}">
                            {{ ucfirst(str_replace('_',' ',$attendance->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">No attendance records found</td>
                </tr>
                @endforelse
            </table>
        </div>

    </div>
</div>

</body>
</html>
