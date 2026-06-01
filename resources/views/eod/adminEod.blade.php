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
            <h2>EOD Reports Management</h2>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            {{-- FILTER --}}
            <div class="card card-filter">
                <div class="card-title">
                    <div class="card-icon bg-indigo">
                        <ion-icon name="funnel-outline"></ion-icon>
                    </div>
                    <h3>Filter EOD Reports</h3>
                </div>

                <form method="GET" action="{{ route('admin.eod.index') }}" class="filter-form">

                    <select name="intern_id">
                        <option value="">All Employees</option>

                        @foreach ($interns as $intern)
                            <option value="{{ $intern->id }}"
                                {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                {{ $intern->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="report_date" value="{{ request('report_date') }}">

                    <button class="btn btn-primary">
                        <ion-icon name="search-outline"></ion-icon>
                        Search
                    </button>

                    <a href="{{ route('admin.eod.index') }}" class="btn btn-reset">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Reset
                    </a>

                </form>
            </div>

            {{-- KPI CARDS --}}
            <div class="kpi-grid">

                <div class="kpi kpi-green">
                    <div class="kpi-icon">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <div>
                        <h3>Total EOD Reports</h3>
                        <strong>{{ $totalReports }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-indigo">
                    <div class="kpi-icon">
                        <ion-icon name="today-outline"></ion-icon>
                    </div>
                    <div>
                        <h3>Today's Reports</h3>
                        <strong>{{ $todayReports }}</strong>
                    </div>
                </div>

                <div class="kpi kpi-red">
                    <div class="kpi-icon">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                    </div>
                    <div>
                        <h3>Pending Today</h3>
                        <strong>{{ $pendingReports }}</strong>
                    </div>
                </div>

            </div>

            {{-- EOD TABLE --}}
            <div class="card card-summary">

                <div class="d-flex align-items-center justify-content-between mb-3">

                    <div class="card-title">
                        <div class="card-icon bg-amber">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </div>
                        <h3>Employee EOD Reports</h3>
                    </div>

                    <button class="btn btn-primary excelBtn">
                        <ion-icon name="download-outline"></ion-icon>
                        Export
                    </button>

                </div>

                <table class="excelTable">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($reports as $key => $report)
                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>{{ $report->intern->name ?? 'N/A' }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $report->created_at->format('d M Y h:i A') }}
                                </td>

                                <td>

                                    <button type="button" class="icon-btn view-btn eodViewBtn"
                                        data-name="{{ $report->intern->name ?? 'N/A' }}"
                                        data-date="{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}"
                                        data-tasks="{{ $report->tasks_completed }}"
                                        data-challenges="{{ $report->challenges_faced }}"
                                        data-plan="{{ $report->plan_for_tomorrow }}" data-bs-toggle="modal"
                                        data-bs-target="#eodModal">

                                        <ion-icon name="eye-outline"></ion-icon>

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    No EOD Reports Found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- EOD MODAL --}}
    <div class="modal fade" id="eodModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Employee EOD Report
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <strong>Employee</strong>
                        <p id="modalEmployee"></p>
                    </div>

                    <div class="mb-3">
                        <strong>Date</strong>
                        <p id="modalDate"></p>
                    </div>

                    <div class="mb-3">
                        <strong>EOD</strong>
                        <div class="border rounded p-3 bg-light" id="modalTasks"></div>
                    </div>

                    {{-- <div class="mb-3">
                        <strong>Challenges Faced</strong>
                        <div class="border rounded p-3 bg-light" id="modalChallenges"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Plan For Tomorrow</strong>
                        <div class="border rounded p-3 bg-light" id="modalPlan"></div>
                    </div> --}}

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        document.querySelector(".excelBtn").addEventListener("click", function() {

            let table = document.querySelector(".excelTable");

            let workbook = XLSX.utils.table_to_book(table, {
                sheet: "EOD Reports"
            });

            XLSX.writeFile(workbook, "Employee_EOD_Reports.xlsx");

        });

        document.querySelectorAll('.eodViewBtn').forEach(button => {

            button.addEventListener('click', function() {

                document.getElementById('modalEmployee').innerText =
                    this.dataset.name;

                document.getElementById('modalDate').innerText =
                    this.dataset.date;

                document.getElementById('modalTasks').innerText =
                    this.dataset.tasks || '-';

                document.getElementById('modalChallenges').innerText =
                    this.dataset.challenges || '-';

                document.getElementById('modalPlan').innerText =
                    this.dataset.plan || '-';

            });

        });
    </script>

</body>

</html>
