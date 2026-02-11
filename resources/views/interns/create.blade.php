<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Intern / Employee</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; min-height:100vh; display:flex; }
    .sidebar { width:240px; background:linear-gradient(180deg,#1d4ed8,#1e40af); color:#fff; padding:20px; }
    .sidebar h2 { text-align:center; margin-bottom:30px; }
    .sidebar a { display:block; padding:12px 15px; margin-bottom:10px; border-radius:8px; text-decoration:none; color:white; transition:0.3s; }
    .sidebar a:hover { background:rgba(255,255,255,0.15); }
    .main { flex:1; display:flex; flex-direction:column; }
    .topbar { background:white; padding:15px 25px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
    .logout-btn { background:#dc2626; border:none; padding:8px 14px; color:white; border-radius:6px; cursor:pointer; }
    .logout-btn:hover { background:#b91c1c; }
    .content { padding:25px; }
    .form-card { background:white; max-width:520px; padding:25px; border-radius:12px; box-shadow:0 8px 18px rgba(0,0,0,0.08); }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; margin-bottom:6px; font-weight:600; }
    .form-group input,
    .form-group select {
        width:100%;
        padding:10px;
        border-radius:6px;
        border:1px solid #d1d5db;
        font-size:14px;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline:none;
        border-color:#2563eb;
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
    .btn { padding:10px 16px; border-radius:6px; border:none; cursor:pointer; font-size:14px; color:white; background:#2563eb; transition:0.3s; }
    .btn:hover { background:#1d4ed8; }

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
        <h2>Add Intern / Employee</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="form-card">
            <form method="POST" action="{{ route('interns.store') }}">
                @csrf

                <!-- NAME -->
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <!-- CONTACT -->
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" value="{{ old('contact') }}" placeholder="Enter phone number">
                    @error('contact') <div class="error">{{ $message }}</div> @enderror
                </div>

                <!-- INTERN / EMPLOYEE CODE -->
                <div class="form-group">
                    <label>Intern / Employee Code</label>
                    <input type="text" name="intern_code" value="{{ old('intern_code') }}" placeholder="e.g. INT26A001" required>
                    <div class="helper-text">
                        Code is entered by admin.  
                        🔐 An 8-character password will be auto-generated.
                    </div>
                    @error('intern_code') <div class="error">{{ $message }}</div> @enderror
                </div>

                <!-- ROLE -->
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="intern" {{ old('role') == 'intern' ? 'selected' : '' }}>Intern</option>
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                    @error('role') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button class="btn">Save</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
