<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1d4ed8, #4f46e5);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      width: 380px;
      background: #fff;
      border-radius: 22px;
      padding: 30px;
      box-shadow: 0 18px 50px rgba(0,0,0,0.25);
    }

    .card h2 {
      margin: 0;
      font-size: 28px;
      color: #111827;
    }

    .card p {
      margin: 8px 0 20px;
      color: #6b7280;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 6px;
      color: #374151;
      font-size: 14px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 14px 0 18px;
    }

    .row a {
      color: #2563eb;
      text-decoration: none;
      font-size: 13px;
    }

    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 14px;
      background: #2563eb;
      color: #fff;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background: #1d4ed8;
    }

    .or {
      text-align: center;
      margin: 18px 0;
      color: #9ca3af;
    }

    /* Google button */
    .google-btn {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      padding: 10px;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      cursor: pointer;
      text-decoration: none;
      color: #111827;
      transition: 0.3s;
    }

    .google-btn:hover {
      background: #f3f4f6;
    }

    .google-logo {
      width: 22px;
      height: 22px;
    }

    .footer {
      text-align: center;
      margin-top: 16px;
      color: #6b7280;
      font-size: 13px;
    }

    .footer a {
      color: #2563eb;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>Welcome Back</h2>
  <p>Login to your account</p>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="input-group">
      <label>Email</label>
      <input type="email" name="email" required autofocus>
    </div>

    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>

    <div class="row">
      <label>
        <input type="checkbox" name="remember">
        Remember me
      </label>

      <a href="{{ route('password.request') }}">Forgot password?</a>
    </div>

    <button class="btn" type="submit">Login</button>
  </form>

  <div class="or">OR</div>

  <!-- Google Button with center logo -->
  <a href="{{ route('google.redirect') }}" class="google-btn">
      <img src="{{ asset('images/google.jpg') }}" class="google-logo" alt="Google">
      <span>Continue with Google</span>
  </a>

  <div class="footer">
    Don't have an account?
    <a href="{{ route('register') }}">Sign Up</a>
  </div>
</div>

</body>
</html>
