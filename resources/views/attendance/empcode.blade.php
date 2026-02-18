@include('body.headerlink')

<style>
/* ========== ROOT VARIABLES ========== */
:root {
    --primary: #6366f1;
    --primary-light: #818cf8;
    --accent: #a78bfa;
    --glass-bg: rgba(255, 255, 255, 0.06);
    --glass-border: rgba(255, 255, 255, 0.12);
    --text-primary: #f1f5f9;
    --text-secondary: #94a3b8;
    --input-bg: rgba(255, 255, 255, 0.08);
    --input-border: rgba(255, 255, 255, 0.15);
    --danger: #f87171;
    --success: #34d399;
}

/* ========== ANIMATED BACKGROUND ========== */
body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #1a1a2e);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    overflow-x: hidden;
}

@keyframes gradientShift {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ========== PARTICLE CANVAS ========== */
#particles-canvas {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    z-index: 0;
    pointer-events: none;
}

/* ========== MAIN WRAPPER ========== */
.login-wrapper {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* ========== GLASSMORPHISM CARD ========== */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 48px 40px 40px;
    width: 100%;
    max-width: 440px;
    box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.08);
    animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    transform: translateY(40px) scale(0.96);
}

@keyframes cardEntrance {
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* ========== GLOW ACCENT ========== */
.glass-card::before {
    content: '';
    position: absolute;
    top: -1px; left: -1px; right: -1px; bottom: -1px;
    border-radius: 25px;
    background: linear-gradient(135deg, rgba(99,102,241,0.3), transparent 40%, transparent 60%, rgba(167,139,250,0.2));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.5s;
}

.glass-card:hover::before {
    opacity: 1;
}

/* ========== LOGO ========== */
.logo-container {
    text-align: center;
    margin-bottom: 8px;
    animation: fadeInDown 0.6s 0.3s ease both;
}

.logo-container img {
    max-width: 140px;
    height: auto;
    filter: drop-shadow(0 4px 20px rgba(99, 102, 241, 0.4));
    transition: transform 0.4s ease, filter 0.4s ease;
}

.logo-container img:hover {
    transform: scale(1.08) rotate(-2deg);
    filter: drop-shadow(0 8px 30px rgba(99, 102, 241, 0.6));
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ========== HEADER ========== */
.login-title {
    text-align: center;
    color: var(--text-primary);
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: -0.5px;
    animation: fadeInDown 0.6s 0.4s ease both;
}

.login-subtitle {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 32px;
    animation: fadeInDown 0.6s 0.5s ease both;
}

/* ========== FORM GROUPS ========== */
.form-group {
    position: relative;
    margin-bottom: 24px;
    animation: fadeInUp 0.5s ease both;
}

.form-group:nth-child(1) { animation-delay: 0.5s; }
.form-group:nth-child(2) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ========== FLOATING LABEL INPUTS ========== */
.input-wrapper {
    position: relative;
}

.input-wrapper .form-input {
    width: 100%;
    padding: 16px 18px 8px;
    font-size: 1rem;
    color: var(--text-primary);
    background: var(--input-bg);
    border: 1.5px solid var(--input-border);
    border-radius: 14px;
    outline: none;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
}

.input-wrapper .form-input::placeholder {
    color: transparent;
}

.input-wrapper .floating-label {
    position: absolute;
    top: 50%;
    left: 18px;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 0.95rem;
    pointer-events: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    background: transparent;
    padding: 0 4px;
}

.input-wrapper .form-input:focus ~ .floating-label,
.input-wrapper .form-input:not(:placeholder-shown) ~ .floating-label {
    top: 6px;
    transform: translateY(0);
    font-size: 0.72rem;
    color: var(--primary-light);
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.input-wrapper .form-input:focus {
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15), 0 0 20px rgba(99, 102, 241, 0.1);
}

/* ========== INPUT ICONS ========== */
.input-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 1.2rem;
    transition: color 0.3s;
    display: flex;
    align-items: center;
}

.input-wrapper .form-input:focus ~ .input-icon {
    color: var(--primary-light);
}

.input-wrapper .form-input {
    padding-right: 48px;
}

/* ========== PASSWORD TOGGLE ========== */
.password-toggle {
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    color: var(--text-secondary);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    transition: color 0.3s;
}

.password-toggle:hover {
    color: var(--primary-light);
}

/* ========== ERROR MSG ========== */
.field-error {
    color: var(--danger);
    font-size: 0.8rem;
    margin-top: 6px;
    padding-left: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: shakeIn 0.4s ease;
}

@keyframes shakeIn {
    0%, 100% { transform: translateX(0); }
    20%      { transform: translateX(-6px); }
    40%      { transform: translateX(6px); }
    60%      { transform: translateX(-4px); }
    80%      { transform: translateX(4px); }
}

.is-invalid {
    border-color: var(--danger) !important;
    box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.15) !important;
}

