<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Intern Attendance</title>
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
.card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 18px rgba(0,0,0,.08);margin-bottom:30px}
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left}
th{background:#f1f5f9}
.btn{padding:8px 14px;border-radius:6px;border:none;cursor:pointer;font-size:14px;color:white}
.btn-primary{background:#2563eb}
.btn-primary:hover{background:#1d4ed8}
.photo-preview{width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #ddd}
video{border:1px solid #ccc;border-radius:6px;width:150px;height:100px;object-fit:cover}
canvas{display:none}

.custom-alert {
    background-color: #4CAF50; /* green */
    color: white;
    padding: 12px 20px;
    margin: 10px 0;
    border-radius: 5px;
    font-weight: bold;
    transition: opacity 0.5s ease;
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('interns.index') }}">Interns</a>
    <a href="{{ route('attendance.index') }}">Attendance</a>
</div>

<div class="main">

<div class="topbar">
    <h2>Attendance</h2>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>


{{-- ================= MARK ATTENDANCE ================= --}}
<div class="card">
<h3>Mark Attendance (One Intern at a Time)</h3>

<table>
<tr>
    <th>Intern</th>
    <th>Date</th>
    <th>Status</th>
    <th>Photo</th>
    <th>Action</th>
</tr>

@foreach($interns as $intern)
<tr>
<form id="att_form_{{ $intern->id }}" method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data">
@csrf
<input type="hidden" name="intern_id" value="{{ $intern->id }}">
<input type="hidden" name="photo_data" id="photo_data_{{ $intern->id }}">
</form>

<td>{{ $intern->name }}</td>

<td>
    <input type="date" name="date" value="{{ date('Y-m-d') }}" form="att_form_{{ $intern->id }}" required>
</td>

<td>
    <select name="status" form="att_form_{{ $intern->id }}" required>
        <option value="present">Present</option>
        <option value="absent">Absent</option>
        <option value="half_day">Half Day</option>
    </select>
</td>

<td>

    <!-- Mobile input -->
    {{-- <input type="file" accept="image/*" capture="environment" form="att_form_{{ $intern->id }}" onchange="handleFileUpload(event, {{ $intern->id }})"> --}}

    <!-- Desktop camera -->
    <div>
        <video id="video_{{ $intern->id }}" autoplay></video>
        <button type="button" onclick="capturePhoto({{ $intern->id }})">Snap</button>
        <canvas id="canvas_{{ $intern->id }}"></canvas>
    </div>
</td>

<td>
    <button class="btn btn-primary" type="submit" form="att_form_{{ $intern->id }}">Save</button>
</td>

</tr>
@endforeach
</table>
</div>

{{-- ================= RECORDS ================= --}}

<div class="content">
   @if(session('success'))
    <div id="success-alert" class="custom-alert">
        {{ session('success') }}
    </div>

    <script>
        // Auto remove after 3 seconds
        setTimeout(function() {
            let alertBox = document.getElementById('success-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';   // fade out
                setTimeout(() => alertBox.remove(), 500); // remove after fade
            }
        }, 3000);
    </script>
@endif
<div class="card">
<h3>Attendance Records</h3>

<table>
<tr>
    <th>Sl no</th>
    <th>Intern</th>
    <th>Date</th>
    <th>Status</th>
    <th>Photo</th>
</tr>
@php
    $i=1;
@endphp

@foreach($records as $att)
<tr>
    <td>{{ $i++ }}</td>
    <td>{{ $att->intern->name }}</td>
    <td>{{ $att->date }}</td>
    <td>{{ ucfirst(str_replace('_',' ',$att->status)) }}</td>
    <td>
        @if($att->photo)
            <img src="{{ asset('storage/'.$att->photo) }}" class="photo-preview">
        @else
            —
        @endif
    </td>
</tr>
@endforeach
</table>
</div>

</div>
</div>

<script>
function handleFileUpload(event, id){
    const file = event.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
        document.getElementById('photo_data_'+id).value = e.target.result;
    }
    reader.readAsDataURL(file);
}

// Setup webcam for desktop
@foreach($interns as $intern)
navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => {
    const video = document.getElementById('video_{{ $intern->id }}');
    if(video) video.srcObject = stream;
})
.catch(err => console.log('Camera not accessible:', err));
@endforeach

function capturePhoto(id){
    const video = document.getElementById('video_'+id);
    const canvas = document.getElementById('canvas_'+id);
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('photo_data_'+id).value = dataUrl;
    alert('Photo captured! You can now save attendance.');
}
</script>

</body>
</html>
