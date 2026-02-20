<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
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
                <div class="stat-box total">
                    Total Days<br>{{ $totalDays }}
                </div>
                <div class="stat-box present">
                    Present<br>{{ $presentCount }}
                </div>
                <div class="stat-box absent">
                    Absent<br>{{ $absentCount }}
                </div>
                <div class="stat-box half_day">
                    Half Day<br>{{ $halfDayCount }}
                </div>
                <div class="stat-box paid_leave">
                    Paid Leave<br>{{ $paidLeaveCount }}
                </div>
            </div>

            <div class="card">
                <h3>Attendance History</h3>

                <!-- FILTER -->
                <form method="GET" class="filter-form">
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" value="{{ request('end_date') }}">

                    <select name="status">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        <option value="paid_leave" {{ request('status') == 'paid_leave' ? 'selected' : '' }}>Paid Leave
                        </option>
                    </select>

                    <button class="btn btn-primary">Search</button>
                    <a href="{{ route('attendance.show', $intern->id) }}" class="btn btn-reset">Reset</a>
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
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
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