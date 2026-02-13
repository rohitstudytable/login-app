<header class="conWrepper">
    <div class="myCon">
        <div class="header">
            <a href="" class="logo">
                <img src="{{ asset('images/dd.png') }}" alt="">
                <div>
                    <h5>DD Attendance Portal</h5>
                    <p>D&D Learning PVT LTD</p>
                </div>
            </a>
            <div class="topMenu">

                <a href="/" class="menuItem {{ Request::is('/') ? 'active' : '' }}">
                    <ion-icon name="home-outline"></ion-icon>
                    Dashboard
                </a>

                <a href="/empattendance" class="menuItem {{ Request::is('empattendance') ? 'active' : '' }}">
                    <ion-icon name="timer-outline"></ion-icon>
                    Attendance
                </a>

                <a href="/empreport" class="menuItem {{ Request::is('empreport') ? 'active' : '' }}">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                    Report
                </a>

                <a href="/profile" class="menuItem {{ Request::is('profile') ? 'active' : '' }}">
                    <ion-icon name="person-outline"></ion-icon>
                    Profile
                </a>

            </div>

        </div>
    </div>

</header>