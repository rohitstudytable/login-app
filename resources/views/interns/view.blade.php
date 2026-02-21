<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('interns.index') }}" class="back-btn">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
                <div>
                    <h2 class="page-title">{{ $intern->name }}</h2>
                    <span class="page-subtitle">
                        {{ ucfirst($intern->role) }} · {{ $intern->designation ?? '—' }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="form-card profile-card">

                <!-- PROFILE HEADER -->
                <div class="profile-header">

                    @if($intern->img)
                        <img src="{{ asset('storage/' . $intern->img) }}" class="profile-img">
                    @else
                        <img src="{{ asset('assets/default-user.png') }}" class="profile-img">
                    @endif


                    <div class="profile-meta">
                        <h4>{{ $intern->name }}</h4>
                        <p class="designation">{{ $intern->designation ?? '—' }}</p>

                        <div class="badge-group">
                            <span class="badge role">{{ ucfirst($intern->role) }}</span>
                            <span class="badge code">{{ $intern->intern_code }}</span>
                        </div>
                    </div>
                </div>

                <!-- PERSONAL INFO -->
                <h5 class="section-title">Personal Information</h5>
                <div class="form-grid-3" style="border-bottom: 1px solid #e5e7eb;padding-bottom: 20px;">

                    <div class="info-group">
                        <label>Gender</label>
                        <p>{{ ucfirst($intern->gender) ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Date of Birth</label>
                        <p>{{ $intern->dob ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Blood Group</label>
                        <p>{{ $intern->blood_group ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Nationality</label>
                        <p>{{ $intern->nationality ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Marital Status</label>
                        <p>{{ $intern->marital_status ?? '-' }}</p>

                    </div>

                </div>

                <!-- CONTACT INFO -->
                <h5 class="section-title mt-4">Contact Information</h5>
                <div class="form-grid-3">

                    <div class="info-group">
                        <label>Phone</label>
                        <p>{{ $intern->contact ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Email</label>
                        <p>{{ $intern->email }}</p>
                    </div>

                    <div class="info-group full">
                        <label>Address</label>
                        <p>{{ $intern->address ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>City</label>
                        <p>{{ $intern->city ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>State</label>
                        <p>{{ $intern->state ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>PIN</label>
                        <p>{{ $intern->pin ?? '-' }}</p>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="form-footer space-between">
                    <small class="text-muted">
                        Joined on {{ $intern->created_at?->format('d M Y') }}
                    </small>

                    <a href="{{ route('interns.edit', $intern->id) }}" class="btn-primary">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>

</html>