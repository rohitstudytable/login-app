@include('body.headerlink')

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    .card {
        max-width: 520px;
        margin: auto;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(0,0,0,.08);
    }
    .badge {
        font-size: 14px;
    }
</style>

<body>

<div class="container-fluid vh-100 d-flex flex-column justify-content-center align-items-center">

    <h3 class="mb-3 text-center">
        Attendance for {{ $intern->name }}
    </h3>

    <div class="card w-100">

        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Mark Attendance</h5>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
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

                {{-- Hidden --}}
                <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                <input type="hidden" name="date" value="{{ $date }}">

                {{-- Employee Name --}}
                <div class="mb-3">
                    <label class="form-label">Employee Name</label>
                    <input type="text" class="form-control" value="{{ $intern->name }}" readonly>
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="text" class="form-control" value="{{ $date }}" readonly>
                </div>

                {{-- Location --}}
                <div class="mb-3">
                    <label class="form-label">Location (Auto)</label>
                    <input type="text"
                           class="form-control"
                           name="location"
                           id="location"
                           readonly
                           required>
                </div>

                {{-- In Time (Display Only) --}}
                <div class="mb-3">
                    <label class="form-label">In Time</label>
                    <input type="text"
                           class="form-control"
                           id="in_time"
                           placeholder="Will be set automatically"
                           readonly>
                </div>

                {{-- Out Time (Display Only) --}}
                <div class="mb-3">
                    <label class="form-label">Out Time</label>
                    <input type="text"
                           class="form-control"
                           id="out_time"
                           placeholder="Will be set on OUT punch"
                           readonly>
                </div>
                
                {{-- Status (AUTO) --}}
                <input type="hidden" name="status" value="present">

                <div class="mb-3 text-center">
                    <span class="badge bg-success p-2">
                        Status: Present
                    </span>
                </div>


                {{-- Photo --}}
                {{-- <div class="mb-3">
                    <label class="form-label">Photo Proof</label>
                    <input type="file"
                           class="form-control"
                           name="photo"
                           accept="image/*"
                           capture="environment"
                           required>
                </div> --}}

                <button type="submit"
                        class="btn btn-success w-100"
                        id="submitBtn"
                        disabled>
                </button>


            </form>

        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const locationInput = document.getElementById('location');
    const inTimeInput   = document.getElementById('in_time');
    const submitBtn     = document.getElementById('submitBtn');

    // Attendance state from backend
    const hasInTime  = @json($todayAttendance && $todayAttendance->in_time);
    const hasOutTime = @json($todayAttendance && $todayAttendance->out_time);

    // Decide button text
    if (!hasInTime) {
        submitBtn.innerText = 'Submit IN Time';
    } else if (hasInTime && !hasOutTime) {
        submitBtn.innerText = 'Submit OUT Time';
        inTimeInput.value = "{{ $todayAttendance->in_time ?? '' }}";
    } else {
        submitBtn.innerText = 'Attendance Completed';
        submitBtn.disabled = true;
        return;
    }

    // Show current time (display only)
    const now = new Date();
    inTimeInput.value = now.toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });

    if (!navigator.geolocation) {
        alert('Geolocation not supported');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async function (position) {

            const lat = position.coords.latitude.toFixed(6);
            const lon = position.coords.longitude.toFixed(6);

            // Instant fallback
            locationInput.value = `${lat}, ${lon}`;
            submitBtn.disabled = false;

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'User-Agent': 'AttendanceApp/1.0'
                        }
                    }
                );

                const data = await response.json();

                if (data.address) {
                    let place =
                        data.address.suburb ||
                        data.address.village ||
                        data.address.town ||
                        data.address.city ||
                        '';

                    let state = data.address.state || '';
                    let country = data.address.country || '';

                    const fullLocation = `${place}, ${state}, ${country}`
                        .replace(/^,|,$/g, '')
                        .trim();

                    if (fullLocation) {
                        locationInput.value = fullLocation;
                    }
                }

            } catch (e) {
                console.warn('Location name fetch failed');
            }
        },
        function () {
            alert('Location permission is required');
        },
        {
            enableHighAccuracy: false,
            timeout: 7000,
            maximumAge: 60000
        }
    );
});
</script>




</body>
</html>
