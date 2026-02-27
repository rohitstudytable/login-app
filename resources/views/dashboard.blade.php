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
            <h2>Dashboard</h2>
            <div class="profile">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <!-- ================== Dashboard Content ================== -->
        <div class="content">

            <!-- ====== Stats Row ====== -->
            <div class="stats-row">

                <div class="stats-item bg-light-green">
                    <div class="stats-icon">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                    </div>
                    <div class="stats-info">
                        <h3>{{ $presentCount }}</h3>
                        <p>Present</p>
                    </div>
                </div>

                <div class="stats-item bg-light-red">
                    <div class="stats-icon">
                        <ion-icon name="close-outline"></ion-icon>
                    </div>
                    <div class="stats-info">
                        <h3>{{ $absentCount }}</h3>
                        <p>Absent</p>
                    </div>
                </div>

                <div class="stats-item bg-light-yellow">
                    <div class="stats-icon">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                    <div class="stats-info">
                        <h3>{{ $halfDayCount }}</h3>
                        <p>Half Day</p>
                    </div>
                </div>

            </div>

            <!-- ====== Action Cards ====== -->
            <div class="actions-row">

                <div class="action-card">
                    <h4>Manage Interns</h4>
                    <p>Add, edit or delete interns</p>
                    <a href="{{ route('interns.index') }}" class="btn-action">Go</a>
                </div>

                <div class="action-card">
                    <h4>Mark Attendance</h4>
                    <p>Record daily attendance</p>
                    <a href="{{ route('attendance.index') }}" class="btn-action">Go</a>
                </div>

                <div class="action-card">
                    <h4>Attendance History</h4>
                    <p>View past records</p>
                    <a href="{{ route('attendance.index') }}" class="btn-action">Go</a>
                </div>

            </div>


            <!-- ====== Chart Section ====== -->
            <div class="charts-section">

                <div class="chart-card">
                    <h5>Today's Attendance</h5>
                    <canvas id="attendanceChart"></canvas>
                </div>

                <div class="chart-card">
                    <h5>Monthly Attendance</h5>
                    <canvas id="monthlyChart"></canvas>
                </div>

                <div class="chart-card">
                    <h5>Attendance Trend</h5>
                    <canvas id="otherChart"></canvas>
                </div>

            </div>

        </div>
    </div>


    <!-- Chart Script (no change) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

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
                        backgroundColor: ['#22c55e', '#ef4444', '#f59e0b']
                    }]
                }
            });

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
                            backgroundColor: '#3b82f6'
                        }]
                    }
                });
            }

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
                            borderColor: '#22c55e',
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