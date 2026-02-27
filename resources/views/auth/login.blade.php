<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont;
            background: linear-gradient(135deg, #1e3a8a, #312e81);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(18px);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .login-header {
            text-align: center;
            margin-bottom: 26px;
        }

        .login-header img {
            width: 110px;
            margin-bottom: 10px;
        }

        .login-header h2 {
            margin: 0;
            color: #fff;
            font-size: 24px;
            font-weight: 700;
        }

        .login-header p {
            margin-top: 6px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.9);
        }

        .input-group input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.3);
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 22px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }

        .row a {
            color: #bfdbfe;
            text-decoration: none;
        }

        .row a:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            background: #ffffff;
            color: #1e3a8a;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
        }

        .divider {
            text-align: center;
            margin: 22px 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 11px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .google-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .google-btn img {
            width: 22px;
            height: 22px;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
        }

        .footer a {
            color: #bfdbfe;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="login-header">
        <img src="{{ asset('images/dd.png') }}" alt="Admin Logo">
        <h2>Admin Login</h2>
        <p>Access the admin dashboard</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="admin@example.com" required autofocus>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="row">
            <label>
                <input type="checkbox" name="remember">
                Remember me
            </label>

            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button class="login-btn" type="submit">
            Login to Dashboard
        </button>
    </form>

    <div class="divider">OR</div>

    <a href="{{ route('google.redirect') }}" class="google-btn">
        <img src="{{ asset('images/google.jpg') }}" alt="Google">
        <span>Continue with Google</span>
    </a>

    <div class="footer">
        New admin?
        <a href="{{ route('register') }}">Create account</a>
    </div>

</div>

</body>
</html>