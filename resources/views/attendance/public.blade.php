@include('body.headerlink')

<style>
body {
    background:#f4f6f9;
    font-family: Arial, sans-serif;
}

/* CARD */
.card {
    border-radius:12px;
    box-shadow:0 8px 18px rgba(0,0,0,.08);
}

/* TABS */
.tabs {
    display:flex;
    justify-content:center;
    gap:10px;
    margin-bottom:20px;
}

.tab-btn {
    padding:10px 20px;
    border:none;
    border-radius:8px;
    background:#e5e7eb;
    cursor:pointer;
    font-weight:600;
}

.tab-btn.active {
    background:#2563eb;
    color:white;
}

.tab-content {
    display:none;
}

.tab-content.active {
    display:block;
}

/* REPORT STYLES */
.stats {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:15px;
    margin-bottom:20px;
}

.stat-box {
    padding:15px;
    border-radius:10px;
    text-align:center;
    font-weight:600;
}

.total { background:#e0e7ff; color:#1e40af; }
.present { background:#dcfce7; color:#166534; }
.absent { background:#fee2e2; color:#991b1b; }
.half_day { background:#fef3c7; color:#92400e; }
.paid_leave { background:#e0f2fe; color:#075985; }

table {
    width:100%;
    border-collapse:collapse;
}

th, td {
    padding:10px;
    border-bottom:1px solid #e5e7eb;
}

th {
    background:#f1f5f9;
}

.badge {
    padding:4px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.badge.present { background:#dcfce7; color:#166534; }
.badge.absent { background:#fee2e2; color:#991b1b; }
.badge.half_day { background:#fef3c7; color:#92400e; }
.badge.paid_leave { background:#e0f2fe; color:#075985; }
.pageContent{
    min-height: 80vh;
}

  /* FILTER */
        .filter-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-row label {
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        /* STATS */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            padding: 14px;
            text-align: center;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            line-height: 1.4;
        }

        .stat-box.total { background: #1e40af; }
        .stat-box.present { background: #16a34a; }
        .stat-box.absent { background: #dc2626; }
        .stat-box.half_day { background: #f59e0b; }
        .stat-box.paid_leave { background: #6366f1; }

        /* TABLE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 14px;
            white-space: nowrap;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: #fff;
        }

        .badge.present { background: #16a34a; }
        .badge.absent { background: #dc2626; }
        .badge.half_day { background: #f59e0b; }
        .badge.paid_leave { background: #6366f1; }

        /* MOBILE */
        @media (max-width: 640px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-row button,
            .filter-row a {
                width: 100%;
            }
        }
</style>

<body>

<div class="container vh-100 d-flex flex-column justify-content-center">

    <h3 class="text-center mb-3">
        {{ $intern->name }} – Attendance Portal
    </h3>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab-btn active" onclick="openTab('attendanceTab', this)">Attendance</button>
        <button class="tab-btn" onclick="openTab('reportTab', this)">Reports</button>
    </div>
<div class="pageContent">
 <!-- TAB 1 : ATTENDANCE -->
    <div id="attendanceTab" class="tab-content active">
        <div class="card mx-auto" style="max-width:520px;">
            <div class="card-header bg-primary text-white text-center">
                Mark Attendance
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('attendance.publicStoreByToken') }}">
                    @csrf

                    <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="mb-3">
                        <label>Employee Name</label>
                        <input class="form-control" value="{{ $intern->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input class="form-control" value="{{ $date }}" readonly>
                    </div>

                    <div class="">
                        {{-- <label>Location</label> --}}
                        <input type="hidden" class="form-control" name="location" id="location" readonly required>
                    </div>

                    <div class="mb-3">
                        <label>In Time</label>
                        <input  class="form-control" id="in_time" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Out Time</label>
                        <input  class="form-control" id="out_time" readonly>
                    </div>

                    {{-- <div class="text-center mb-3">
                        <span class="badge bg-success p-2">Status: Present</span>
                    </div> --}}

                    <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                        Fetching location...
                    </button>

                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2 : REPORT -->
<div id="reportTab" class="tab-content">
    {{-- ================== DATE FILTER ================== --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="">
                <div class="filter-row">

                    <div>
                        <label>Select Date</label>
                        <input type="date" name="date"
                               value="{{ request('date') }}">
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ url()->current() }}" class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ================== STATS ================== --}}
    <div class="stats">
        <div class="stat-box total">
            Total<br>{{ $totalDays ?? 0 }}
        </div>
        <div class="stat-box present">
            Present<br>{{ $presentCount ?? 0 }}
        </div>
        <div class="stat-box absent">
            Absent<br>{{ $absentCount ?? 0 }}
        </div>
        <div class="stat-box half_day">
            Half Day<br>{{ $halfDayCount ?? 0 }}
        </div>
        <div class="stat-box paid_leave">
            Paid Leave<br>{{ $paidLeaveCount ?? 0 }}
        </div>
    </div>

    {{-- ================== ATTENDANCE TABLE ================== --}}
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Attendance History</h5>

            <div class="table-responsive">
                <table>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                    </tr>

                    @forelse($attendances ?? [] as $i => $attendance)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>
                                {{ $attendance->date
                                    ? $attendance->date->format('Y-m-d')
                                    : '-' }}
                            </td>

                            <td>
                                <span class="badge {{ $attendance->status }}">
                                    {{ ucfirst(str_replace('_',' ',$attendance->status)) }}
                                </span>
                            </td>

                            <td>{{ $attendance->location ?? '-' }}</td>

                            <td>
                                {{ $attendance->in_time
                                    ? \Carbon\Carbon::parse($attendance->in_time)->format('H:i')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $attendance->out_time
                                    ? \Carbon\Carbon::parse($attendance->out_time)->format('H:i')
                                    : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>

        </div>
    </div>

</div>

</div>
   

</div>

<script>
function openTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function () {

    const locationInput = document.getElementById('location');
    const submitBtn     = document.getElementById('submitBtn');
    const inTimeInput   = document.getElementById('in_time');
    const outTimeInput  = document.getElementById('out_time');

    // ✅ Attendance state FROM BACKEND
    const hasInTime  = @json($todayAttendance && $todayAttendance->in_time);
    const hasOutTime = @json($todayAttendance && $todayAttendance->out_time);

    // ✅ Decide button state
    if (!hasInTime) {
        submitBtn.innerText = 'Punch In';
    } else if (hasInTime && !hasOutTime) {
        submitBtn.innerText = 'Punch Out';
        inTimeInput.value = "{{ $todayAttendance->in_time ?? '' }}";
    } else {
        submitBtn.innerText = 'Attendance Completed';
        submitBtn.disabled = true;
        return;
    }

    submitBtn.disabled = true;

    // Show current time (display only)
    const now = new Date();
    const currentTime = now.toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });

    if (!hasInTime) {
        inTimeInput.value = currentTime;
    }

    if (!navigator.geolocation) {
        locationInput.value = 'Geolocation not supported';
        submitBtn.disabled = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async function (position) {

            const lat = position.coords.latitude.toFixed(6);
            const lon = position.coords.longitude.toFixed(6);

            // Fallback first
            locationInput.value = `${lat}, ${lon}`;
            submitBtn.disabled = false;

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'User-Agent': 'AttendanceApp/1.0'
                        }
                    }
                );

                const data = await response.json();

                if (data.address) {
                    let place =
                        data.address.suburb ||
                        data.address.village ||
                        data.address.town ||
                        data.address.city ||
                        '';

                    let state = data.address.state || '';
                    let country = data.address.country || '';

                    const fullLocation = `${place}, ${state}, ${country}`
                        .replace(/^,|,$/g, '')
                        .trim();

                    if (fullLocation) {
                        locationInput.value = fullLocation;
                    }
                }

            } catch (e) {
                console.warn('Reverse geocoding failed');
            }
        },
        function () {
            alert('Location permission is required');
        },
        {
            enableHighAccuracy: false,
            timeout: 7000,
            maximumAge: 60000
        }
    );
});
</script>


</body>
<!-- LOGOUT BUTTON -->
<div style="position:fixed; top:15px; right:15px; z-index:1000;">
    <form method="POST" action="{{ route('intern.logout') }}">
        @csrf
        <button type="submit"
                style="background:#dc2626;color:white;padding:8px 14px;border:none;border-radius:6px;cursor:pointer;">
            Logout
        </button>
    </form>
</div>

</html>
