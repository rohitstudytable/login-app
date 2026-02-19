@include('body.headerlink')

@php
    use Illuminate\Support\Facades\Auth;

    $intern = Auth::guard('intern')->user();
@endphp

<body>

    <div class="">
        <!-- <div class="sidebar">sidebar</div> -->
        <div>
            @include('body.empHeader')

            {{----------------- INTER CARD -------------------------}}
                <section class="myBodySection">
                    <div class="conWrepper mb-4">
                            <div class="myConSm">
                                <div class="myCard">
                                    <div class="welcomeFlex">
                                        <div>
                                            <h3 class="text-dark mb-2">
                                                Welcome, {{ $intern->name }}
                                            </h3>

                                            @if($intern->role === 'employee')
                                                <p>Employee ID: {{ $intern->employee_code }} | Daily Time Management Center</p>
                                            @else
                                                <p>Intern ID: {{ $intern->intern_code }} | Daily Time Management Center</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
            {{-- ---------------END -------------------------}}


            {{---------------CLOCK RUNNING ------------------------------------}}
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="myCard primaryCard text-center clockCard mb-4">
                            <p class="mb-1"><ion-icon name="time"></ion-icon> Indian Standard Time (IST)</p>
                            <h2 class="mb-1" id="liveTime"></h2>
                            <script>
                                function updateTime() {
                                    const now = new Date();

                                    // Format time for Asia/Kolkata
                                    const timeString = now.toLocaleTimeString("en-IN", {
                                        timeZone: "Asia/Kolkata",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        second: "2-digit",
                                    });

                                    document.getElementById("liveTime").innerText = timeString;
                                }

                                // Run immediately
                                updateTime();

                                // Update every second
                                setInterval(updateTime, 1000);

                            </script>
                            <h6 class="mb-0" id="todayDate"></h6>
                            <script>
                                function showTodayDate() {
                                    const today = new Date();

                                    const formattedDate = today.toLocaleDateString("en-IN", {
                                        timeZone: "Asia/Kolkata",
                                        weekday: "long",
                                        day: "2-digit",
                                        month: "long",
                                        year: "numeric",
                                    });

                                    document.getElementById("todayDate").innerText = formattedDate;
                                }

                                showTodayDate();

                            </script>

                        </div>

            {{-- -----------END --------------------------}}


            {{----------------------- CHECK IN CHECK OUT ----------------------------- --}}

                    @php
                        $clockInDone = $todayAttendance && $todayAttendance->in_time;
                        $clockOutDone = $todayAttendance && $todayAttendance->out_time;
                    @endphp

                    <!-- mark attendance alert toaster -->
                    <div class="myTost tostSuccess mb-4" style="display:none;" id="attendanceToaster">
                        <ion-icon name="checkmark-circle" class="text-success me-2"></ion-icon>
                        <div>
                            <p class="text-success mb-0" id="toasterMessage">Action Successful</p>
                            <p class="mb-0" id="toasterTime">--:--:--</p>
                        </div>
                    </div>

                    <!-- clock in clock out Buttons -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            {{-- IF today the user not check in keep this enable & after check in keep the disable class till next day --}}
                            <button 
                                class="clockBtn clockIn" 
                                id="btnClockIn"
                                {{ $clockInDone ? 'disabled' : '' }}>
                                <ion-icon name="arrow-forward-circle"></ion-icon>
                                Clock In
                            </button>
                        </div>
                        <div class="col-md-6">
                            {{-- till the user not clock in keep it class dissable and after check in enable it and after check out again disable it till next day --}}
                            <button 
                                class="clockBtn clockOut" 
                                id="btnClockOut"
                                {{ !$clockInDone || $clockOutDone ? 'disabled' : '' }}>
                                <ion-icon name="arrow-back-circle"></ion-icon>
                                Clock Out
                            </button>
                        </div>
                    </div>

                    <!-- last action -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="lastActionCard text-center" id="lastActionCard">
                                <p class="mb-1">Last Action</p>
                                <p class="text-dark mb-0" style="font-size: 16px">
                                    @if($clockOutDone)
                                        Clocked Out at {{ \Carbon\Carbon::parse($todayAttendance->out_time)->format('h:i:s a') }}
                                    @elseif($clockInDone)
                                        Clocked In at {{ \Carbon\Carbon::parse($todayAttendance->in_time)->format('h:i:s a') }}
                                    @else
                                        No action yet
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <script>
                        const clockInBtn = document.getElementById('btnClockIn');
                        const clockOutBtn = document.getElementById('btnClockOut');
                        const toaster = document.getElementById('attendanceToaster');
                        const toasterMessage = document.getElementById('toasterMessage');
                        const toasterTime = document.getElementById('toasterTime');
                        const lastActionCard = document.getElementById('lastActionCard');

                        function showToaster(message, time) {
                            toasterMessage.innerText = message;
                            toasterTime.innerText = time;
                            toaster.style.display = 'flex';
                            setTimeout(() => toaster.style.display = 'none', 3000);
                        }

                        function updateLastAction(message, time) {
                            lastActionCard.querySelector('p.text-dark').innerText = `${message} at ${time}`;
                        }

                        async function getLocationName(lat, lon) {
                            try {
                                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
                                const data = await response.json();
                                return data.display_name || `${lat}, ${lon}`;
                            } catch (err) {
                                console.error('Reverse geocoding failed:', err);
                                return `${lat}, ${lon}`;
                            }
                        }

                        async function markAttendance(action) {
                            // Disable the button immediately
                            if (action === 'in') clockInBtn.disabled = true;
                            else clockOutBtn.disabled = true;

                            if (!navigator.geolocation) {
                                alert('Geolocation is not supported by your browser.');
                                if (action === 'in') clockInBtn.disabled = false;
                                else clockOutBtn.disabled = false;
                                return;
                            }

                            navigator.geolocation.getCurrentPosition(async position => {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;
                                const locationName = await getLocationName(lat, lon);

                                fetch('{{ route("attendance.publicStoreByToken") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        intern_id: {{ $intern->id }},
                                        date: '{{ $date }}',
                                        action: action,
                                        location: locationName
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        const actionName = data.action === 'in' ? 'Clocked In' : 'Clocked Out';
                                        showToaster(actionName, data.time);
                                        updateLastAction(actionName, data.time);

                                        // Update buttons properly
                                        if (data.action === 'in') {
                                            clockInBtn.disabled = true;
                                            clockOutBtn.disabled = false;
                                        } else {
                                            clockOutBtn.disabled = true;
                                            clockInBtn.disabled = true;
                                        }
                                    } else {
                                        alert(data.error || 'Something went wrong');
                                        // Re-enable button if failed
                                        if (action === 'in') clockInBtn.disabled = false;
                                        else clockOutBtn.disabled = false;
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    if (action === 'in') clockInBtn.disabled = false;
                                    else clockOutBtn.disabled = false;
                                });
                            }, error => {
                                alert('Unable to get your location. Please allow location access.');
                                if (action === 'in') clockInBtn.disabled = false;
                                else clockOutBtn.disabled = false;
                            });
                        }

                        clockInBtn?.addEventListener('click', () => markAttendance('in'));
                        clockOutBtn?.addEventListener('click', () => markAttendance('out'));
                    </script>

            {{---------------------------- END ---------------------------}}

                        <div class="row mb-4">
                            <div class="col-md-6">

                            {{-- ----------------- LOCATION ------------------------- --}}

                                <div class="whiteBigCard h-100">
                                    <h4 class="mb-3">
                                        <ion-icon name="location-outline"></ion-icon> Location Verification
                                    </h4>

                                    {{-- VERIFIED (HIDDEN INITIALLY) --}}
                                    <p class="locationVerify mb-2 text-success d-none" id="locationVerified">
                                        <ion-icon name="checkmark-circle"></ion-icon>
                                        Location Verified
                                    </p>

                                    {{-- FETCHING (VISIBLE INITIALLY) --}}
                                    <p class="locationVerify mb-2 text-danger" id="locationFetching">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Location Fetching...
                                    </p>

                                    <div class="myCard2 d-none" id="locationCard">
                                        <div class="card2Flex mb-2">
                                            <ion-icon name="business-outline"></ion-icon>
                                            <div>
                                                <h6 class="mb-0" id="locationAddress">Fetching address...</h6>
                                                <p class="mb-0" id="locationCity">---</p>
                                            </div>
                                        </div>

                                        <div class="card2Flex">
                                            <ion-icon name="globe-outline"></ion-icon>
                                            <p class="mb-0" id="locationCountry">---</p>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    /* ===== GLOBAL (USED BY ATTENDANCE SCRIPT) ===== */
                                    let userLocation = null;
                                    let userLat = null;
                                    let userLng = null;
                                    /* ============================================== */

                                    const fetchingEl = document.getElementById('locationFetching');
                                    const verifiedEl = document.getElementById('locationVerified');
                                    const cardEl = document.getElementById('locationCard');

                                    const addressEl = document.getElementById('locationAddress');
                                    const cityEl = document.getElementById('locationCity');
                                    const countryEl = document.getElementById('locationCountry');

                                    function showFetching() {
                                        fetchingEl.classList.remove('d-none');
                                        verifiedEl.classList.add('d-none');
                                        cardEl.classList.add('d-none');
                                    }

                                    function showVerified(address, city, country) {
                                        fetchingEl.classList.add('d-none');
                                        verifiedEl.classList.remove('d-none');
                                        cardEl.classList.remove('d-none');

                                        addressEl.innerText = address;
                                        cityEl.innerText = city;
                                        countryEl.innerText = country;

                                        userLocation = `${address}, ${city}, ${country}`;
                                    }

                                    function showLocationError(msg) {
                                        fetchingEl.innerText = msg;
                                    }

                                    /* ===== MAIN LOCATION FUNCTION ===== */
                                    function fetchLocation() {

                                        if (!navigator.geolocation) {
                                            showLocationError('Geolocation not supported');
                                            return;
                                        }

                                        showFetching();

                                        navigator.geolocation.getCurrentPosition(
                                            position => {

                                                userLat = position.coords.latitude;
                                                userLng = position.coords.longitude;

                                                /* ✅ IMMEDIATE FALLBACK (IMPORTANT) */
                                                userLocation = `Lat: ${userLat}, Lng: ${userLng}`;

                                                /* Stop infinite loading immediately */
                                                showVerified(
                                                    'Current GPS Location',
                                                    `Lat ${userLat.toFixed(5)}`,
                                                    `Lng ${userLng.toFixed(5)}`
                                                );

                                                /* OPTIONAL: Reverse geocoding (NON-BLOCKING) */
                                                fetch(
                                                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}`,
                                                    {
                                                        headers: {
                                                            'Accept': 'application/json',
                                                            'User-Agent': 'AttendanceApp/1.0'
                                                        }
                                                    }
                                                )
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data?.display_name) {
                                                        const city =
                                                            data.address.city ||
                                                            data.address.town ||
                                                            data.address.village ||
                                                            'Unknown City';

                                                        const country = data.address.country || 'Unknown Country';

                                                        showVerified(data.display_name, city, country);
                                                    }
                                                })
                                                .catch(() => {
                                                    /* silently fail – fallback already set */
                                                });
                                            },
                                            error => {
                                                showLocationError('Location permission denied');
                                            },
                                            {
                                                enableHighAccuracy: true,
                                                timeout: 15000,
                                                maximumAge: 0
                                            }
                                        );
                                    }

                                    fetchLocation();
                                </script>

                            {{------------- END -----------------------}}

                            {{------------ TABLE CLOCK IN ----------}}
                                </div>
                                <div class="col-md-6">
                                    @php
                                    use Carbon\Carbon;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | REQUIRED VARIABLES (from controller)
                                    |--------------------------------------------------------------------------
                                    | $attendance  -> today's attendance record OR null
                                    | $shift       -> shift details (start_time, end_time, break_minutes)
                                    */

                                    /* ===== SAFETY FALLBACKS ===== */
                                    $attendance = $attendance ?? null;

                                    $shift = $shift ?? (object)[
                                        'start_time'    => '10:00',
                                        'end_time'      => '18:00',
                                        'break_minutes' => 45
                                    ];

                                    /* ===== SHIFT STATUS ===== */
                                    $isPresent = $attendance && $attendance->in_time;

                                    /* ===== START TIME ===== */
                                    $clockInTime = ($attendance && $attendance->in_time)
                                        ? Carbon::parse($attendance->in_time)->format('h:i A')
                                        : '— —';

                                    /* ===== END TIME ===== */
                                    $clockOutTime = ($attendance && $attendance->out_time)
                                        ? Carbon::parse($attendance->out_time)->format('h:i A')
                                        : '— —';

                                    /* ===== EXPECTED HOURS ===== */
                                    $expectedHours = Carbon::parse($shift->start_time)
                                        ->diffInHours(Carbon::parse($shift->end_time));

                                    /* ===== WORKING HOURS ===== */
                                    if ($attendance && $attendance->in_time && $attendance->out_time) {

                                        $workedMinutes = Carbon::parse($attendance->in_time)
                                            ->diffInMinutes(Carbon::parse($attendance->out_time));

                                        // subtract break safely
                                        $workedMinutes = max(0, $workedMinutes - $shift->break_minutes);

                                        $workingHours = round($workedMinutes / 60, 2) . ' hours';

                                    } else {
                                        $workingHours = '— —';
                                    }
                                    @endphp


                                    <div class="whiteBigCard">
                                        <h4 class="mb-3">
                                            <ion-icon name="calendar-outline"></ion-icon>
                                            Today's Shift Schedule
                                        </h4>

                                        <div class="myCard2 myCard2Outline mb-3">
                                            <p class="mb-2" style="color: #1E40AF">
                                                Shift Type
                                            </p>

                                            <h5 class="mb-0 text-dark fw-semibold">
                                                <!-- {{ $isPresent ? 'Present (Regular Day Shift)' : 'Absent (Regular Day Shift)' }} -->
                                                 @if($workingHours >= 7.75 && $workingHours <= 8.10)
    Present (Regular Day Shift)

@elseif($workingHours >= 7 && $workingHours < 7.75)
    Present (Early Checkout / Late Check-in)

@elseif($workingHours >= 4 && $workingHours < 7)
    Half Day

@elseif($workingHours < 4)
    Below Half Day

@elseif($workingHours > 8.10)

   Present (Overtime)
    

@endif


                                            </h5>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="myCard2">
                                                    <div class="card2Flex mb-2">
                                                        <ion-icon name="arrow-forward-circle-outline" class="text-success"></ion-icon>
                                                        <p class="mb-0">Start Time</p>
                                                    </div>

                                                    <h5 class="mb-0 text-dark fw-semibold">
                                                        {{ $clockInTime }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="myCard2">
                                                    <div class="card2Flex mb-2">
                                                        <ion-icon name="arrow-back-circle-outline" class="text-danger"></ion-icon>
                                                        <p class="mb-0">End Time</p>
                                                    </div>

                                                    <h5 class="mb-0 text-dark fw-semibold">
                                                        {{ $clockOutTime }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="lineFlex">
                                            <span>Expected Hours</span>
                                            <span class="text-dark">{{ $expectedHours }} hours</span>
                                        </p>

                                        <p class="lineFlex">
                                            <span>Break Time</span>
                                            <span class="text-dark">{{ $shift->break_minutes }} minutes</span>
                                        </p>

                                        <p class="lineFlex">
                                            <span>Working Hours</span>
                                            <span class="text-dark">{{ $workingHours }}</span>
                                        </p>
                                    </div>
                            {{------------ END ----------------}}


                            </div>
                        </div>

                        <div class="row mb-4">

                        {{-------------- ATTENDANCE HISTORY  ----------------------------}}
                            <div class="col-md-12">
                                <div class="whiteBigCard">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h4 class="mb-0">
                                            <ion-icon name="time-outline"></ion-icon> Recent Clock History
                                        </h4>
                                        <p class="mb-0 sm">Last 7 days</p>
                                    </div>

                                    @forelse($recentAttendances as $record)
                                        @php
                                            // CLOCK IN
                                            $clockIn = $record->in_time
                                                ? \Carbon\Carbon::parse($record->in_time)->format('h:i a')
                                                : '-- --';

                                            // CLOCK OUT
                                            $clockOut = $record->out_time
                                                ? \Carbon\Carbon::parse($record->out_time)->format('h:i a')
                                                : '-- --';

                                            // DATE
                                            $date = $record->date
                                                ? \Carbon\Carbon::parse($record->date)->format('d M Y')
                                                : \Carbon\Carbon::parse($record->created_at)->format('d M Y');

                                            // DURATION
                                            if ($record->in_time && $record->out_time) {
                                                $minutes = \Carbon\Carbon::parse($record->in_time)
                                                    ->diffInMinutes(\Carbon\Carbon::parse($record->out_time));

                                                $hours = floor($minutes / 60);
                                                $mins = $minutes % 60;

                                                $duration = $hours . 'h ' . $mins . ' m';
                                            } else {
                                                $duration = '-- --';
                                            }

                                            // STATUS COLOR
                                            $status = strtolower($record->status ?? 'absent');

                                            $statusClass = match($status) {
                                                'present' => 'text-success',
                                                'half_day', 'half day', 'halfday' => 'text-warning',
                                                default => 'text-danger'
                                            };
                                        @endphp

                                        <div class="myCard2 mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <p class="mb-0">{{ $date }}</p>
                                                <p class="{{ $statusClass }} mb-0">
                                                    {{ ucfirst(str_replace('_',' ', $record->status ?? 'Absent')) }}
                                                </p>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="mb-1 sm">Clock In</p>
                                                    <p class="mb-0 text-dark">{{ $clockIn }}</p>
                                                </div>

                                                <div class="col-4">
                                                    <p class="mb-1 sm">Clock Out</p>
                                                    <p class="mb-0 text-dark">{{ $clockOut }}</p>
                                                </div>

                                                <div class="col-4">
                                                    <p class="mb-1 sm">Duration</p>
                                                    <p class="mb-0 text-dark">{{ $duration }}</p>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <div class="text-center py-4">
                                            <p class="mb-0 text-muted">No attendance records found</p>
                                        </div>
                                    @endforelse

                                </div>
                            </div>


                        {{--------------- END  -----------------------------}}
                        </div>
                    </div>

                </div>
        </div>

        </section>
        @include('body.empFooter')
    </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

</body>
