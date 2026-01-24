<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance for {{ $intern->name }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f4f6f9; }
.card { background:white; padding:20px; border-radius:12px; max-width:400px; margin:auto; box-shadow:0 8px 18px rgba(0,0,0,.08); }
input, select, button { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { background:#2563eb; color:white; border:none; cursor:pointer; }
button:hover { background:#1d4ed8; }
video { width:100%; max-width:300px; border-radius:6px; margin-top:10px; }
</style>
</head>
<body>

<div class="card">
<h2>Attendance for {{ $intern->name }}</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('attendance.publicStoreByToken', $intern->attendance_token) }}" enctype="multipart/form-data">
    @csrf

    <label>Date</label>
    <input type="date" name="date" value="{{ date('Y-m-d') }}" required>

    <label>Status</label>
    <select name="status" required>
        <option value="present">Present</option>
        <option value="absent">Absent</option>
        <option value="half_day">Half Day</option>
    </select>

    <label>Upload Photo</label>
    <input type="file" name="photo" accept="image/*" capture="environment">

    <button type="submit">Submit Attendance</button>
</form>
</div>

</body>
</html>
