@include('body.headerlink')



<body>

    <div class="">
        <!-- <div class="sidebar">sidebar</div> -->
        <div>
            @include('body.empHeader')
            <section class="myBodySection">
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="myCard primaryCard">
                            <div class="perentCardFlex">
                                <div class="welcomeFlex">
                                    <ion-icon name="person-circle-outline"></ion-icon>
                                    <div>
                                        <h3>Welcome back, Rohit Acharjee</h3>
                                        <!-- if user is emp -->
                                        <p>Employee ID: D&D-014</p>

                                        <!-- if user is intern -->
                                        <!-- <p>Intern ID: Intern-014</p> -->
                                    </div>
                                </div>
                                <div>
                                    <div class="cardDate">
                                        <h2>29</h2>
                                        <p>December</p>
                                    </div>
                                </div>
                            </div>
                            <div class="myCardFoot">
                                <p><ion-icon name="business-outline"></ion-icon> D&D learning Pvt Ltd</p>
                                <p><ion-icon name="time-outline"></ion-icon> Last Login: 28 Dec 2024, 09:15 AM IST</p>
                            </div>


                        </div>
                    </div>
                </div>





                <div class="conWrepper">
                    <div class="myConSm">
                        <div class="row">
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