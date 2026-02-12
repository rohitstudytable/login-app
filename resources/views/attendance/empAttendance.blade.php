@include('body.headerlink')



<body>

    <div class="">
        <!-- <div class="sidebar">sidebar</div> -->
        <div>
            @include('body.empHeader')
            <section class="myBodySection">
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="myCard">
                            <div class="welcomeFlex">

                                <div>
                                    <h3 class="text-dark mb-2">Welcome, Rohit Acharjee</h3>
                                    <!-- if user is emp -->
                                    <p>Employee ID: D&D-014 | Daily Time Management Center</p>

                                    <!-- if user is intern -->
                                    <!-- <p>Intern ID: Intern-014 | Daily Time Management Center</p> -->
                                </div>
                            </div>



                        </div>
                    </div>
                </div>





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

                        <!-- mark attendance alert toaster -->
                        <div class="myTost tostSuccess mb-4">
                            <ion-icon name="checkmark-circle" class="text-success me-2"></ion-icon>
                            <div>
                                <p class="text-success mb-0">Action Successful</p>
                                <p class="mb-0">Clocked in at 10:12:17 am</p>
                            </div>
                        </div>

                        <!-- clock in clock out Buttons -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <button class="clockBtn clockIn ">
                                    <ion-icon name="arrow-forward-circle"></ion-icon>
                                    Clock In
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="clockBtn clockOut diable">
                                    <ion-icon name="arrow-back-circle"></ion-icon>
                                    Clock Out
                                </button>
                            </div>
                        </div>
                        <!-- last action  -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="lastActionCard text-center">
                                    <p class="mb-1">Last Action</p>
                                    <p class="text-dark mb-0" style="font-size: 16px">Clocked Out at 12:32:08 pm</p>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="whiteBigCard">
                                    <h4 class="mb-3"><ion-icon name="location-outline"></ion-icon> Location Verification
                                    </h4>
                                    <p class="locationVerify mb-2 text-success"><ion-icon
                                            name="checkmark-circle"></ion-icon>
                                        Location
                                        Verified
                                    </p>
                                    <p class="locationVerify mb-2 text-danger">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Location Fetching...
                                    </p>
                                    <div class="myCard2">
                                        <div class="card2Flex mb-2">
                                            <ion-icon name="business-outline"></ion-icon>
                                            <div>
                                                <h6 class="mb-0"> State Government Office, Sector 12
                                                </h6>
                                                <p class="mb-0">New Delhi
                                                </p>
                                            </div>
                                        </div>

                                        <div class="card2Flex">
                                            <ion-icon name="globe-outline"></ion-icon>
                                            <p class="mb-0">New Delhi
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="whiteBigCard">
                                    <h4 class="mb-3"><ion-icon name="calendar-outline"></ion-icon> Today's Shift
                                        Schedule</h4>
                                    <div class="myCard2 myCard2Outline mb-3">
                                        <p class="mb-2" style="color: #1E40AF"> Shift Type
                                        </p>
                                        <h5 class="mb-0 text-dark fw-semibold">Regular Day Shift
                                        </h5>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="myCard2">
                                                <div class="card2Flex mb-2">
                                                    <ion-icon name="arrow-forward-circle-outline"
                                                        class="text-success"></ion-icon>
                                                    <p class="mb-0">Start Time
                                                    </p>
                                                </div>
                                                <h5 class="mb-0 text-dark fw-semibold">10:00 AM
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="myCard2">
                                                <div class="card2Flex mb-2">
                                                    <ion-icon name="arrow-back-circle-outline"
                                                        class="text-danger"></ion-icon>
                                                    <p class="mb-0">End Time
                                                    </p>
                                                </div>
                                                <h5 class="mb-0 text-dark fw-semibold">06:00 PM
                                                </h5>
                                            </div>
                                        </div>


                                    </div>

                                    <p class="lineFlex">
                                        <span>Expected Hours</span>
                                        <span class="text-dark">8 hours</span>
                                    </p>
                                    <p class="lineFlex">
                                        <span>Break Time</span>
                                        <span class="text-dark">45 minutes</span>
                                    </p>
                                    <p class="lineFlex">
                                        <span>Working Hours</span>
                                        <span class="text-dark">8 hours</span>
                                    </p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>