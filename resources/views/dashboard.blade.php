<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')


</head>

<body>

    @include('layouts.sidebar')

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Dashboard</h2>
            <div class="profile">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- CARDS -->
            <div class="card-grid">
                <div class="card">
                    <h3>Manage Interns</h3>
                    <p>Add, edit or delete interns.</p>
                    <a href="{{ route('interns.index') }}">Go</a>
                </div>

                <div class="card">
                    <h3>Mark Attendance</h3>
                    <p>Record daily attendance.</p>
                    <a href="{{ route('attendance.index') }}">Go</a>
                </div>

                <div class="card">
                    <h3>Attendance History</h3>
                    <p>View past records.</p>
                    <a href="{{ route('attendance.index') }}">Go</a>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="chart-grid">
                <div class="chart-card">
                    <h3>Today's Attendance</h3>
                    <canvas id="attendanceChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Monthly Attendance</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Attendance Trend</h3>
                    <canvas id="otherChart"></canvas>
                </div>
            </div>

            <!-- TOPBAR -->
            <div class="topbar">
                <h2>Dashboard</h2>
                <div class="profile">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {

                    /* TODAY */
                    new Chart(document.getElementById('attendanceChart'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Present', 'Absent', 'Half Day'],
                            datasets: [{
                                data: [
                    {{ $presentCount }},
                    {{ $absentCount }},
                                    {{ $halfDayCount }}
                                ],
                                backgroundColor: ['#16a34a', '#dc2626', '#f59e0b']
                            }]
                        }
                    });

                    /* MONTHLY */
                    const monthlyLabels = @json($monthlyData->pluck('day'));
                    const monthlyTotals = @json($monthlyData->pluck('total'));

                    if (monthlyLabels.length > 0) {
                        new Chart(document.getElementById('monthlyChart'), {
                            type: 'bar',
                            data: {
                                labels: monthlyLabels,
                                datasets: [{
                                    label: 'Present Count',
                                    data: monthlyTotals,
                                    backgroundColor: '#2563eb'
                                }]
                            }
                        });
                    }

                    /* TREND */
                    const trendLabels = @json($trendData->pluck('date'));
                    const trendTotals = @json($trendData->pluck('total'));

                    if (trendLabels.length > 0) {
                        new Chart(document.getElementById('otherChart'), {
                            type: 'line',
                            data: {
                                labels: trendLabels,
                                datasets: [{
                                    label: 'Attendance Trend',
                                    data: trendTotals,
                                    borderColor: '#16a34a',
                                    tension: 0.4,
                                    fill: false
                                }]
                            }
                        });
                    }

                });
            </script>





</body>

</html>