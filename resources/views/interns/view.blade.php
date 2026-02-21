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
                <div class="profile-image-wrapper">
                    @if($intern->img)
                        <img src="{{ asset('storage/'.$intern->img) }}" class="profile-img">
                    @else
                        <img src="{{ asset('assets/default-user.png') }}" class="profile-img">
                    @endif
                </div>

                <div class="profile-meta">
                    <h3>{{ $intern->name }}</h3>
                    <p class="designation">{{ $intern->designation ?? '—' }}</p>

                    <div class="badge-group">
                        <span class="badge role">{{ ucfirst($intern->role) }}</span>
                        <span class="badge code">{{ $intern->intern_code }}</span>
                    </div>
                </div>
            </div>

            <!-- PERSONAL INFO -->
            <h5 class="section-title">Personal Information</h5>
            <div class="form-grid">

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
            <div class="form-grid">

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

                <a href="{{ route('interns.edit', $intern->id) }}" class="btn-secondary">
                    <i class="fa fa-edit"></i> Edit Profile
                </a>
            </div>

        </div>
    </div>

</div>

</body>
</html>

<style>
  .page-title {
    font-size: 20px;
    margin-bottom: 2px;
}

.page-subtitle {
    font-size: 13px;
    color: #6b7280;
}

.profile-card {
    padding: 28px;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 24px;
}

.profile-image-wrapper {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e5e7eb;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-meta h3 {
    margin-bottom: 4px;
}

.designation {
    color: #6b7280;
    font-size: 14px;
}

.badge-group {
    margin-top: 8px;
    display: flex;
    gap: 8px;
}

.badge {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 500;
}

.badge.role {
    background: #e0f2fe;
    color: #0369a1;
}

.badge.code {
    background: #f3f4f6;
    color: #374151;
}

.section-title {
    font-size: 15px;
    margin-bottom: 12px;
}

.info-group label {
    font-size: 12px;
    color: #6b7280;
}

.info-group p {
    font-size: 14px;
    font-weight: 500;
    margin-top: 4px;
}

.space-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
