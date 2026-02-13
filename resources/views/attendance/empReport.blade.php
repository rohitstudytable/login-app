@include('body.headerlink')



<body>

    <div class="">
        <!-- <div class="sidebar">sidebar</div> -->
        <div>
            @include('body.empHeader')
            <section class="myBodySection">
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="d-flex mb-0 align-items-center">
                            <a href="/"><ion-icon name="home-outline" style="margin-bottom: -2px;"></ion-icon></a>
                            <span class="mx-2">/</span>
                            <p class="mb-0">Attendance Report</p>
                        </div>
                        <h2 class="text-dark fw-bold">Attendance Report</h2>
                        <p class="mb-0">View and analyze your comprehensive attendance records with advanced filtering
                            options</p>
                    </div>
                </div>





                <div class="conWrepper">
                    <div class="myConSm">


                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="whiteBigCard">
                                    <h4 class="mb-3"><ion-icon name="filter-outline"></ion-icon> Filter Records</h4>
                                    <form action="" class="myForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">From Date</label>
                                                <input type="date">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">To Date</label>
                                                <input type="date">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Attendance Status</label>
                                                <select name="" id="">
                                                    <option value="All Status">All Status</option>
                                                    <option value="All Status">Present</option>
                                                    <option value="All Status">Absent</option>
                                                    <option value="All Status">Leave Taken</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="myCard">
                                    <div class="perentCardFlex align-items-center">
                                        <div>
                                            <p class="mb-2">Present Days</p>
                                            <h2 class="text-black mb-0 fw-bold">0</h2>
                                        </div>
                                        <div class="cardIcon">
                                            <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="myCard">
                                    <div class="perentCardFlex align-items-center">
                                        <div>
                                            <p class="mb-2">Absent Days</p>
                                            <h2 class="text-black mb-0 fw-bold">0</h2>
                                        </div>
                                        <div class="cardIcon">
                                            <ion-icon name="close-circle" class="text-danger"></ion-icon>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="myCard">
                                    <div class="perentCardFlex align-items-center">
                                        <div>
                                            <p class="mb-2">Half Days</p>
                                            <h2 class="text-black mb-0 fw-bold">0</h2>
                                        </div>
                                        <div class="cardIcon">
                                            <ion-icon name="hourglass" class="text-warning"></ion-icon>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="myCard">
                                    <div class="perentCardFlex align-items-center">
                                        <div>
                                            <p class="mb-2">Leave Taken</p>
                                            <h2 class="text-black mb-0 fw-bold">0</h2>
                                        </div>
                                        <div class="cardIcon">
                                            <ion-icon name="calendar" class="text-info"></ion-icon>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="whiteBigCard">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h4 class="mb-0"><ion-icon name="list-outline"></ion-icon> Attendance Records
                                        </h4>
                                        <div class="d-flex">
                                            <form class="myForm">
                                                <input type="text" placeholder="🔍 Search here...">
                                            </form>
                                            <button class="myBtn myBtnPrimary mx-2"><ion-icon
                                                    name="download-outline"></ion-icon>Export</button>
                                            <button class="myBtn"><ion-icon
                                                    name="print-outline"></ion-icon>Print</button>
                                        </div>
                                    </div>

                                    <div class="mytableCon">
                                        <table>
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
                                                <tr>
                                                    <td>29/12/2025</td>
                                                    <td>10:00 AM</td>
                                                    <td>06:20 PM</td>
                                                    <td>8h 20m</td>
                                                    <td>
                                                        <!-- <p class="text-success mb-0">Present</p> -->
                                                        <p class="text-warning sm mb-0">Half day</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>29/12/2025</td>
                                                    <td>10:00 AM</td>
                                                    <td>06:20 PM</td>
                                                    <td>8h 20m</td>
                                                    <td>

                                                        <p class="text-warning sm mb-0">Half day</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>30/12/2025</td>
                                                    <td>10:00 AM</td>
                                                    <td>06:20 PM</td>
                                                    <td>8h 20m</td>
                                                    <td>
                                                        <p class="text-success sm mb-0">Present</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>31/12/2025</td>
                                                    <td>10:00 AM</td>
                                                    <td>06:20 PM</td>
                                                    <td>8h 20m</td>
                                                    <td>

                                                        <p class="text-danger sm mb-0">Absent</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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