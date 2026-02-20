<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <div class="topbar">
            <h2>Attendance</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="custom-alert">{{ session('success') }}</div>
            @endif

            {{-- ===============================
            CHECK HOLIDAY / SUNDAY
            ================================ --}}
            @php
                use Carbon\Carbon;
                use App\Models\Holiday;

                $markDate = request('filter_date', $attendanceDate);
                $checkDate = Carbon::parse($markDate);

                $isSunday = $checkDate->isSunday();
                $isHoliday = Holiday::whereDate('holiday_date', $checkDate)->exists();
                $isBlocked = $isSunday || $isHoliday;
            @endphp

            @if($isBlocked)
                <div class="holiday-alert">
                    🚫 Attendance cannot be marked.
                    {{ $isSunday ? 'Today is Sunday.' : '' }}
                    {{ $isHoliday ? 'Today is an Admin Holiday.' : '' }}
                </div>
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
                    <input type="date" name="filter_date" value="{{ $markDate }}">
                    <input type="text" name="filter_name" value="{{ request('filter_name') }}" placeholder="Name">
                    <button class="btn btn-primary">Search</button>
                    <a href="{{ route('attendance.index', ['role' => request('role')]) }}"
                        class="btn btn-reset">Reset</a>
                </form>
            </div>

            {{-- ===============================
            MARK ATTENDANCE
            ================================ --}}
            <div class="card">

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
                            @php $saved = $recordsForDate->firstWhere('intern_id', $intern->id); @endphp
                            <tr>
                                <td>{{ $intern->name }}</td>
                                <td>{{ $markDate }}</td>

                                <td>
                                    <select name="interns[{{ $intern->id }}][status]" {{ $isBlocked ? 'disabled' : '' }}>
                                        <option value="unmark" {{ !$saved ? 'selected' : '' }}>Unmark</option>
                                        <option value="present" {{ optional($saved)->status === 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ optional($saved)->status === 'absent' ? 'selected' : '' }}>
                                            Absent</option>
                                        <option value="half_day" {{ optional($saved)->status === 'half_day' ? 'selected' : '' }}>Half Day</option>
                                        <option value="paid_leave" {{ optional($saved)->status === 'paid_leave' ? 'selected' : '' }}>Paid Leave</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="interns[{{ $intern->id }}][location]"
                                        value="{{ $saved->location ?? 'Office' }}" {{ $isBlocked ? 'disabled' : '' }}>
                                </td>

                                <td>
                                    <input type="time" name="interns[{{ $intern->id }}][in_time]"
                                        value="{{ $saved->in_time ?? '' }}" {{ $isBlocked ? 'disabled' : '' }}>
                                </td>

                                <td>
                                    <input type="time" name="interns[{{ $intern->id }}][out_time]"
                                        value="{{ $saved->out_time ?? '' }}" {{ $isBlocked ? 'disabled' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    @if(!$isBlocked)
                        <button class="btn btn-primary" style="margin-top:20px">Save Attendance</button>
                    @endif

                </form>
            </div>

            {{-- ===============================
            HISTORY
            ================================ --}}
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
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $att->intern->name }}</td>
                            <td>{{ $att->date->format('Y-m-d') }}</td>
                            <td><span
                                    class="badge {{ $att->status }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span>
                            </td>
                            <td>{{ $att->location ?? '-' }}</td>
                            <td>{{ $att->in_time ? \Carbon\Carbon::parse($att->in_time)->format('H:i') : '-' }}</td>
                            <td>{{ $att->out_time ? \Carbon\Carbon::parse($att->out_time)->format('H:i') : '-' }}</td>
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