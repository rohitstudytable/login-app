  <div class="topbar">
        <h2>{{ $intern->name }} Attendance</h2>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>
