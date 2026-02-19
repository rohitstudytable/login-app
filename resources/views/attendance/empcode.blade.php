@include('body.headerlink')

<style>
    body {
    background: #567cd6; /* deep navy background */
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
}

/* ------------------ Card ------------------ */
.auth-card {
    background: #1e293b; /* dark card background for contrast */
    border-radius: 14px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6); /* stronger shadow for depth */
}

/* ------------------ Card Header ------------------ */
.auth-header {
    background: #111827; /* slightly darker than card for separation */
    color: #f8fafc; /* light text for readability */
    border-radius: 14px 14px 0 0;
    padding: 15px;
}

.auth-header h5 {
    margin: 0;
    font-weight: 600;
}

/* ------------------ Form Inputs ------------------ */
.form-control {
    border-radius: 10px;
    padding: 12px;
    border: none;
    background-color: #e2e8f0; /* light background for inputs */
    color: #111827; /* dark text inside inputs */
    transition: all 0.2s ease-in-out;
}

.form-control:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(30,64,175,0.3); /* primary color focus ring */
    background-color: #ffffff;
}

/* ------------------ Buttons ------------------ */
.btn-primary {
    background: #1e40af; /* deep blue button */
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    color: #ffffff;
    transition: all 0.3s ease-in-out;
}

.btn-primary:hover {
    background: #1e3aa8; /* slightly darker blue on hover */
    transform: translateY(-1px);
}

/* ------------------ Logo ------------------ */
.logo-box img {
    filter: drop-shadow(0 6px 15px rgba(255,255,255,0.25));
    max-width: 180px;
    width: 100%;
    height: auto;
    margin-bottom: 20px;
}

/* ------------------ Alerts ------------------ */
.alert {
    border-radius: 8px;
    font-size: 14px;
}

.alert-success {
    background-color: #10b9811a; /* subtle green background */
    color: #047857; /* dark green text */
    border: 1px solid #10b981;
}

.alert-danger {
    background-color: #f871711a; /* subtle red background */
    color: #b91c1c; /* dark red text */
    border: 1px solid #dc2626;
}

/* ------------------ Form Labels ------------------ */
.form-label {
    color: #f1f5f9; /* light label text for readability */
    font-weight: 600;
}

</style>

<body>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6 col-lg-4">

        {{-- ===== COMPANY LOGO ===== --}}
        <div class="text-center mb-4 logo-box">
            <img src="{{ asset('images/dd.png') }}" alt="Company Logo">
        </div>

        {{-- ===== LOGIN CARD ===== --}}
        <div class="card auth-card w-100">
            <div class="card-header auth-header text-center">
                <h5>Employee / Intern Login</h5>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('intern.login.submit') }}">
                    @csrf

                    {{-- Employee/Intern Code --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee / Intern Code</label>
                        <input type="text" 
                               class="form-control @error('intern_code') is-invalid @enderror" 
                               name="intern_code" 
                               value="{{ old('intern_code') }}" 
                               placeholder="Enter your ID" 
                               required>
                        @error('intern_code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder="Enter your password" 
                               required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>
