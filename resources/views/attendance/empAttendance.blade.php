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
                        <div class="myCard primaryCard text-center clockCard">
                            <p class="mb-1"><ion-icon name="time"></ion-icon> Indian Standard Time (IST)</p>
                            <h2 class="mb-1">12:20:35</h2>
                            <h6 class="mb-0">Wednesday, 11 February 2026</h6>

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