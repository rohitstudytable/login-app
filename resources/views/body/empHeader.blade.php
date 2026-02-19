<header class="conWrepper">
    <div class="myCon">
        <div class="header">

            {{-- LOGO --}}
            <a href="/" class="logo">
                <img src="{{ asset('images/dd.png') }}" alt="DD Logo">
                <div>
                    <h5>DD Attendance Portal</h5>
                    <p>D&D Learning PVT LTD</p>
                </div>
            </a>

            {{-- MENU --}}
            <div class="topMenu">

                {{-- Home --}}
                <a href="/" class="menuItem {{ Request::is('empdashboard') ? 'active' : '' }}">
                    <ion-icon name="home-outline"></ion-icon>
                    Home
                </a>

                {{-- Attendance (Public Safe) --}}
                <a href="{{ route('empattendance') }}"
                    class="menuItem {{ Request::is('empattendance') ? 'active' : '' }}">
                    <ion-icon name="timer-outline"></ion-icon>
                    Attendance
                </a>


                <a href="/empreport" class="menuItem {{ Request::is('empreport') ? 'active' : '' }}">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                    Report
                </a>


                {{-- thi spart --}}
                @if(isset($intern))
                    <a href="/empprofile" class="menuItem {{ Request::is('empprofile') ? 'active' : '' }}">
                        <ion-icon name="person-outline"></ion-icon>
                        {{ $intern->name }}
                    </a>
                @endif


            </div>
        </div>
    </div>
</header>