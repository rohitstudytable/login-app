@include('body.headerlink')

@php
    use Illuminate\Support\Facades\Auth;

    $intern = Auth::guard('intern')->user();
@endphp

<body>

    @if(!$intern)
        <div class="alert alert-danger m-4">
            Session expired. Please login again.
        </div>
    @else

        <div class="">
            <div>
                @include('body.empHeader')

                <section class="myBodySection">

                    {{-- WELCOME CARD --}}
                    <div class="conWrepper mb-4">
                        <div class="myConSm">
                            <div class="myCard primaryCard">
                                <div class="perentCardFlex">
                                    <div class="welcomeFlex">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                        <div>
                                            <h3>Welcome back, {{ $intern->name }}</h3>
                                            @if($intern->role === 'employee')
                                                <p>Employee ID: {{ $intern->intern_code }}</p>
                                            @else
                                                <p>Intern ID: {{ $intern->intern_code }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="cardDate">
                                            <h2 id="day"></h2>
                                            <p id="month"></p>
                                        </div>
                                        <script>
                                            const today = new Date();
                                            document.getElementById("day").innerText = today.getDate();
                                            document.getElementById("month").innerText =
                                                today.toLocaleString("en-IN", { month: "long" });
                                        </script>
                                    </div>
                                </div>

                                <div class="myCardFoot">
                                    <p>
                                        <ion-icon name="business-outline"></ion-icon>
                                        D&D Learning Pvt Ltd
                                    </p>
                                    <p>
                                        <ion-icon name="time-outline"></ion-icon>
                                        Last Login: {{ now()->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STATS CARDS --}}
                    <div class="conWrepper">
                        <div class="myConSm">
                            <div class="myGrid5 mb-4">

                                {{-- TOTAL DAYS --}}
                                <div>
                                    <div class="myCard total">
                                        <div class="perentCardFlex align-items-center">
                                            <div>
                                                <p class="mb-2">Total Days</p>
                                                <h2 class="fw-bold mb-0">{{ $totalDays }}</h2>
                                            </div>
                                            <div class="cardIcon">
                                                <ion-icon name="calendar-outline" class="text-primary"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PRESENT --}}
                                <div>
                                    <div class="myCard present">
                                        <div class="perentCardFlex align-items-center">
                                            <div>
                                                <p class="mb-2">Present Days</p>
                                                <h2 class="fw-bold mb-0">{{ $presentCount }}</h2>
                                            </div>
                                            <div class="cardIcon">
                                                <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ABSENT --}}
                                <div>
                                    <div class="myCard absent">
                                        <div class="perentCardFlex align-items-center">
                                            <div>
                                                <p class="mb-2">Absent Days</p>
                                                <h2 class="fw-bold mb-0">{{ $absentCount }}</h2>
                                            </div>
                                            <div class="cardIcon">
                                                <ion-icon name="close-circle" class="text-danger"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- HALF DAY --}}
                                <div>
                                    <div class="myCard half_day">
                                        <div class="perentCardFlex align-items-center">
                                            <div>
                                                <p class="mb-2">Half Days</p>
                                                <h2 class="fw-bold mb-0">{{ $halfDayCount }}</h2>
                                            </div>
                                            <div class="cardIcon">
                                                <ion-icon name="hourglass" class="text-warning"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PAID LEAVE --}}
                                <div>
                                    <div class="myCard paid_leave">
                                        <div class="perentCardFlex align-items-center">
                                            <div>
                                                <p class="mb-2">Leave Taken</p>
                                                <h2 class="fw-bold mb-0">{{ $paidLeaveCount }}</h2>
                                            </div>
                                            <div class="cardIcon">
                                                <ion-icon name="calendar" class="text-info"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>




                            <div class="row">
                                <div class="col-md-8">
                                    <div class="whiteBigCard">
                                        <h4 class="mb-3">
                                            <ion-icon name="flash-outline"></ion-icon>
                                            Quick Actions
                                        </h4>
                                        <div class="quickActionGrid">
                                            <a href="/empattendance" class="quickAction">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <ion-icon name="time-outline"
                                                            class="me-3 text-primary leftIcon"></ion-icon>
                                                        <div>
                                                            <h6 class="mb-1">Clock In/Out</h6>
                                                            <p class="mb-0">Record your daily attendance</p>
                                                        </div>
                                                    </div>
                                                    <ion-icon name="chevron-forward-outline"></ion-icon>
                                                </div>
                                            </a>
                                            <a href="/empreport" class="quickAction">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <ion-icon name="calendar-clear-outline"
                                                            class="me-3 text-success leftIcon"></ion-icon>
                                                        <div>
                                                            <h6 class="mb-1">View Attendance</h6>
                                                            <p class="mb-0">Check your attendance report</p>
                                                        </div>
                                                    </div>
                                                    <ion-icon name="chevron-forward-outline"></ion-icon>
                                                </div>
                                            </a>
                                            <a href="/empprofile" class="quickAction">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <ion-icon name="person-outline"
                                                            class="me-3 text-info leftIcon"></ion-icon>
                                                        <div>
                                                            <h6 class="mb-1">Update Profile</h6>
                                                            <p class="mb-0">Manage your personal information</p>
                                                        </div>
                                                    </div>
                                                    <ion-icon name="chevron-forward-outline"></ion-icon>
                                                </div>
                                            </a>
                                            <a href="#" class="quickAction">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <ion-icon name="bag-remove-outline"
                                                            class="me-3 text-warning leftIcon"></ion-icon>
                                                        <div>
                                                            <h6 class="mb-1">Leave Taken</h6>
                                                            <p class="mb-0">Manage your CL & PL</p>
                                                        </div>
                                                    </div>
                                                    <ion-icon name="chevron-forward-outline"></ion-icon>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="weatherCard" id="weatherCard">
                                        Fetching your weather...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

                @include('body.empFooter')
            </div>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>




    <script>
        async function loadAgartalaWeather() {

            // 📍 Fixed Location: Agartala, Tripura
            const lat = 23.8315;
            const lon = 91.2868;

            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&hourly=relativehumidity_2m`;

            try {
                const res = await fetch(url);
                const data = await res.json();

                const temp = data.current_weather.temperature;
                const wind = data.current_weather.windspeed;
                const humidity = data.hourly.relativehumidity_2m[0];

                document.getElementById("weatherCard").innerHTML = `
                <h1 style="font-size:55px; margin:15px 0;">
                    ${temp}°C
                </h1>

                <p style="margin:0;">Live Weather Update</p>

                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <div>
                        <small>Humidity</small>
                        <h3>${humidity}%</h3>
                    </div>

                    <div>
                        <small>Wind</small>
                        <h3>${wind} km/h</h3>
                    </div>
                </div>
            `;
            }
            catch (err) {
                document.getElementById("weatherCard").innerHTML =
                    "❌ Weather API Error!";
            }
        }

        // Auto Load Agartala Weather
        loadAgartalaWeather();
    </script>



</body>