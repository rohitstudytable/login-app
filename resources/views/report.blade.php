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
        </div>

        <div class="content">

            <!-- FILTER -->
            <div class="card">
                <form method="GET" class="filter-form">
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                    <button class="btn-primary">Search</button>
                    <a href="{{ route('report') }}" class="btn-reset">Reset</a>
                </form>
            </div>

            <!-- KPI -->
            <div class="kpi-grid">
                <div class="kpi">
                    <h3>Attendance Rate</h3>
                    <strong>{{ $totalDays ? round(($presentCount / $totalDays) * 100) : 0 }}%</strong>
                </div>
                <div class="kpi">
                    <h3>Present</h3><strong>{{ $presentCount }}</strong>
                </div>
                <div class="kpi">
                    <h3>Half Day</h3><strong>{{ $halfDayCount }}</strong>
                </div>
                <div class="kpi">
                    <h3>Absent</h3><strong>{{ $absentCount }}</strong>
                </div>
                <div class="kpi">
                    <h3>Total Days</h3><strong>{{ $totalDays }}</strong>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="dashboard-grid">
                <div class="card">
                    <h3>Overall Distribution</h3>
                    <canvas id="donutChart" height="220"></canvas>
                </div>
                <div class="card">
                    <h3>Daily Trend (Last 7 Days)</h3>
                    <canvas id="barChart" height="220"></canvas>
                </div>
            </div>

            <!-- INTERN TABLE -->
            <div class="card">
                <h3>Intern Attendance Summary</h3>
                <table>
                    <tr>
                        <th>#</th>
                        <th>Intern</th>
                        <th>Present</th>
                        <th>Half Day</th>
                        <th>Absent</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    @foreach($internSummaries as $i => $intern)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $intern['name'] }}</td>
                            <td>{{ $intern['present'] }}</td>
                            <td>{{ $intern['half_day'] }}</td>
                            <td>{{ $intern['absent'] }}</td>
                            <td>{{ $intern['total'] }}</td>
                            <td><a href="{{ route('attendance.show', $intern['id']) }}" class="view-btn">View</a></td>
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