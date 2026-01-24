@include('body.headerlink')

<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f4f6f9; }
.card { background:white; padding:20px; border-radius:12px; max-width:400px; margin:auto; box-shadow:0 8px 18px rgba(0,0,0,.08); }
input, select, button { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { background:#2563eb; color:white; border:none; cursor:pointer; }
button:hover { background:#1d4ed8; }
video { width:100%; max-width:300px; border-radius:6px; margin-top:10px; }
</style>
<body>




@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif
<div class="container-fluid vh-100 d-flex flex-column justify-content-center align-items-center">

    <!-- Page Title -->
    <h2 class="text-center mb-4">Attendance for {{ $intern->name }}</h2>

    <div class="col-md-6">
        <div class="card shadow-lg w-100">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Submit Attendance</h4>
            </div>
            <div class="card-body">
                <!-- Success / Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <!-- Attendance Form -->
                <form method="POST" action="{{ route('attendance.publicStoreByToken') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="random_code" value="{{ $encryptToken }}">
                    <input type="hidden" name="intern" value="{{ dataEncrypt($intern->id) }}">

                    <!-- Name (decrypted) -->
                    <div class="mb-3">
                        <label class="form-label">Employee Name</label>
                        <input type="text" class="form-control text-center" value="{{ $intern->name }}" readonly>
                    </div>

                    <!-- Date (decrypted) -->
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="hidden" name="date" value="{{ $encryptdate }}">
                        <input type="text" class="form-control text-center" id="date" disabled>
                    </div>

                    <!-- Status -->

                    <!-- Photo Capture -->
                    <div class="mb-3">
                        <label for="photo" class="form-label">Upload Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" capture="environment" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Submit Attendance</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fill today's date into disabled field
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').value = today;
</script>

<script>
    // Get today's date
    const today = new Date();

    // Format as YYYY-MM-DD
    const formattedDate = today.toISOString().split('T')[0];

    // Set value in the disabled input
    document.getElementById('date').value = formattedDate;
</script>




                    <!-- Photo Capture -->
                    <div class="mb-3">
                        <label for="photo" class="form-label">Upload Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" capture="environment" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit Attendance</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
