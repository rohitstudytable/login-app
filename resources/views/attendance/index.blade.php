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
            <div class="custom-alert">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ===============================
        CHECK HOLIDAY / SUNDAY
        ================================ --}}
        @php
            $markDate  = request('filter_date', $attendanceDate);
            $checkDate = \Carbon\Carbon::parse($markDate);

            $isSunday  = $checkDate->isSunday();
            $isHoliday = \App\Models\Holiday::whereDate('holiday_date', $checkDate)->exists();
            $isBlocked = $isSunday || $isHoliday;
        @endphp

        @if($isBlocked)
            <div class="holiday-alert">
                <ion-icon name="alert-circle-outline"></ion-icon>
                <span>
                    Attendance cannot be marked.
                    {{ $isSunday ? 'Today is Sunday.' : '' }}
                    {{ $isHoliday ? 'Today is a Holiday.' : '' }}
                </span>
            </div>
        @endif

        {{-- ROLE TABS --}}
        <div class="tabs">
            <a href="{{ route('attendance.index') }}" class="tab {{ request('role') == null ? 'active' : '' }}">
                <ion-icon name="people-outline"></ion-icon> All
            </a>
            <a href="{{ route('attendance.index', ['role' => 'intern']) }}"
               class="tab {{ request('role') == 'intern' ? 'active' : '' }}">
                <ion-icon name="school-outline"></ion-icon> Interns
            </a>
            <a href="{{ route('attendance.index', ['role' => 'employee']) }}"
               class="tab {{ request('role') == 'employee' ? 'active' : '' }}">
                <ion-icon name="briefcase-outline"></ion-icon> Employees
            </a>
        </div>

        {{-- FILTER --}}
        <div class="card card-filter">
            <div class="card-title">
                <div class="card-icon bg-indigo">
                    <ion-icon name="funnel-outline"></ion-icon>
                </div>
                <h3>Filter Attendance</h3>
            </div>

            <form method="GET" class="filter-form">
                <input type="hidden" name="role" value="{{ request('role') }}">
                <input type="date" name="filter_date" value="{{ $markDate }}">
                <input type="text" name="filter_name" value="{{ request('filter_name') }}"
                       placeholder="Search by name...">
                <button class="btn btn-primary">
                    <ion-icon name="search-outline"></ion-icon> Search
                </button>
                <a href="{{ route('attendance.index', ['role' => request('role')]) }}"
                   class="btn btn-reset">
                    <ion-icon name="refresh-outline"></ion-icon> Reset
                </a>
            </form>
        </div>

      {{-- ===============================
        MARK ATTENDANCE
        ================================ --}}
        <div class="card card-mark">
    <div class="card-title">
        <div class="card-icon bg-green">
            <ion-icon name="checkmark-done-outline"></ion-icon>
        </div>
        <h3>Mark Attendance for {{ $markDate }}</h3>
    </div>

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

            @php
                // Enum values for the status dropdown
                $statusOptions = [
                    'present' => 'Present',
                    'half_day' => 'Half Day',
                    'below_half_day' => 'Below Half Day',
                    'overtime' => 'Overtime',
                    'absent' => 'Absent',
                    'paid_leave' => 'Paid Leave',
                    'late_checkin_checkout' => 'Late Check-in/Checkout',
                ];
            @endphp

            @foreach($interns as $intern)
                @php
                    $saved = $recordsForDate->firstWhere('intern_id', $intern->id);

                    $workedMinutes = null;
                    if ($saved?->in_time && $saved?->out_time) {
                        $workedMinutes = \Carbon\Carbon::parse($saved->in_time)
                            ->diffInMinutes(\Carbon\Carbon::parse($saved->out_time));
                    }

                    if ($workedMinutes !== null) {
                        if ($workedMinutes >= 540) {
                            $finalStatus = 'overtime';
                        } elseif ($workedMinutes >= 465) {
                            $finalStatus = 'present';
                        } elseif ($workedMinutes >= 420 && $workedMinutes < 465) {
                             $finalStatus = 'late_checkin_checkout';
                        } elseif ($workedMinutes >= 240) {
                            $finalStatus = 'half_day';
                        } else {
                            $finalStatus = 'absent';
                        }
                    } else {
                        $finalStatus = $saved->status ?? 'absent';
                    }
                @endphp

                <tr>
                    <td>{{ $intern->name }}</td>
                    <td>{{ $markDate }}</td>

                    {{-- STATUS SELECT --}}
                    <td>
                        <select name="interns[{{ $intern->id }}][status]" 
                                {{ $isBlocked ? 'disabled' : '' }}>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ ($saved->status ?? $finalStatus) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    {{-- LOCATION --}}
                    <td>
                        <input type="text"
                            name="interns[{{ $intern->id }}][location]"
                            value="{{ old('interns.'.$intern->id.'.location', $saved->location ?? 'Office') }}"
                            {{ $isBlocked ? 'readonly' : '' }}>
                    </td>

                    {{-- IN TIME --}}
                    <td>
                        <input type="time"
                            name="interns[{{ $intern->id }}][in_time]"
                            value="{{ $saved?->in_time ? \Carbon\Carbon::parse($saved->in_time)->format('H:i') : '' }}"
                            {{ $isBlocked ? 'readonly' : '' }}>
                    </td>

                    {{-- OUT TIME --}}
                    <td>
                        <input type="time"
                            name="interns[{{ $intern->id }}][out_time]"
                            value="{{ $saved?->out_time ? \Carbon\Carbon::parse($saved->out_time)->format('H:i') : '' }}"
                            {{ $isBlocked ? 'readonly' : '' }}>
                    </td>
                </tr>
            @endforeach
        </table>

        @if(!$isBlocked)
            <button class="btn btn-primary save-attendance-btn">
                <ion-icon name="save-outline"></ion-icon>
                Save Attendance
            </button>
        @endif
    </form>
</div>

        {{-- ===============================
        HISTORY
        ================================ --}}
      <div class="card card-history">
        <div class="card-title">
            <div class="card-icon bg-amber">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <h3>Attendance History</h3>
        </div>

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
                        @php
                            $status = 'absent'; // default

                            if ($att->in_time && $att->out_time) {
                                $workedMinutes = \Carbon\Carbon::parse($att->in_time)
                                    ->diffInMinutes(\Carbon\Carbon::parse($att->out_time));

                                if ($workedMinutes >= 540) {
                                    $status = 'overtime';
                                } elseif ($workedMinutes >= 465 && $workedMinutes < 540) {
                                    $status = 'present';        // Regular Day Shift
                                } elseif ($workedMinutes >= 420 && $workedMinutes < 465) {
                                    $status = 'present_early_checkout'; // Early Checkout / Late Check-in
                                } elseif ($workedMinutes >= 240 && $workedMinutes < 420) {
                                    $status = 'half_day';
                                } elseif ($workedMinutes >= 120 && $workedMinutes < 240) {
                                    $status = 'below_half_day';
                                } else {
                                    $status = 'absent';
                                }
                            }
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $att->intern->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge {{ $status }}">
                                    {{ str_replace('_',' ', ucfirst($status)) }}
                                </span>
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