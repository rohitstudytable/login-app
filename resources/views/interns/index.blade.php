<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Interns & Employees</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f4f6f9;min-height:100vh;display:flex}

.sidebar{width:240px;background:linear-gradient(180deg,#1d4ed8,#1e40af);color:#fff;padding:20px}
.sidebar h2{text-align:center;margin-bottom:30px}
.sidebar a{display:block;padding:12px 15px;margin-bottom:10px;border-radius:8px;text-decoration:none;color:white}
.sidebar a:hover{background:rgba(255,255,255,.15)}

.main{flex:1;display:flex;flex-direction:column}

.topbar{
    background:white;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,.08)
}

.logout-btn{
    background:#dc2626;
    border:none;
    padding:8px 14px;
    color:white;
    border-radius:6px;
    cursor:pointer
}

.content{padding:25px}

.tabs{display:flex;gap:10px;margin-bottom:15px}
.tab{
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    background:#e5e7eb;
    color:#374151
}
.tab.active{background:#2563eb;color:white}

.btn{
    padding:8px 14px;
    border-radius:6px;
    border:none;
    cursor:pointer;
    text-decoration:none;
    color:white;
    font-size:14px
}
.btn-primary{background:#2563eb}
.btn-danger{background:#dc2626}

.table-card{
    background:white;
    border-radius:12px;
    box-shadow:0 8px 18px rgba(0,0,0,.08);
    overflow:hidden
}

table{
    width:100%;
    border-collapse:collapse
}

th,td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
    text-align:left;
    vertical-align:middle
}

th{
    background:#f1f5f9;
    font-size:14px
}

tr:hover{background:#f9fafb}

.profile-img{
    width:42px;
    height:42px;
    border-radius:50%;
    object-fit:cover;
    border:1px solid #e5e7eb
}

.info small{
    color:#6b7280;
    display:block;
    font-size:12px
}

.actions{
    display:flex;
    gap:6px
}

/* MOBILE VIEW */
@media (max-width:900px){
    table,thead,tbody,th,td,tr{display:block}
    thead{display:none}
    tr{margin-bottom:15px;border-bottom:2px solid #e5e7eb}
    td{padding:10px}
    td::before{
        content:attr(data-label);
        font-weight:600;
        display:block;
        margin-bottom:4px;
        color:#374151
    }
}
</style>
</head>

<body>

@include('layouts.sidebar')

<div class="main">

<div class="topbar">
    <h2>Interns & Employees</h2>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>

<div class="content">

    <div class="tabs">
        <a href="{{ route('interns.index') }}" class="tab {{ request('role') == null ? 'active' : '' }}">All</a>
        <a href="{{ route('interns.index',['role'=>'intern']) }}" class="tab {{ request('role')=='intern'?'active':'' }}">Interns</a>
        <a href="{{ route('interns.index',['role'=>'employee']) }}" class="tab {{ request('role')=='employee'?'active':'' }}">Employees</a>
    </div>

    <a href="{{ route('interns.create') }}" class="btn btn-primary">+ Add Intern / Employee</a>
    <br><br>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Details</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($interns as $intern)
                <tr>
                    <td data-label="Profile">
                        <img class="profile-img"
                             src="{{ $intern->img ? asset('storage/'.$intern->img) : 'https://ui-avatars.com/api/?name='.$intern->name }}">
                    </td>

                    <td data-label="Details" class="info">
                        <strong>{{ $intern->name }}</strong>
                        <small>{{ $intern->email }}</small>
                        <small>Code: {{ $intern->intern_code }}</small>
                    </td>

                    <td data-label="Contact">
                        {{ $intern->contact ?? '-' }}
                    </td>

                    <td data-label="Role">
                        {{ ucfirst($intern->role) }}
                    </td>

                    <td data-label="Password">
                        <strong>{{ $intern->plain_password ?? '—' }}</strong>
                    </td>

                    <td data-label="Actions">
                        <div class="actions">
                            <a href="{{ route('interns.edit',$intern) }}" class="btn btn-primary">Edit</a>

                            <form action="{{ route('interns.destroy',$intern) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                    onclick="return confirm('Delete this record?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:20px">
                        No records found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>

</body>
</html>