/* ========== SUBMIT BUTTON ========== */
.btn-login {
    width: 100%;
    padding: 14px;
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border: none;
    border-radius: 14px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-family: 'Inter', sans-serif;
    animation: fadeInUp 0.5s 0.7s ease both;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
}

.btn-login:active {
    transform: translateY(0) scale(0.98);
}

/* Ripple */
.btn-login .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.35);
    transform: scale(0);
    animation: rippleEffect 0.6s linear;
    pointer-events: none;
}

@keyframes rippleEffect {
    to { transform: scale(4); opacity: 0; }
}

/* Loading */
.btn-login.loading {
    pointer-events: none;
    opacity: 0.85;
}

.btn-login.loading .btn-text {
    visibility: hidden;
}

.btn-login .spinner {
    display: none;
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 22px; height: 22px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

.btn-login.loading .spinner {
    display: block;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* ========== ALERTS ========== */
.custom-alert {
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: fadeInDown 0.5s 0.3s ease both;
    border: 1px solid;
}

.alert-success-custom {
    background: rgba(52, 211, 153, 0.1);
    border-color: rgba(52, 211, 153, 0.3);
    color: var(--success);
}

.alert-danger-custom {
    background: rgba(248, 113, 113, 0.1);
    border-color: rgba(248, 113, 113, 0.3);
    color: var(--danger);
}

.custom-alert .alert-icon {
    font-size: 1.3rem;
    flex-shrink: 0;
}

.custom-alert .close-alert {
    margin-left: auto;
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s;
    font-size: 1.1rem;
}

.custom-alert .close-alert:hover {
    opacity: 1;
}

/* ========== DIVIDER ========== */
.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: var(--text-secondary);
    font-size: 0.8rem;
    animation: fadeInUp 0.5s 0.65s ease both;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--glass-border);
}

/* ========== FOOTER TEXT ========== */
.login-footer {
    text-align: center;
    margin-top: 28px;
    color: var(--text-secondary);
    font-size: 0.82rem;
    animation: fadeInUp 0.5s 0.8s ease both;
}

.login-footer ion-icon {
    vertical-align: middle;
    font-size: 1rem;
    color: var(--accent);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 480px) {
    .glass-card {
        padding: 0;
        border-radius: 0;
        background: transparent;
        border: none;
        box-shadow: none;
        backdrop-filter: none;
    }
    .login-title {
        font-size: 1.35rem;
    }
}
</style>

{{-- ========== PARTICLE BACKGROUND ========== --}}
<canvas id="particles-canvas"></canvas>

