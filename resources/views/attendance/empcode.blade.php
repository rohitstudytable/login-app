@include('body.headerlink')

<style>
    body {
        font-family: "Inter", sans-serif;
        min-height: 100vh;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Main Card */
    .auth-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(18px);
        border-radius: 18px;
        padding: 35px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.25);
        width: 100%;
        max-width: 420px;
    }

    /* Logo */
    .logo-box img {
        max-width: 140px;
        margin-bottom: 15px;
    }

    /* Heading */
    .auth-title {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        text-align: center;
        margin-bottom: 8px;
    }

    .auth-subtitle {
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-bottom: 25px;
    }

    /* Input Wrapper */
    .input-group {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 14px;
        display: flex;
        align-items: center;
        margin-bottom: 18px;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    .input-group:hover {
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .input-group ion-icon {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.8);
        margin-right: 10px;
    }

    .input-group input {
        border: none;
        outline: none;
        background: transparent;
        width: 90%;
        font-size: 15px;
        color: #fff;
    }

    .input-group input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Button */
    .auth-btn {
        width: 100%;
        border: none;
        padding: 13px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        background: #fff;
        color: #2563eb;
        transition: 0.3s;
    }

    .auth-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
    }

    /* Alerts */
    .alert {
        border-radius: 12px;
        font-size: 14px;

    }
</style>

<body>




    <div class="auth-card">

        {{-- Logo --}}
        <div class="text-center logo-box">
            <img src="{{ asset('images/dd.png') }}" alt="Company Logo">
        </div>

        {{-- Title --}}
        <h2 class="auth-title">Employee / Intern Login</h2>
        <p class="auth-subtitle">
            Welcome back! Please login to continue.
        </p>

        {{-- Login Form --}}
        <form method="POST" action="{{ route('intern.login.submit') }}">
            @csrf

            {{-- Intern Code --}}
            <div class="input-group">
                <ion-icon name="person-outline"></ion-icon>
                <input type="text" name="intern_code" value="{{ old('intern_code') }}"
                    placeholder="Employee / Intern Code" required>
            </div>
            @error('intern_code')
                <small class="text-warning d-block mb-2">{{ $message }}</small>
            @enderror


            {{-- Password --}}
            <div class="input-group">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            @error('password')
                <small class="text-warning d-block mb-2">{{ $message }}</small>
            @enderror

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success text-center mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center mb-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Button --}}
            <button type="submit" class="auth-btn mt-2">
                Login
            </button>

        </form>

    </div>
    <script>
        setTimeout(() => {
            let alerts = document.querySelectorAll(".alert");

            alerts.forEach(alert => {
                alert.style.transition = "0.5s";
                alert.style.opacity = "0";

                setTimeout(() => {
                    alert.remove();
                }, 500);
            });

        }, 5000); // 5 seconds
    </script>

    {{-- Ionicons --}}
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons.js"></script>

</body>