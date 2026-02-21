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
                    <ion-icon name="people-outline"></ion-icon> All
                </a>

                <a href="{{ route('interns.index', ['role' => 'intern']) }}"
                    class="tab {{ request('role') == 'intern' ? 'active' : '' }}">
                    <ion-icon name="school-outline"></ion-icon> Interns
                </a>

                <a href="{{ route('interns.index', ['role' => 'employee']) }}"
                    class="tab {{ request('role') == 'employee' ? 'active' : '' }}">
                    <ion-icon name="briefcase-outline"></ion-icon> Employees
                </a>
            </div>


            <!-- ================= Table Card ================= -->
            <div class="card card-members table-card">

                <div class="card-header-flex">
                    <div class="card-title">
                        <div class="card-icon bg-indigo">
                            <ion-icon name="people-circle-outline"></ion-icon>
                        </div>
                        <h3>Members List</h3>
                    </div>
                    <span class="count-badge"><ion-icon name="stats-chart-outline"></ion-icon> {{ $interns->count() }}
                        Total</span>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th><ion-icon name="person-outline"></ion-icon> Member</th>
                                <th><ion-icon name="call-outline"></ion-icon> Contact</th>
                                <th><ion-icon name="shield-outline"></ion-icon> Role</th>
                                <th><ion-icon name="lock-closed-outline"></ion-icon> Password</th>
                                <th width="120"><ion-icon name="settings-outline"></ion-icon> Action</th>
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

                                            <!-- VIEW -->
                                            <a href="{{ route('interns.show', $intern->id) }}" class="icon-btn view-btn"
                                                title="View Details">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('interns.edit', $intern) }}" class="icon-btn edit-btn"
                                                title="Edit">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>

                                            <!-- DELETE -->
                                            <form action="{{ route('interns.destroy', $intern) }}" method="POST"
                                                onsubmit="return confirm('Delete this record?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="icon-btn delete-btn" type="submit">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <ion-icon name="file-tray-outline"></ion-icon>
                                        <p>No records found</p>
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