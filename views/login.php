<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PHP WebPhone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Login to WebPhone</h2>
            
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($_SESSION['login_error']); ?>
                    <?php unset($_SESSION['login_error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="api/login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <div class="demo-credentials">
                <p><strong>Demo Credentials:</strong></p>
                <p>Username: demo<br>Password: demo123</p>
            </div>
        </div>
    </div>
    
    <style>
        .error-message {
            background: #f56565;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .demo-credentials {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            font-size: 0.9em;
        }
        
        .demo-credentials p {
            margin: 5px 0;
        }
    </style>
</body>
</html>