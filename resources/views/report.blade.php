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
                    <button class="btn btn-primary"><ion-icon name="search-outline"></ion-icon> Search</button>
                    <a href="{{ route('report') }}" class="btn btn-reset"><ion-icon name="refresh-outline"></ion-icon> Reset</a>
                </form>
            </div>

            <!-- KPI -->
            <div class="kpi-grid">
                <div class="kpi kpi-blue">
                    <div class="kpi-icon"><ion-icon name="trending-up-outline"></ion-icon></div>
                    <div>
                        <h3>Attendance Rate</h3>
                        <strong>{{ $totalDays ? round(($presentCount / $totalDays) * 100) : 0 }}%</strong>
                    </div>
                </div>
                <div class="kpi kpi-green">
                    <div class="kpi-icon"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Present</h3>
                        <strong>{{ $presentCount }}</strong>
                    </div>
                </div>
                <div class="kpi kpi-yellow">
                    <div class="kpi-icon"><ion-icon name="remove-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Half Day</h3>
                        <strong>{{ $halfDayCount }}</strong>
                    </div>
                </div>
                <div class="kpi kpi-red">
                    <div class="kpi-icon"><ion-icon name="close-circle-outline"></ion-icon></div>
                    <div>
                        <h3>Absent</h3>
                        <strong>{{ $absentCount }}</strong>
                    </div>
                </div>
                <div class="kpi kpi-purple">
                    <div class="kpi-icon"><ion-icon name="layers-outline"></ion-icon></div>
                    <div>
                        <h3>Total Days</h3>
                        <strong>{{ $totalDays }}</strong>
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
                <div class="card-title">
                    <div class="card-icon bg-amber">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <h3>Intern Attendance Summary</h3>
                </div>
                <table>
                    <tr>
                        <th>#</th>
                        <th><ion-icon name="person-outline"></ion-icon> Intern</th>
                        <th><ion-icon name="checkmark-outline"></ion-icon> Present</th>
                        <th><ion-icon name="remove-outline"></ion-icon> Half Day</th>
                        <th><ion-icon name="close-outline"></ion-icon> Absent</th>
                        <th><ion-icon name="layers-outline"></ion-icon> Total</th>
                        <th><ion-icon name="eye-outline"></ion-icon> Action</th>
                    </tr>
                    @foreach($internSummaries as $i => $intern)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $intern['name'] }}</td>
                            <td><span class="badge present">{{ $intern['present'] }}</span></td>
                            <td><span class="badge half_day">{{ $intern['half_day'] }}</span></td>
                            <td><span class="badge absent">{{ $intern['absent'] }}</span></td>
                            <td><strong>{{ $intern['total'] }}</strong></td>
                            <td><a href="{{ route('attendance.show', $intern['id']) }}" class="view-btn"><ion-icon name="eye-outline"></ion-icon> View</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    </div>

    <!-- CHART JS -->
    <script>
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Half Day', 'Absent'],
                datasets: [{
                    data: [{{ $presentCount }}, {{ $halfDayCount }}, {{ $absentCount }}],
                    backgroundColor: ['#22c55e', '#facc15', '#ef4444']
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

</body>

</html>