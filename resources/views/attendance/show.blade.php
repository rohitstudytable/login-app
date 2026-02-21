<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('report') }}" class="back-btn">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                </a>
                <h2>{{ $intern->name }} Attendance</h2>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            <!-- STATS -->
            <div class="stats">
                <div class="stat-box stat-total">
                    <div class="stat-icon"><ion-icon name="layers-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Total Days</span>
                        <strong>{{ $totalDays }}</strong>
                    </div>
                </div>
                <div class="stat-box stat-present">
                    <div class="stat-icon"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Present</span>
                        <strong>{{ $presentCount }}</strong>
                    </div>
                </div>
                <div class="stat-box stat-absent">
                    <div class="stat-icon"><ion-icon name="close-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Absent</span>
                        <strong>{{ $absentCount }}</strong>
                    </div>
                </div>
                <div class="stat-box stat-halfday">
                    <div class="stat-icon"><ion-icon name="remove-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Half Day</span>
                        <strong>{{ $halfDayCount }}</strong>
                    </div>
                </div>
                <div class="stat-box stat-paidleave">
                    <div class="stat-icon"><ion-icon name="airplane-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Paid Leave</span>
                        <strong>{{ $paidLeaveCount }}</strong>
                    </div>
                </div>
            </div>

            <div class="card card-show-history">

                <div class="card-title">
                    <div class="card-icon bg-indigo">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                    <h3>Attendance History</h3>
                </div>

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

                    <button class="btn btn-primary"><ion-icon name="search-outline"></ion-icon> Search</button>
                    <a href="{{ route('attendance.show', $intern->id) }}" class="btn btn-reset"><ion-icon name="refresh-outline"></ion-icon> Reset</a>
                </form>

                <!-- TABLE -->
                <table>
                    <tr>
                        <th>#</th>
                        <th><ion-icon name="calendar-outline"></ion-icon> Date</th>
                        <th><ion-icon name="flag-outline"></ion-icon> Status</th>
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
                            <td colspan="3" class="empty-state">
                                <ion-icon name="file-tray-outline"></ion-icon>
                                <p>No attendance records found</p>
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>

        </div>
    </div>

</body>

</html>