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
                                Last Login: {{ now()->format('d M Y, h:i A') }} IST
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATS CARDS --}}
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

        </section>

        @include('body.empFooter')
    </div>
</div>

@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
