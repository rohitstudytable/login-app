@include('body.headerlink')

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background: #f4f6f9;
    }
    .card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        max-width: 500px;
        margin: auto;
        box-shadow: 0 8px 18px rgba(0,0,0,.08);
    }
    input, select, button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    button {
        background: #2563eb;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background: #1d4ed8;
    }
</style>

<body>

<div class="container-fluid vh-100 d-flex flex-column justify-content-center align-items-center">

    <!-- Page Title -->
    <h2 class="text-center mb-4">
        Attendance for {{ $intern->name }}
    </h2>

    <div class="card shadow-lg w-100">

        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Submit Attendance</h4>
        </div>

        <div class="card-body">

            {{-- Success / Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Attendance Form --}}
            <form method="POST"
                  action="{{ route('attendance.publicStoreByToken') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- Hidden Required Data --}}
                <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                <input type="hidden" name="date" value="{{ $date }}">

                {{-- Employee Name --}}
                <div class="mb-3">
                    <label class="form-label">Employee Name</label>
                    <input type="text"
                           class="form-control text-center"
                           value="{{ $intern->name }}"
                           readonly>
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="text"
                           class="form-control text-center"
                           value="{{ $date }}"
                           readonly>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Attendance Status</label>
                    <select name="status" class="form-control" required>
                        <option value="">-- Select Status --</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="half_day">Half Day</option>
                    </select>
                </div>

                {{-- Photo Upload --}}
                <div class="mb-3">
                    <label class="form-label">Upload Photo</label>
                    <input type="file"
                           class="form-control"
                           name="photo"
                           accept="image/*"
                           capture="environment"
                           required>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Submit Attendance
                </button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
