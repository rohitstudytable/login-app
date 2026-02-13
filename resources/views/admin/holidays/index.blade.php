<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Holiday List</title>
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

.card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 18px rgba(0,0,0,.08)}

table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left}
th{background:#f1f5f9}

.btn{padding:8px 14px;border:none;border-radius:6px;cursor:pointer}
.btn-add{background:#2563eb;color:white;text-decoration:none}
.btn-delete{background:#dc2626;color:white}

.alert{background:#16a34a;color:white;padding:10px;border-radius:6px;margin-bottom:10px}
</style>
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
<td>{{ $i+1 }}</td>
<td>{{ $holiday->title }}</td>
<td>{{ $holiday->holiday_date }}</td>
<td>
<form method="POST" action="{{ route('admin.holidays.destroy',$holiday->id) }}">
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
