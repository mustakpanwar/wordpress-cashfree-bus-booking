<?php
session_start();

// If already logged in redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /admin-dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Admin credentials — change these!
    if ($username === 'admin' && $password === 'YOUR_ADMIN_PASSWORD') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: /admin-dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Mumbai Darshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d35400, #2c3e50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 180px;
        }
        .logo h2 {
            color: #d35400;
            font-size: 20px;
            margin-top: 10px;
        }
        .logo p {
            color: #888;
            font-size: 13px;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
        }
        .form-group input {
            width: 100%;
            padding: 13px 15px 13px 45px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: border 0.3s;
            outline: none;
        }
        .form-group input:focus {
            border-color: #d35400;
            box-shadow: 0 0 0 4px rgba(211,84,0,0.1);
        }
        .error-msg {
            background: #fde8e8;
            color: #e74c3c;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #d35400, #e67e22);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 5px 15px rgba(211,84,0,0.3);
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(211,84,0,0.4);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }
        .back-link a {
            color: #d35400;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo">
        <img src="https://YOUR-DOMAIN.com/wp-content/uploads/2024/10/mumbai-darshan-bus-logo.png" alt="Mumbai Darshan Bus">
        <h2>Admin Panel</h2>
        <p>Sign in to manage bookings</p>
    </div>

    <?php if ($error): ?>
    <div class="error-msg">❌ <?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <div class="input-wrap">
                <span>👤</span>
                <input type="text" name="username" placeholder="Enter username" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <span>🔒</span>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
        </div>
        <button type="submit" class="login-btn">🔐 Login to Dashboard</button>
    </form>

    <div class="back-link">
        <a href="https://YOUR-DOMAIN.com">← Back to Website</a>
    </div>
</div>
</body>
</html>
