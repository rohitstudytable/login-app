<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
    min-height:100vh;
    display:flex;
    color:#1f2937;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    background:linear-gradient(180deg,#3b82f6,#2563eb);
    color:white;
    padding:20px;
}
.sidebar h2{text-align:center;margin-bottom:30px}
.sidebar a{
    display:block;
    padding:12px 15px;
    margin-bottom:10px;
    border-radius:8px;
    text-decoration:none;
    color:white;
}
.sidebar a:hover{background:rgba(255,255,255,.15)}

.main{flex:1;display:flex;flex-direction:column}

/* TOPBAR */
.topbar{
    background:white;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
}

/* CONTENT */
.content{padding:25px}

/* FILTER */
.filter-form{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}
.filter-form input{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #ccc;
}
.filter-form button,.btn-reset{
    padding:6px 14px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
}
.btn-primary{background:#2563eb}
.btn-reset{background:#6b7280}

/* KPI */
.kpi-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:16px;
    margin:25px 0;
}
.kpi{
    background:white;
    padding:18px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}
.kpi h3{font-size:13px;color:#6b7280}
.kpi strong{font-size:26px}

/* CARDS */
.dashboard-grid{
    display:grid;
    grid-template-columns:2fr 3fr;
    gap:20px;
    margin-bottom:25px;
}
.card{
    background:white;
    border-radius:12px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}
th,td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}
th{background:#f1f5f9}

/* BUTTON */
.view-btn{
    background:#2563eb;
    color:white;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
}
</style>
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
    <div class="kpi"><h3>Attendance Rate</h3><strong>{{ $totalDays ? round(($presentCount/$totalDays)*100) : 0 }}%</strong></div>
    <div class="kpi"><h3>Present</h3><strong>{{ $presentCount }}</strong></div>
    <div class="kpi"><h3>Half Day</h3><strong>{{ $halfDayCount }}</strong></div>
    <div class="kpi"><h3>Absent</h3><strong>{{ $absentCount }}</strong></div>
    <div class="kpi"><h3>Total Days</h3><strong>{{ $totalDays }}</strong></div>
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
            <th>#</th><th>Intern</th><th>Present</th><th>Half Day</th><th>Absent</th><th>Total</th><th>Action</th>
        </tr>
        @foreach($internSummaries as $i => $intern)
        <tr>
            <td>{{ $i+1 }}</td>
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
        labels: ['Present','Half Day','Absent'],
        datasets: [{
            data: [{{ $presentCount }}, {{ $halfDayCount }}, {{ $absentCount }}],
            backgroundColor: ['#22c55e','#facc15','#ef4444']
        }]
    },
    options:{plugins:{legend:{position:'bottom'}}}
});

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($last7Dates ?? []) !!},
        datasets: [{
            label: 'Present',
            data: {!! json_encode($last7PresentCounts ?? []) !!},
            backgroundColor:'#2563eb'
        }]
    },
    options:{scales:{y:{beginAtZero:true}}}
});
</script>

</body>
</html>
