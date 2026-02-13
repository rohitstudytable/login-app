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
                    <div class="myCard">
                        <div class="welcomeFlex">
                            <div>
                                <h3 class="text-dark mb-2">Welcome, {{ $intern->name }}</h3>

                                @if($intern->role === 'employee')
                                    <p>Employee ID: {{ $intern->intern_code }} | Daily Time Management Center</p>
                                @else
                                    <p>Intern ID: {{ $intern->intern_code }} | Daily Time Management Center</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CLOCK CARD --}}
            <div class="conWrepper mb-4">
                <div class="myConSm">
                    <div class="myCard primaryCard text-center clockCard mb-4">
                        <p class="mb-1"><ion-icon name="time"></ion-icon> Indian Standard Time (IST)</p>
                        <h2 class="mb-1" id="liveTime"></h2>
                        <script>
                            function updateTime() {
                                const now = new Date();
                                const timeString = now.toLocaleTimeString("en-IN", {
                                    timeZone: "Asia/Kolkata",
                                    hour: "2-digit",
                                    minute: "2-digit",
                                    second: "2-digit",
                                });
                                document.getElementById("liveTime").innerText = timeString;
                            }
                            updateTime();
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

                    {{-- ATTENDANCE STATS CARDS --}}
                    <div class="conWrepper">
                        <div class="myConSm">
                            <div class="row">
                                {{-- TOTAL DAYS --}}
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3 mt-3">
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
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

</body>
