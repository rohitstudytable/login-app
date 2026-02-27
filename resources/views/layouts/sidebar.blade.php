<!-- SIDEBAR ADMIN-->
<div class="sidebar">

    <div class="sidebar-header">

        <img src="{{ asset('images/dd.png') }}" alt="DD Logo">
        <div>
            <h5>DD Attendance Portal</h5>
            <p>D&D Learning PVT LTD</p>
        </div>

    </div>

    <div class="sidebar-menu">
        <h6>ADMIN</h6>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <ion-icon name="grid-outline"></ion-icon>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('interns.index') }}" class="{{ request()->routeIs('interns.index') ? 'active' : '' }}">
            <ion-icon name="people-outline"></ion-icon>
            <span>Interns / Employee</span>
        </a>

        <a href="{{ route('attendance.index') }}" class="{{ request()->routeIs('attendance.index') ? 'active' : '' }}">
            <ion-icon name="calendar-outline"></ion-icon>
            <span>Attendance</span>
        </a>

        <a href="{{ route('admin.holidays.index') }}"
            class="{{ request()->routeIs('admin.holidays.*') ? 'active' : '' }}">
            <ion-icon name="gift-outline"></ion-icon>
            <span>Holidays</span>
        </a>

        <a href="{{ route('report') }}" class="{{ request()->routeIs('report') ? 'active' : '' }}">
            <ion-icon name="bar-chart-outline"></ion-icon>
            <span>Report</span>
        </a>


        {{-- <a href="{{ route('interns.holidays.page') }}">
            <ion-icon name="gift-outline"></ion-icon>
            <span>Intern Holidays</span>
        </a>
        --}}

    </div>
</div>