<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendence Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* =====================
    GLOBAL
    ===================== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f9;
        min-height: 100vh;
        display: flex;
    }

    /* =====================
    SIDEBAR
    ===================== */
    .sidebar {
        width: 240px;
        background: linear-gradient(180deg, #1d4ed8, #1e40af);
        color: #fff;
        padding: 20px;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 22px;
    }

    .sidebar a {
        display: block;
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-weight: 500;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.15);
    }

    /* =====================
    MAIN AREA
    ===================== */
    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* =====================
    TOPBAR
    ===================== */
    .topbar {
        background: white;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logout-btn {
        background: #dc2626;
        border: none;
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    .logout-btn:hover {
        background: #b91c1c;
    }

    /* =====================
    CONTENT
    ===================== */
    .content {
        padding: 25px;
    }

    /* =====================
    CARDS GRID
    ===================== */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.12);
    }

    .card h3 {
        margin-bottom: 10px;
        font-size: 18px;
    }

    .card p {
        color: #6b7280;
        margin-bottom: 15px;
    }

    .card a {
        display: inline-block;
        padding: 10px 16px;
        background: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: 0.3s;
    }

    .card a:hover {
        background: #1d4ed8;
    }

    /* =====================
    CHARTS GRID
    ===================== */
    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .chart-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    }

    .chart-card h3 {
        margin-bottom: 15px;
    }

    /* =====================
    RESPONSIVE
    ===================== */
    @media (max-width: 768px) {
        body {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            display: flex;
            justify-content: space-around;
        }

        .sidebar h2 {
            display: none;
        }
    }
</style>
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
                backgroundColor: ['#16a34a','#dc2626','#f59e0b']
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