<body>
<div class="login-wrapper">
    <div class="glass-card" id="loginCard">

        {{-- ===== ALERTS ===== --}}
        @if(session('success'))
            <div class="custom-alert alert-success-custom">
                <ion-icon name="checkmark-circle" class="alert-icon"></ion-icon>
                <span>{{ session('success') }}</span>
                <button class="close-alert" onclick="this.parentElement.remove()">
                    <ion-icon name="close"></ion-icon>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="custom-alert alert-danger-custom">
                <ion-icon name="alert-circle" class="alert-icon"></ion-icon>
                <span>{{ session('error') }}</span>
                <button class="close-alert" onclick="this.parentElement.remove()">
                    <ion-icon name="close"></ion-icon>
                </button>
            </div>
        @endif

        {{-- ===== LOGO ===== --}}
        <div class="logo-container">
            <img src="{{ asset('images/dd.png') }}" alt="Company Logo">
        </div>

        {{-- ===== TITLE ===== --}}
        <h1 class="login-title">Welcome Back</h1>
        <p class="login-subtitle">Sign in to your employee portal</p>

        {{-- ===== FORM ===== --}}
        <form method="POST" action="{{ route('intern.login.submit') }}" id="loginForm">
            @csrf

            {{-- Employee / Intern Code --}}
            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text"
                           class="form-input @error('intern_code') is-invalid @enderror"
                           name="intern_code"
                           id="internCode"
                           value="{{ old('intern_code') }}"
                           placeholder="Employee Code"
                           required
                           autocomplete="username">
                    <label class="floating-label" for="internCode">Employee / Intern Code</label>
                    <span class="input-icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </span>
                </div>
                @error('intern_code')
                    <div class="field-error">
                        <ion-icon name="warning-outline"></ion-icon>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <div class="input-wrapper">
                    <input type="password"
                           class="form-input @error('password') is-invalid @enderror"
                           name="password"
                           id="passwordField"
                           placeholder="Password"
                           required
                           autocomplete="current-password">
                    <label class="floating-label" for="passwordField">Password</label>
                    <button type="button" class="input-icon password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                        <ion-icon name="eye-outline" id="eyeIcon"></ion-icon>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">
                        <ion-icon name="warning-outline"></ion-icon>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <span class="spinner"></span>
            </button>
        </form>

        <div class="divider">Secure Access</div>

        <div class="login-footer">
            <ion-icon name="shield-checkmark-outline"></ion-icon>
            Protected by enterprise-grade security
        </div>

    </div>
</div>

{{-- ========== SCRIPTS ========== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

<script>
// ========== FLOATING PARTICLES ==========
(function() {
    const canvas = document.getElementById('particles-canvas');
    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouse = { x: null, y: null };
    const maxDistance = 120;

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    window.addEventListener('mousemove', e => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 0.5;
            this.speedX = (Math.random() - 0.5) * 0.6;
            this.speedY = (Math.random() - 0.5) * 0.6;
            this.opacity = Math.random() * 0.5 + 0.1;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x > canvas.width) this.x = 0;
            if (this.x < 0) this.x = canvas.width;
            if (this.y > canvas.height) this.y = 0;
            if (this.y < 0) this.y = canvas.height;

            // Mouse interaction — gently push particles
            if (mouse.x !== null) {
                const dx = this.x - mouse.x;
                const dy = this.y - mouse.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 100) {
                    this.x += dx * 0.02;
                    this.y += dy * 0.02;
                }
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(139, 92, 246, ${this.opacity})`;
            ctx.fill();
        }
    }

    function init() {
        particles = [];
        const count = Math.min(80, Math.floor((canvas.width * canvas.height) / 12000));
        for (let i = 0; i < count; i++) {
            particles.push(new Particle());
        }
    }

    function connectParticles() {
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < maxDistance) {
                    const opacity = (1 - dist / maxDistance) * 0.15;
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(139, 92, 246, ${opacity})`;
                    ctx.lineWidth = 0.6;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            p.update();
            p.draw();
        });
        connectParticles();
        requestAnimationFrame(animate);
    }

    init();
    animate();
    window.addEventListener('resize', init);
})();

// ========== PASSWORD TOGGLE ==========
document.getElementById('togglePassword').addEventListener('click', function() {
    const field = document.getElementById('passwordField');
    const icon = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.setAttribute('name', 'eye-off-outline');
    } else {
        field.type = 'password';
        icon.setAttribute('name', 'eye-outline');
    }
});

// ========== BUTTON RIPPLE EFFECT ==========
document.getElementById('loginBtn').addEventListener('click', function(e) {
    const btn = this;
    const rect = btn.getBoundingClientRect();
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    const size = Math.max(rect.width, rect.height);
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
    btn.appendChild(ripple);
    setTimeout(() => ripple.remove(), 600);
});

// ========== LOADING STATE ON SUBMIT ==========
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.classList.add('loading');
});

// ========== INPUT FOCUS ANIMATION ==========
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.form-group').style.transform = 'scale(1.02)';
        this.closest('.form-group').style.transition = 'transform 0.2s ease';
    });
    input.addEventListener('blur', function() {
        this.closest('.form-group').style.transform = 'scale(1)';
    });
});
</script>

</body>
</html>
