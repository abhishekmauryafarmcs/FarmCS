<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FarmCS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #27ae60;
            --bg-color: #f0f2f5;
            --text-color: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
            background: none;
            position: relative;
        }

        .background-iframe {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            border: none;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            z-index: 1;
            cursor: pointer;
        }

        .login-container {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 2rem;
            border-radius: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 3;
            margin: 20px;
        }

        .login-card:hover {
            background: rgba(255, 255, 255, 0.85);
            transform: translateY(-5px);
            box-shadow: 
                0 15px 45px rgba(0, 0, 0, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.4);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
            transform: translateZ(30px);
            position: relative;
        }

        .login-header img {
            width: 80px;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            color: #000000;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #000000;
            font-weight: 500;
        }

        .logo img {
            width: 70px;
            height: auto;
            transition: transform 0.3s ease;
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .logo img {
                width: 50px;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            transform-style: preserve-3d;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 25px;
            font-size: 1rem;
            color: #000000;
            font-weight: 500;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.65);
        }

        .form-group input::placeholder {
            color: rgba(0, 0, 0, 0.7);
            font-weight: 500;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            transform-style: preserve-3d;
            position: relative;
        }

        .login-btn:hover {
            background: var(--secondary-color);
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #1e2332;
            font-weight: 500;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
        }

        .signup-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
            text-decoration: none;
            margin-left: 5px;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
        }

        .signup-link a:hover {
            color: var(--secondary-color);
            background-color: rgba(46, 204, 113, 0.1);
            transform: translateY(-1px);
        }

        .signup-link a:active {
            transform: translateY(0);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #666;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .forgot-password {
            color: var(--primary-color);
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
        }

        .forgot-password:hover::after {
            transform: scaleX(1);
        }

        .error-message {
            color: #d32f2f;
            font-weight: 500;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        .success-message {
            color: var(--primary-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        @media (max-width: 480px) {
            .login-card {
                margin: 1rem;
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            html, body {
                overflow-y: auto;
            }

            .background-iframe {
                display: none;
            }

            body {
                background: var(--bg-color);
            }

            .overlay {
                background: none;
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }

            .login-container {
                padding: 20px;
                height: auto;
                min-height: 100vh;
            }

            .login-card {
                max-width: 100%;
                width: 100%;
                margin: 0;
                padding: 1.5rem;
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }

            .login-header img {
                width: 60px;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .form-group input {
                padding: 10px;
                font-size: 0.9rem;
            }

            .login-btn {
                padding: 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1rem;
            }

            .login-header img {
                width: 50px;
            }

            .login-header h1 {
                font-size: 1.3rem;
            }

            .form-group input {
                padding: 8px;
                font-size: 0.85rem;
            }

            .login-btn {
                padding: 8px;
                font-size: 0.85rem;
            }
        }

        /* Ensure form elements are touch-friendly */
        @media (max-width: 768px) {
            .form-group input,
            .login-btn {
                min-height: 44px;
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>
    <script src="js/login.js"></script>
    <script src="js/ui_effects.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            const loginCard = document.querySelector('.login-card');

            // Add click event listener to the login container
            loginContainer.addEventListener('click', function(event) {
                // If the click is on the login container but not on the login card
                if (!loginCard.contains(event.target)) {
                    window.location.href = 'index.php';
                }
            });

            // Prevent clicks on the login card from bubbling up
            loginCard.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>
</head>
<body>
    <iframe src="index.php" class="background-iframe" title="Background"></iframe>
    <div class="overlay"></div>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <img src="images/FarmCSlogo.png" alt="FarmCS Logo">
                </div>
                <h1>Welcome Back</h1>
                <p>Login to your FarmCS Account</p>
            </div>
            <div id="errorDisplay" style="display: none; color: #d32f2f; margin-bottom: 15px; padding: 10px; border-radius: 5px; background-color: rgba(211, 47, 47, 0.1);"></div>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" pattern="[0-9]{10}" maxlength="10" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe" name="rememberMe">
                        Remember me
                    </label>
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>
                <div class="form-group">
                    <button type="submit" class="login-btn">Login</button>
                </div>
                <div class="form-group signup-link">
                    Don't have an account? <a href="signup.php" class="page-transition">Sign up</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
</body>
</html>
