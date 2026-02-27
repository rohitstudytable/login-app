<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Attendance Report</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            <!-- FILTER -->
            <div class="card card-filter">
                <div class="card-title">
                    <div class="card-icon bg-indigo">
                        <ion-icon name="funnel-outline"></ion-icon>
                    </div>
                    <h3>Filter Report</h3>
                </div>
                <form method="GET" class="filter-form">
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                    <button class="btn btn-primary">
                        <ion-icon name="search-outline"></ion-icon> Search
                    </button>
                    <a href="{{ route('report') }}" class="btn btn-reset">
                        <ion-icon name="refresh-outline"></ion-icon> Reset
                    </a>
                </form>
            </div>

            <!-- KPI -->
            <div class="kpi-grid">

                <div class="kpi kpi-green">
                    <div class="kpi-icon"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Present</h3>
                        <strong>{{ $presentCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-yellow">
                    <div class="kpi-icon"><ion-icon name="remove-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Half Day</h3>
                        <strong>{{ $halfDayCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-orange">
                    <div class="kpi-icon"><ion-icon name="alert-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Below Half Day</h3>
                        <strong>{{ $belowHalfDayCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-purple">
                    <div class="kpi-icon"><ion-icon name="rocket-outline"></ion-icon></div>
                    <div>
                        <h3>Overtime</h3>
                        <strong>{{ $overtimeCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-red">
                    <div class="kpi-icon"><ion-icon name="close-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Absent</h3>
                        <strong>{{ $absentCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-teal">
                    <div class="kpi-icon"><ion-icon name="gift-outline"></ion-icon></div>
                    <div>
                        <h3>Paid Leave</h3>
                        <strong>{{ $paidLeaveCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-gray">
                    <div class="kpi-icon"><ion-icon name="time-outline"></ion-icon></div>
                    <div>
                        <h3>Late Check-in/Check-out</h3>
                        <strong>{{ $lateCheckinCheckoutCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-dark">
                    <div class="kpi-icon"><ion-icon name="layers-outline"></ion-icon></div>
                    <div>
                        <h3>Total Days</h3>
                        <strong>{{ $totalDays ?? 0 }}</strong>
                    </div>
                </div>

            </div>

            <!-- CHARTS -->
            <div class="dashboard-grid">
                <div class="card card-chart-donut">
                    <div class="card-title">
                        <div class="card-icon bg-green">
                            <ion-icon name="pie-chart-outline"></ion-icon>
                        </div>
                        <h3>Overall Distribution</h3>
                    </div>
                    <canvas id="donutChart" height="220"></canvas>
                </div>

                <div class="card card-chart-bar">
                    <div class="card-title">
                        <div class="card-icon bg-indigo">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                        </div>
                        <h3>Daily Trend (Last 7 Days)</h3>
                    </div>
                    <canvas id="barChart" height="220"></canvas>
                </div>
            </div>

            <!-- INTERN TABLE -->
            <div class="card card-summary">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title">
                        <div class="card-icon bg-amber">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                        <h3>Intern Attendance Summary</h3>
                    </div>
                    <button class="btn btn-primary excelBtn">
                        <ion-icon name="download-outline"></ion-icon> Export
                    </button>
                </div>


                <table class="excelTable">
                    <tr>
                        <th>#</th>
                        <th>Intern</th>
                        <th>Present</th>
                        <th>Half Day</th>
                        <th>Below Half Day</th>
                        <th>Overtime</th>
                        <th>Absent</th>
                        <th>Paid Leave</th>
                        <th>Late CheckIn/Out</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>

                    @forelse($internSummaries ?? [] as $i => $intern)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $intern['name'] }}</td>
                            <td class="text-center"><span class="badge present">{{ $intern['present'] ?? 0 }}</span></td>
                            <td class="text-center"><span class="badge half_day">{{ $intern['half_day'] ?? 0 }}</span></td>
                            <td class="text-center"><span
                                    class="badge below_half_day">{{ $intern['below_half_day'] ?? 0 }}</span></td>
                            <td class="text-center"><span class="badge overtime">{{ $intern['overtime'] ?? 0 }}</span></td>
                            <td class="text-center"><span class="badge absent">{{ $intern['absent'] ?? 0 }}</span></td>
                            <td class="text-center"><span class="badge paid_leave">{{ $intern['paid_leave'] ?? 0 }}</span>
                            </td>
                            <td class="text-center"><span
                                    class="badge late_checkin_checkout">{{ $intern['late_checkin_checkout'] ?? 0 }}</span>
                            </td>
                            <td class="text-center"><strong>{{ $intern['total'] ?? 0 }}</strong></td>
                            <td>
                                <a href="{{ route('attendance.show', $intern['id']) }}" class="icon-btn view-btn">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </table>
            </div>


        </div>
    </div>

    <!-- CHART JS -->
    <script>
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: [
                    'Present', 'Half Day', 'Below Half Day',
                    'Overtime', 'Absent', 'Paid Leave', 'Late'
                ],
                datasets: [{
                    data: [
                {{ $presentCount ?? 0 }},
                {{ $halfDayCount ?? 0 }},
                {{ $belowHalfDayCount ?? 0 }},
                {{ $overtimeCount ?? 0 }},
                {{ $absentCount ?? 0 }},
                {{ $paidLeaveCount ?? 0 }},
                        {{ $lateCheckinCheckoutCount ?? 0 }}
                    ],
                    backgroundColor: [
                        '#22c55e', '#facc15', '#fb923c',
                        '#a855f7', '#ef4444', '#14b8a6', '#64748b'
                    ]
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($last7Dates ?? []) !!},
                datasets: [{
                    label: 'Present',
                    data: {!! json_encode($last7PresentCounts ?? []) !!},
                    backgroundColor: '#2563eb'
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>




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