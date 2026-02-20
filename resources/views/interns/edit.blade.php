<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Intern / Employee</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; min-height:100vh; display:flex; }

    .sidebar { width:240px; background:linear-gradient(180deg,#1d4ed8,#1e40af); color:#fff; padding:20px; }
    .sidebar h2 { text-align:center; margin-bottom:30px; }
    .sidebar a { display:block; padding:12px 15px; margin-bottom:10px; border-radius:8px; text-decoration:none; color:white; transition:0.3s; }
    .sidebar a:hover { background:rgba(255,255,255,0.15); }

    .main { flex:1; display:flex; flex-direction:column; }

    .topbar {
        background:white;
        padding:15px 25px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        box-shadow:0 2px 6px rgba(0,0,0,0.08);
    }

    .logout-btn {
        background:#dc2626;
        border:none;
        padding:8px 14px;
        color:white;
        border-radius:6px;
        cursor:pointer;
    }

    .logout-btn:hover { background:#b91c1c; }

    .content { padding:25px; }

    .form-card {
        background:white;
        max-width:520px;
        padding:25px;
        border-radius:12px;
        box-shadow:0 8px 18px rgba(0,0,0,0.08);
    }

    .form-group { margin-bottom:18px; }

    .form-group label {
        display:block;
        margin-bottom:6px;
        font-weight:600;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width:100%;
        padding:10px;
        border-radius:6px;
        border:1px solid #d1d5db;
        font-size:14px;
    }

    .form-group textarea { resize:vertical; }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline:none;
        border-color:#2563eb;
    }

    .form-group input[readonly] {
        background:#f3f4f6;
        cursor:not-allowed;
    }

    .helper-text {
        font-size:12px;
        color:#6b7280;
        margin-top:4px;
    }

    .error {
        font-size:12px;
        color:#dc2626;
        margin-top:4px;
    }

    .btn {
        padding:10px 16px;
        border-radius:6px;
        border:none;
        cursor:pointer;
        font-size:14px;
        color:white;
        background:#2563eb;
        transition:0.3s;
    }

    .btn:hover { background:#1d4ed8; }

    .profile-img {
        width:90px;
        height:90px;
        border-radius:50%;
        object-fit:cover;
        margin-bottom:10px;
        border:2px solid #e5e7eb;
    }

    @media (max-width:768px) {
        body { flex-direction:column; }
        .sidebar { width:100%; display:flex; justify-content:space-around; }
        .sidebar h2 { display:none; }
        .form-card { max-width:100%; }
    }
</style>
</head>

<body>

@include('layouts.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Edit Intern / Employee</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="form-card">

            <form method="POST" action="{{ route('interns.update', $intern) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- BASIC DETAILS -->
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $intern->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $intern->email) }}" required>
                </div>

               <div class="form-group">
                    <label>Contact</label>
                    <input type="text"
                        name="contact"
                        value="{{ old('contact', $intern->contact) }}"
                        maxlength="10"
                        pattern="[0-9]{1,13}"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <div class="helper-text">
                        Only numbers allowed. Maximum 10 digits.
                    </div>
                </div>


                <!-- PERSONAL DETAILS -->
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select</option>
                        <option value="male" {{ $intern->gender === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $intern->gender === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $intern->gender === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', optional($intern->dob)->format('Y-m-d')) }}">
                </div>

                 <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group">
                        <option value="">Select Blood Group</option>
                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <select name="marital_status">
                        <option value="">Select</option>
                        <option value="single" {{ $intern->marital_status === 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ $intern->marital_status === 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ $intern->marital_status === 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ $intern->marital_status === 'widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $intern->nationality) }}">
                </div>

                <!-- ADDRESS -->
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3">{{ old('address', $intern->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city', $intern->city) }}">
                </div>

                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" value="{{ old('state', $intern->state) }}">
                </div>

                <div class="form-group">
                    <label>PIN Code</label>
                    <input type="text" name="pin" value="{{ old('pin', $intern->pin) }}">
                </div>

                <!-- WORK DETAILS -->
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $intern->designation) }}">
                </div>

                <div class="form-group">
                    <label>Intern / Employee Code</label>
                    <input type="text" name="intern_code" value="{{ old('intern_code', $intern->intern_code) }}" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="intern" {{ $intern->role === 'intern' ? 'selected' : '' }}>Intern</option>
                        <option value="employee" {{ $intern->role === 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>

                <!-- PASSWORD (READ ONLY) -->
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" value="{{ $intern->plain_password }}" readonly>
                    <div class="helper-text">
                        Password is auto-generated and cannot be changed.
                    </div>
                </div>

                <!-- PROFILE IMAGE -->
                <div class="form-group">
                    <label>Profile Image</label>

                    @if($intern->img)
                        <img src="{{ asset('storage/'.$intern->img) }}" class="profile-img">
                    @endif

                    <input type="file" name="img" accept="image/*">

                    <div class="helper-text">
                        Uploading a new image will replace the existing one.<br>
                        Max file size: <strong>5 MB</strong> (JPG, JPEG, PNG, WEBP)
                    </div>
                </div>

                <button class="btn">Update</button>

            </form>
        </div>
    </div>
</div>

</body>
</html>
