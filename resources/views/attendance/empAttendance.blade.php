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
                                <button class="clockBtn clockIn diable">
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