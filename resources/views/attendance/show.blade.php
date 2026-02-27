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
                {{-- TOTAL DAYS --}}
                <div class="stat-box stat-total">
                    <div class="stat-icon"><ion-icon name="layers-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Total Days</span>
                        <strong>{{ $totalDays ?? 0 }}</strong>
                    </div>
                </div>

                {{-- PRESENT --}}
                <div class="stat-box stat-present">
                    <div class="stat-icon"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Present</span>
                        <strong>{{ $presentCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- HALF DAY --}}
                <div class="stat-box stat-halfday">
                    <div class="stat-icon"><ion-icon name="remove-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Half Day</span>
                        <strong>{{ $halfDayCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- BELOW HALF DAY --}}
                <div class="stat-box stat-below-halfday">
                    <div class="stat-icon"><ion-icon name="alert-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Below Half Day</span>
                        <strong>{{ $belowHalfDayCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- OVERTIME --}}
                <div class="stat-box stat-overtime">
                    <div class="stat-icon"><ion-icon name="rocket-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Overtime</span>
                        <strong>{{ $overtimeCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- ABSENT --}}
                <div class="stat-box stat-absent">
                    <div class="stat-icon"><ion-icon name="close-circle-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Absent</span>
                        <strong>{{ $absentCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- PAID LEAVE --}}
                <div class="stat-box stat-paidleave">
                    <div class="stat-icon"><ion-icon name="airplane-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Paid Leave</span>
                        <strong>{{ $paidLeaveCount ?? 0 }}</strong>
                    </div>
                </div>

                {{-- LATE CHECK-IN / CHECK-OUT --}}
                <div class="stat-box stat-late-checkin-checkout">
                    <div class="stat-icon"><ion-icon name="time-outline"></ion-icon></div>
                    <div>
                        <span class="stat-label">Late Check-in/Out</span>
                        <strong>{{ $lateCheckinCheckoutCount ?? 0 }}</strong>
                    </div>
                </div>
            </div>

            <div class="card card-show-history">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title">
                        <div class="card-icon bg-indigo">
                            <ion-icon name="time-outline"></ion-icon>
                        </div>
                        <h3>Attendance History</h3>
                    </div>
                    <button class="btn btn-primary excelBtn">
                        <ion-icon name="download-outline"></ion-icon> Export
                    </button>
                </div>

                <!-- FILTER -->
                <form method="GET" class="filter-form">
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" value="{{ request('end_date') }}">

                    <select name="status">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late_early" {{ request('status') == 'late_early' ? 'selected' : '' }}>Late / Early
                        </option>
                        <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        <option value="below_half_day" {{ request('status') == 'below_half_day' ? 'selected' : '' }}>Below
                            Half Day</option>
                        <option value="overtime" {{ request('status') == 'overtime' ? 'selected' : '' }}>Overtime</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    </select>

                    <button class="btn btn-primary">
                        <ion-icon name="search-outline"></ion-icon> Search
                    </button>
                    <a href="{{ route('attendance.show', $intern->id) }}" class="btn btn-reset">
                        <ion-icon name="refresh-outline"></ion-icon> Reset
                    </a>
                </form>

                {{-- TABLE --}}
                <table class="excelTable">
                    <tr>
                        <th>#</th>
                        <th><ion-icon name="calendar-outline"></ion-icon> Date</th>
                        <th><ion-icon name="flag-outline"></ion-icon> Status</th>
                    </tr>

                    @forelse($attendances as $i => $attendance)
                        @php
                            $status = $attendance->status ?? 'absent';
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge {{ $status }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
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


    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        document.querySelector(".excelBtn").addEventListener("click", function () {

            let table = document.querySelector(".excelTable");

            // Convert table to worksheet
            let workbook = XLSX.utils.table_to_book(table, { sheet: "Intern Attendance" });

            // Download Excel file
            XLSX.writeFile(workbook, "Intern_Attendance_Summary.xlsx");
        });
    </script>

</body>

</html>