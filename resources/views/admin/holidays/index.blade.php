<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <div class="topbar">
            <h2>Holiday List</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="custom-alert">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="card card-holidays">

                <div class="card-header-flex">
                    <div class="card-title">
                        <div class="card-icon bg-amber">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                        <h3>All Holidays</h3>
                    </div>
                    <a href="{{ route('admin.holidays.create') }}" class="btn-add">
                        <ion-icon name="add-outline"></ion-icon>
                        Add Holiday
                    </a>
                </div>

                <table>
                    <tr>
                        <th>#</th>
                        <th><ion-icon name="bookmark-outline"></ion-icon> Title</th>
                        <th><ion-icon name="calendar-number-outline"></ion-icon> Date</th>
                        <th><ion-icon name="settings-outline"></ion-icon> Action</th>
                    </tr>

                    @forelse($holidays as $i => $holiday)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $holiday->title }}</td>
                            <td>{{ $holiday->holiday_date }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.holidays.destroy', $holiday->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-btn delete-btn" onclick="return confirm('Delete holiday?')">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <ion-icon name="file-tray-outline"></ion-icon>
                                <p>No holidays found</p>
                            </td>
                        </tr>
                    @endforelse

                </table>

            </div>
        </div>
    </div>
</body>

</html>