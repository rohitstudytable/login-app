<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('admin.holidays.index') }}" class="back-btn">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                </a>
                <h2>Add Holiday</h2>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            <div class="form-card card-holiday-form">

                <div class="card-title">
                    <div class="card-icon bg-amber">
                        <ion-icon name="sunny-outline"></ion-icon>
                    </div>
                    <h3>Holiday Details</h3>
                </div>

                <form method="POST" action="{{ route('admin.holidays.store') }}">
                    @csrf

                    <div class="form-grid">

                        <div class="form-group">
                            <label><ion-icon name="text-outline"></ion-icon> Holiday Name</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Diwali, Christmas..." required>
                            @error('title') <div class="error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label><ion-icon name="calendar-outline"></ion-icon> Holiday Date</label>
                            <input type="date" name="holiday_date" value="{{ old('holiday_date') }}" required>
                            @error('holiday_date') <div class="error">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="form-footer">
                        <button class="btn btn-primary"><ion-icon name="save-outline"></ion-icon> Save Holiday</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</body>

</html>