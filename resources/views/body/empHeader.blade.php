<style>
/* ================================
   RESPONSIVE HEADER WITH SIDEBAR
   ================================ */

/* Toggle checkbox (hidden) */
#menuToggle {
    display: none;
}

/* Hamburger icon */
.menuIcon {
    display: none;
    font-size: 28px;
    cursor: pointer;
    color: #1E40AF;
}

/* ---------------- MOBILE ---------------- */
@media (max-width: 768px) {

    .menuIcon {
        display: block;
        position: absolute;
        right: 16px;
        top: 22px;
        z-index: 1002;
    }

    .topMenu {
        position: fixed;
        top: 0;
        right: -270px;
        width: 270px;
        height: 100vh;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        padding-top: 90px;
        box-shadow: -6px 0 18px rgba(0,0,0,0.15);
        transition: right 0.35s ease;
        z-index: 1001;
    }

    /* Sidebar open */
    #menuToggle:checked ~ .topMenu {
        right: 0;
    }

    .menuItem {
        padding: 15px 22px;
        border-bottom: 1px solid #e5e7eb;
        width: 100%;
        font-size: 15px;
    }

    /* Dim background overlay */
    .header::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 1000;
    }

    #menuToggle:checked ~ .header::before {
        opacity: 1;
        pointer-events: auto;
    }
}

/* ---------------- DESKTOP ---------------- */
@media (min-width: 769px) {
    .topMenu {
        position: static;
        height: auto;
        width: auto;
        flex-direction: row;
        padding: 0;
        box-shadow: none;
    }
}
</style>


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

            {{-- HAMBURGER --}}
            <input type="checkbox" id="menuToggle">
            <label for="menuToggle" class="menuIcon">
                <ion-icon name="menu-outline"></ion-icon>
            </label>

            {{-- MENU --}}
            <div class="topMenu">

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
        </div>
    </div>
</header>