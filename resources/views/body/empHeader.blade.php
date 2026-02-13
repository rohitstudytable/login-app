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
                <a href="/" class="menuItem {{ Request::is('/') ? 'active' : '' }}">
                    <ion-icon name="home-outline"></ion-icon>
                    Home
                </a>

                {{-- Attendance (Public Safe) --}}
                <a href="{{ route('empattendance') }}"
                   class="menuItem {{ Request::is('attendance/search*') ? 'active' : '' }}">
                    <ion-icon name="timer-outline"></ion-icon>
                    Attendance
                </a>

                {{-- Show ONLY if intern exists --}}
                @if(isset($intern))
                    <span class="menuItem" style="cursor: default;">
                        <ion-icon name="person-outline"></ion-icon>
                        {{ $intern->name }}
                    </span>
                @endif

            </div>
        </div>
    </div>
</header>
