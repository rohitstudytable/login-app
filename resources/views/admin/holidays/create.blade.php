<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Holiday</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f4f6f9;min-height:100vh;display:flex}

.sidebar{width:240px;background:linear-gradient(180deg,#1d4ed8,#1e40af);color:#fff;padding:20px}
.sidebar h2{text-align:center;margin-bottom:30px}
.sidebar a{display:block;padding:12px 15px;margin-bottom:10px;border-radius:8px;text-decoration:none;color:white}
.sidebar a:hover{background:rgba(255,255,255,.15)}

.main{flex:1;display:flex;flex-direction:column}

.topbar{background:white;padding:15px 25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 6px rgba(0,0,0,.08)}

.logout-btn{background:#dc2626;border:none;padding:8px 14px;color:white;border-radius:6px;cursor:pointer}
.logout-btn:hover{background:#b91c1c}

.content{padding:25px}

.form-card{background:white;max-width:520px;padding:25px;border-radius:12px;box-shadow:0 8px 18px rgba(0,0,0,.08)}

.form-group{margin-bottom:18px}
.form-group label{display:block;margin-bottom:6px;font-weight:600}

.form-group input{
width:100%;
padding:10px;
border-radius:6px;
border:1px solid #d1d5db;
}

.error{font-size:12px;color:#dc2626;margin-top:4px}

.btn{padding:10px 16px;border-radius:6px;border:none;cursor:pointer;font-size:14px;color:white;background:#2563eb}
.btn:hover{background:#1d4ed8}
</style>
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
