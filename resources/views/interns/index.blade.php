<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')


</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <!-- ================= Topbar ================= -->
        <div class="topbar">
            <h2>Interns & Employees</h2>

            <div class="topbar-right">
                <a href="{{ route('interns.create') }}" class="btn-add">
                    <ion-icon name="add-outline"></ion-icon>
                    Add Employe /Intern
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <div class="content">

            <!-- ================= Role Tabs ================= -->
            <div class="tabs">
                <a href="{{ route('interns.index') }}" class="tab {{ request('role') == null ? 'active' : '' }}">
                    All
                </a>

                <a href="{{ route('interns.index', ['role' => 'intern']) }}"
                    class="tab {{ request('role') == 'intern' ? 'active' : '' }}">
                    Interns
                </a>

                <a href="{{ route('interns.index', ['role' => 'employee']) }}"
                    class="tab {{ request('role') == 'employee' ? 'active' : '' }}">
                    Employees
                </a>
            </div>


            <!-- ================= Table Card ================= -->
            <div class="card table-card">

                <div class="card-header-flex">
                    <h3>Members List</h3>
                    <span class="count-badge">{{ $interns->count() }} Total</span>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Password</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($interns as $intern)
                                <tr>

                                    <td>
                                        <div class="member-info">
                                            <img class="profile-img"
                                                src="{{ $intern->img ? asset('storage/' . $intern->img) : 'https://ui-avatars.com/api/?name=' . $intern->name }}">

                                            <div>
                                                <strong>{{ $intern->name }}</strong>
                                                <small>{{ $intern->email }}</small>
                                                <small>ID: {{ $intern->intern_code }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>{{ $intern->contact ?? '-' }}</td>

                                    <td>
                                        <span class="role-badge {{ $intern->role }}">
                                            {{ ucfirst($intern->role) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="password-box">
                                            {{ $intern->plain_password ?? '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-buttons">

                                            <a href="{{ route('interns.edit', $intern) }}" class="icon-btn edit-btn">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>

                                            <form action="{{ route('interns.destroy', $intern) }}" method="POST"
                                                onsubmit="return confirm('Delete this record?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="icon-btn delete-btn">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;padding:25px">
                                        No records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</body>

</html>