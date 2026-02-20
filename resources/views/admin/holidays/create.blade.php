<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <div class="topbar">
            <h2>Add Holiday</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            <div class="form-card">

                <form method="POST" action="{{ route('admin.holidays.store') }}">
                    @csrf

                    <div class="form-group">
                        <label>Holiday Name</label>
                        <input type="text" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Holiday Date</label>
                        <input type="date" name="holiday_date" value="{{ old('holiday_date') }}" required>
                        @error('holiday_date') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn">Save Holiday</button>

                </form>

            </div>
        </div>
    </div>
</body>

</html>