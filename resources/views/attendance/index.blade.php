<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Intern Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'Segoe UI',sans-serif;background:#f4f6f9;min-height:100vh;display:flex}

    /* SIDEBAR */
    .sidebar{width:240px;background:linear-gradient(180deg,#1d4ed8,#1e40af);color:#fff;padding:20px}
    .sidebar h2{text-align:center;margin-bottom:30px}
    .sidebar a{display:block;padding:12px 15px;margin-bottom:10px;border-radius:8px;text-decoration:none;color:white}
    .sidebar a:hover{background:rgba(255,255,255,.15)}

    /* MAIN */
    .main{flex:1;display:flex;flex-direction:column}

    /* TOPBAR */
    .topbar{background:white;padding:15px 25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 6px rgba(0,0,0,.08)}
    .logout-btn{background:#dc2626;border:none;padding:8px 14px;color:white;border-radius:6px;cursor:pointer}
    .logout-btn:hover{background:#b91c1c}

    /* CONTENT */
    .content{padding:25px}

    /* TABS */
    .tabs{display:flex;gap:10px;margin-bottom:20px}
    .tab{
        padding:8px 14px;
        border-radius:6px;
        text-decoration:none;
        font-size:14px;
        font-weight:600;
        background:#e5e7eb;
        color:#374151;
    }
    .tab.active{
        background:#2563eb;
        color:white;
    }

    /* CARD */
    .card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 18px rgba(0,0,0,.08);margin-bottom:30px}

    /* TABLE */
    table{width:100%;border-collapse:collapse;margin-top:15px}
    th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left}
    th{background:#f1f5f9}

    /* FORM */
    input, select{padding:6px 10px;border:1px solid #ccc;border-radius:6px;margin-right:8px}

    /* BUTTONS */
    .btn{padding:10px 18px;border-radius:6px;border:none;cursor:pointer;font-size:15px;color:white}
    .btn-primary{background:#2563eb}
    .btn-primary:hover{background:#1d4ed8}
    .btn-reset{background:#6b7280}
    .btn-reset:hover{background:#4b5563}

    /* ALERT */
    .custom-alert{background:#16a34a;color:#fff;padding:12px 20px;margin-bottom:15px;border-radius:6px;font-weight:bold}

    /* BADGES */
    .badge{padding:4px 10px;border-radius:20px;font-size:13px;font-weight:600;display:inline-block}
    .present{background:#dcfce7;color:#166534}
    .absent{background:#fee2e2;color:#991b1b}
    .half_day{background:#fef3c7;color:#92400e}
    .unmark{background:#e5e7eb;color:#374151}

    /* FILTER */
    .filter-form{margin-bottom:20px;display:flex;align-items:center;flex-wrap:wrap}
</style>
</head>

<body>

@include('layouts.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Attendance</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="custom-alert">{{ session('success') }}</div>
    @endif

    {{-- ROLE TABS --}}
    <div class="tabs">
        <a href="{{ route('attendance.index') }}"
        class="tab {{ request('role') == null ? 'active' : '' }}">All</a>

        <a href="{{ route('attendance.index', ['role' => 'intern']) }}"
        class="tab {{ request('role') == 'intern' ? 'active' : '' }}">Interns</a>

        <a href="{{ route('attendance.index', ['role' => 'employee']) }}"
        class="tab {{ request('role') == 'employee' ? 'active' : '' }}">Employees</a>
    </div>

    {{-- FILTER --}}
    <div class="card">
        <h3>Filter Attendance</h3>
        <form method="GET" class="filter-form">
            <input type="hidden" name="role" value="{{ request('role') }}">
            <input type="date" name="filter_date" value="{{ request('filter_date', $attendanceDate) }}">
            <input type="text" name="filter_name" value="{{ request('filter_name') }}" placeholder="Name">
            <button class="btn btn-primary">Search</button>
            <a href="{{ route('attendance.index', ['role' => request('role')]) }}" class="btn btn-reset">Reset</a>
        </form>
    </div>

    {{-- MARK ATTENDANCE --}}
    <div class="card">
    @php $markDate = request('filter_date', $attendanceDate); @endphp

    <h3>Mark Attendance for {{ $markDate }}</h3>

    <form method="POST" action="{{ route('attendance.store') }}">
    @csrf
    <input type="hidden" name="date" value="{{ $markDate }}">

    <table>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>Location</th>
        <th>In Time</th>
        <th>Out Time</th>
    </tr>

    @foreach($interns as $intern)
    @php
        $saved = $recordsForDate->firstWhere('intern_id', $intern->id);
    @endphp
    <tr>
        <td>{{ $intern->name }}</td>
        <td>{{ $markDate }}</td>

        <td>
            <select name="interns[{{ $intern->id }}][status]">
                <option value="unmark" {{ !$saved ? 'selected' : '' }}>Unmark</option>
                <option value="present" {{ optional($saved)->status === 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ optional($saved)->status === 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="half_day" {{ optional($saved)->status === 'half_day' ? 'selected' : '' }}>Half Day</option>
                <option value="paid_leave" {{ optional($saved)->status === 'paid_leave' ? 'selected' : '' }}>Paid Leave</option>
            </select>
        </td>

        <td>
            <input type="text"
                name="interns[{{ $intern->id }}][location]"
                value="{{ $saved->location ?? 'Office' }}"
                placeholder="Location">
        </td>

        <td>
            <input type="time"
                name="interns[{{ $intern->id }}][in_time]"
                value="{{ $saved->in_time ?? '' }}">
        </td>

        <td>
            <input type="time"
                name="interns[{{ $intern->id }}][out_time]"
                value="{{ $saved->out_time ?? '' }}">
        </td>
    </tr>
    @endforeach
    </table>

    <button class="btn btn-primary" style="margin-top:20px">
        Save Attendance
    </button>
    </form>
    </div>


    {{-- HISTORY --}}
    <div class="card">
    <h3>Attendance History</h3>

    <table>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>Location</th>
        <th>In Time</th>
        <th>Out Time</th>
    </tr>

    @forelse($allRecords as $i => $att)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $att->intern->name }}</td>
        <td>{{ $att->date->format('Y-m-d') }}</td>

        <td>
            <span class="badge {{ $att->status }}">
                {{ ucfirst(str_replace('_',' ',$att->status)) }}
            </span>
        </td>

        <td>{{ $att->location ?? '-' }}</td>

        <td>
            {{ $att->in_time ? \Carbon\Carbon::parse($att->in_time)->format('H:i') : '-' }}
        </td>

        <td>
            {{ $att->out_time ? \Carbon\Carbon::parse($att->out_time)->format('H:i') : '-' }}
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7">No records found</td>
    </tr>
    @endforelse
    </table>
    </div>

    </div>
</div>

</body>
</html>
