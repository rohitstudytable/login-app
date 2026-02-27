@include('body.headerlink')

<body>

    <div class="">
        <div>
            @include('body.empHeader');

            <section class="myBodySection">

                {{-- ================= PAGE HEADER ================= --}}
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="d-flex mb-0 align-items-center">
                            <a href="/"><ion-icon name="home-outline" style="margin-bottom: -2px;"></ion-icon></a>
                            <span class="mx-2">/</span>
                            <p class="mb-0">Attendance Report</p>
                        </div>
                        <h2 class="text-dark fw-bold">Attendance Report</h2>
                        <p class="mb-0">View and analyze your comprehensive attendance records with advanced filtering options</p>
                    </div>
                </div>

                <div class="conWrepper">
                    <div class="myConSm">

                        {{-- ================= FILTER FORM ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="whiteBigCard">
                                    <h4 class="mb-3"><ion-icon name="filter-outline"></ion-icon> Filter Records</h4>

                                    <form method="GET" action="{{ route('empreport') }}" class="myForm">
                                        <div class="row">

                                        
                                        <div class="col-md-3">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Attendance Status</label>
                                            <select name="status" class="form-control">
                                                <option value="all">All Status</option>
                                                <option value="present" {{ request('status')=='present'?'selected':'' }}>Present</option>
                                                <option value="absent" {{ request('status')=='absent'?'selected':'' }}>Absent</option>
                                                <option value="half_day" {{ request('status')=='half_day'?'selected':'' }}>Half Day</option>
                                                <option value="paid_leave" {{ request('status')=='paid_leave'?'selected':'' }}>Leave Taken</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 d-flex gap-2 align-items-end">
                                            <button type="submit" class="myBtn myBtnPrimary" style="height: fit-content;">Search</button>
                                            <a href="{{ route('empreport') }}" class="myBtn btn-secondary" style="height: fit-content;">Reset</a>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                {{-- ================= ATTENDANCE CARDS ================= --}}
                    @php
                        $records = $attendances ?? collect();

                        $presentCount = $records->where('status', 'present')->count();
                        $halfDayCount = $records->where('status', 'half_day')->count();
                        $belowHalfDayCount = $records->where('status', 'below_half_day')->count();
                        $overtimeCount = $records->where('status', 'overtime')->count();
                        $absentCount = $records->where('status', 'absent')->count();
                        $paidLeaveCount = $records->where('status', 'paid_leave')->count();
                        $lateCheckinCheckoutCount = $records->where('status', 'late_checkin_checkout')->count();
                    @endphp

                    <div class="row mb-3">

                        {{-- PRESENT --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard present">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Present Days</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $presentCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- HALF DAY --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard half_day">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Half Days</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $halfDayCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="hourglass" class="text-warning"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BELOW HALF DAY --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard below_half_day">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Below Half Day</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $belowHalfDayCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="alert-circle-outline" class="text-orange"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- OVERTIME --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard overtime">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Overtime</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $overtimeCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="rocket-outline" class="text-purple"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ABSENT --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard absent">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Absent</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $absentCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="close-circle" class="text-danger"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAID LEAVE --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard paid_leave">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Paid Leave</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $paidLeaveCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="gift-outline" class="text-info"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- LATE CHECK-IN / CHECK-OUT --}}
                        <div class="col-md-3 mb-3">
                            <div class="myCard late_checkin_checkout">
                                <div class="perentCardFlex align-items-center">
                                    <div>
                                        <p class="mb-2">Late Check-in/Out</p>
                                        <h2 class="text-black mb-0 fw-bold">{{ $lateCheckinCheckoutCount }}</h2>
                                    </div>
                                    <div class="cardIcon">
                                        <ion-icon name="time-outline" class="text-secondary"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                {{-- END --}}

                {{-- ================= ATTENDANCE TABLE ================= --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="whiteBigCard">

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h4 class="mb-0">
                                        <ion-icon name="list-outline"></ion-icon> Attendance Records
                                    </h4>

                                    <div class="d-flex gap-2">
                                        <button class="myBtn myBtnPrimary mx-2">
                                            <ion-icon name="download-outline"></ion-icon> Export
                                        </button>

                                        <button class="myBtn">
                                            <ion-icon name="print-outline"></ion-icon> Print
                                        </button>
                                    </div>
                                </div>

                                <div class="mytableCon">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Clock In</th>
                                                <th>Clock Out</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($attendances ?? [] as $att)
                                                @php
                                                    // CLOCK TIMES
                                                    $in  = $att->in_time ? \Carbon\Carbon::parse($att->in_time) : null;
                                                    $out = $att->out_time ? \Carbon\Carbon::parse($att->out_time) : null;

                                                    // DURATION
                                                    $duration = '-';
                                                    if ($in && $out) {
                                                        $totalMinutes = $in->diffInMinutes($out);
                                                        $hours   = intdiv($totalMinutes, 60);
                                                        $minutes = $totalMinutes % 60;

                                                        $duration = ($hours ? $hours . ' hr ' : '') . $minutes . ' min';
                                                    }

                                                    // STATUS FROM DATABASE
                                                    $statusKey  = $att->status ?? 'absent';
                                                    $statusText = ucfirst(str_replace('_', ' ', $statusKey));

                                                    $badgeClass = match ($statusKey) {
                                                        'present'           => 'bg-success',
                                                        'present_early_checkout' => 'bg-warning text-dark',
                                                        'half_day'          => 'bg-warning text-dark',
                                                        'below_half_day'    => 'bg-danger',
                                                        'overtime'          => 'bg-primary',
                                                        default             => 'bg-danger',
                                                    };
                                                @endphp

                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                                    <td>{{ $in ? $in->format('h:i A') : '-' }}</td>
                                                    <td>{{ $out ? $out->format('h:i A') : '-' }}</td>
                                                    <td>{{ $duration }}</td>
                                                    <td>
                                                        <span class="badge {{ $badgeClass }}">
                                                            {{ $statusText }}
                                                        </span>
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        No attendance records found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                {{-- ================= END ================= --}}
                    </div>
                </div>

            </section>

            @include('body.empFooter')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
