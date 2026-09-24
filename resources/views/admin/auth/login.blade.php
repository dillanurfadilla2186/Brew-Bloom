<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Brew & Bloom</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1E1B18 0%, #3D291D 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #FFFFFF;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #1D1D1D;
            margin-top: 10px;
        }
        .login-header p {
            color: #777777;
            font-size: 13px;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #C98A52;
            box-shadow: 0 0 0 3px rgba(201, 138, 82, 0.2);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #C98A52;
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #b57943;
            transform: translateY(-2px);
        }
        .demo-box {
            margin-top: 25px;
            padding: 15px;
            background: #FDF8F3;
            border-radius: 10px;
            border: 1px dashed #C98A52;
            font-size: 12px;
            color: #555555;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }
        .back-link a {
            color: #C98A52;
            text-decoration: none;
        }
        .error-alert {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <span style="font-size: 36px;">☕</span>
            <h1>Brew & Bloom</h1>
            <p>Admin Control Panel</p>
        </div>

        @if($errors->any())
            <div class="error-alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Administrator</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', 'admin@brewbloom.com') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" value="admin123" required>
            </div>

            <button type="submit" class="btn-login">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="demo-box">
            <strong>Kredensial Default:</strong><br>
            Email: <code>admin@brewbloom.com</code><br>
            Password: <code>admin123</code>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">← Kembali ke Website</a>
        </div>
    </div>

</body>
</html>
