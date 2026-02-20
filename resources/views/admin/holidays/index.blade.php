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
                <div class="alert">{{ session('success') }}</div>
            @endif

            <div class="card">

                <a href="{{ route('admin.holidays.create') }}" class="btn btn-add">
                    + Add Holiday
                </a>

                <table>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Action</th>
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
                                    <button class="btn btn-delete" onclick="return confirm('Delete holiday?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No holidays found</td>
                        </tr>
                    @endforelse

                </table>

            </div>
        </div>
    </div>
</body>

</html>