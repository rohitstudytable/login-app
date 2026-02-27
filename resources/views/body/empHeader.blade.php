<header class="conWrepper">
    <div class="myCon">
        <div class="header" style="position: relative;">

            {{-- LOGO --}}
            <a href="/" class="logo">
                <img src="{{ asset('images/dd.png') }}" alt="DD Logo">
                <div>
                    <h5>DD Attendance Portal</h5>
                    <p>D&D Learning PVT LTD</p>
                </div>
            </a>
            <span onclick="toggleMenu()" class="mobileMenu">
                <ion-icon id="menuIcon" name="menu-outline"></ion-icon>
            </span>
            {{-- MENU --}}
            <div class="topMenu" id="topManu">

                <a href="/" class="menuItem {{ Request::is('empdashboard') ? 'active' : '' }}">
                    <ion-icon name="home-outline"></ion-icon>
                    Home
                </a>

                <a href="{{ route('empattendance') }}"
                    class="menuItem {{ Request::is('empattendance') ? 'active' : '' }}">
                    <ion-icon name="timer-outline"></ion-icon>
                    Attendance
                </a>

                <a href="/empreport" class="menuItem {{ Request::is('empreport') ? 'active' : '' }}">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                    Report
                </a>

                @if(isset($intern))
                    <a href="/empprofile" class="menuItem {{ Request::is('empprofile') ? 'active' : '' }}">
                        <ion-icon name="person-outline"></ion-icon>
                        {{ $intern->name }}
                    </a>
                @endif

            </div>



            <script>
                function toggleMenu() {
                    const menu = document.getElementById("topManu");
                    const icon = document.getElementById("menuIcon");

                    if (menu.style.top === "65px") {
                        menu.style.top = "-100%";
                        icon.setAttribute("name", "menu-outline");
                    } else {
                        menu.style.top = "65px";
                        icon.setAttribute("name", "close-outline");
                    }
                }
            </script>
        </div>
    </div>
</header>