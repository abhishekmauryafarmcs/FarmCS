<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - FarmCS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #27ae60;
            --bg-color: #f0f2f5;
            --text-color: #1e2332;
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

        .signup-container {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .signup-card {
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

        .signup-card::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 35px;
            background: linear-gradient(135deg, 
                rgba(46, 204, 113, 0.3) 0%,
                rgba(39, 174, 96, 0.3) 100%
            );
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: -1;
        }

        .signup-card:hover {
            background: rgba(255, 255, 255, 0.85);
            transform: translateY(-5px);
            box-shadow: 
                0 15px 45px rgba(0, 0, 0, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.4);
        }

        .signup-header {
            text-align: center;
            margin-bottom: 20px;
            transform-style: preserve-3d;
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
        
        .signup-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            transform: translateZ(30px);
            transition: transform 0.3s ease;
        }

        .signup-header h1 {
            color: #000000;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 5px;
            transform: translateZ(20px);
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .signup-header p {
            color: #1e2332;
            font-weight: 500;
            opacity: 0.9;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
        }
        
        .signup-form {
            display: grid;
            gap: 8px;
            transform-style: preserve-3d;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .signup-form input,
        .signup-form select {
            width: 100%;
            padding: 8px 10px;
            border: 2px solid transparent;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            font-size: 14px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            color: #000000;
            transition: all 0.3s ease;
        }

        .signup-form input::placeholder,
        .signup-form select::placeholder {
            color: rgba(0, 0, 0, 0.7);
            font-size: 13px;
            font-weight: 500;
        }

        .signup-form input:focus,
        .signup-form select:focus {
            outline: none;
            border-color: var(--primary-color);
            transform: translateZ(5px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.2);
            background: rgba(255, 255, 255, 0.65);
        }
        
        .signup-btn {
            width: 100%;
            padding: 8px;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            transform: translateZ(15px);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            margin-top: 10px;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .signup-btn:hover {
            background: var(--secondary-color);
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
            transform: translateZ(10px);
            transition: transform 0.3s ease;
            color: #1e2332;
            font-weight: 500;
        }

        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.3s ease;
            margin-left: 5px;
        }

        .login-link a:hover {
            color: var(--secondary-color);
        }

        .error-message {
            color: #d32f2f;
            font-size: 13px;
            font-weight: 500;
            margin-top: 4px;
            display: none;
            transform: translateZ(5px);
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0) translateZ(0);
            }
            to {
                opacity: 0;
                transform: translateX(50px) translateZ(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-50px) translateZ(0);
            }
            to {
                opacity: 1;
                transform: translateX(0) translateZ(0);
            }
        }

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @media (min-width: 600px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid transparent;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            font-size: 14px;
            color: var(--text-color);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            caret-color: var(--primary-color);
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 5px 20px rgba(46, 204, 113, 0.15);
            background: rgba(255, 255, 255, 0.2);
        }

        .form-group input::placeholder {
            color: #9ca3af;
            opacity: 0.8;
            font-size: 13px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #f0f2f5;
            padding: 10px;
            text-align: center;
            font-size: 13px;
            color: #1e2332;
            font-weight: 500;
            border-top: 1px solid #e5e5e5;
            z-index: 1000;
        }

        .footer-content {
            margin: 0 auto;
            max-width: 400px;
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

            .signup-container {
                padding: 20px;
                height: auto;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .signup-card {
                max-width: 100%;
                width: 100%;
                margin: 0;
                padding: 1.5rem;
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }

            .signup-header img {
                width: 60px;
                height: 60px;
            }

            .signup-header h1 {
                font-size: 18px;
            }

            .signup-header p {
                font-size: 14px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .signup-form input,
            .signup-form select {
                padding: 10px;
                font-size: 14px;
            }

            .signup-btn {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .signup-card {
                padding: 1rem;
            }

            .signup-header img {
                width: 50px;
                height: 50px;
            }

            .signup-header h1 {
                font-size: 16px;
            }

            .signup-header p {
                font-size: 12px;
            }

            .signup-form input,
            .signup-form select {
                padding: 8px;
                font-size: 13px;
            }

            .signup-btn {
                padding: 8px;
                font-size: 13px;
            }
        }

        /* Ensure form elements are touch-friendly */
        @media (max-width: 768px) {
            .signup-form input,
            .signup-form select,
            .signup-btn {
                min-height: 44px;
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>
    <script src="js/districts.js"></script>
    <script src="js/signup.js"></script>
    <script src="js/ui_effects.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize district dropdown based on current state value
            const stateSelect = document.getElementById('state');
            if (stateSelect && stateSelect.value) {
                loadDistricts(stateSelect.value);
            }

            const signupContainer = document.querySelector('.signup-container');
            const signupCard = document.querySelector('.signup-card');

            // Add click event listener to the signup container
            signupContainer.addEventListener('click', function(event) {
                // If the click is on the signup container but not on the signup card
                if (!signupCard.contains(event.target)) {
                    window.location.href = 'index.php';
                }
            });

            // Prevent clicks on the signup card from bubbling up
            signupCard.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>
</head>
<body>
    <iframe src="index.php" class="background-iframe" title="Background"></iframe>
    <div class="overlay"></div>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <div class="logo">
                    <img src="images/FarmCSlogo.png" alt="FarmCS Logo">
                </div>
                <h1>Create Account</h1>
                <p>Join FarmCS Smart Farming Platform</p>
            </div>
            <div id="errorDisplay" style="display: none; color: #d32f2f; margin-bottom: 15px; padding: 10px; border-radius: 5px; background-color: rgba(211, 47, 47, 0.1);"></div>
            <form class="signup-form" id="signupForm" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                        <div class="error-message" id="firstNameError"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                        <div class="error-message" id="lastNameError"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" pattern="[0-9]{10}" maxlength="10" required>
                    <div class="error-message" id="mobileError"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <div class="error-message" id="passwordError"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                        <div class="error-message" id="confirmPasswordError"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <select id="state" name="state" required>
                            <option value="">Select State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                        <div class="error-message" id="stateError"></div>
                    </div>
                    <div class="form-group">
                        <select id="district" name="district" required disabled>
                            <option value="">Select District</option>
                        </select>
                        <div class="error-message" id="districtError"></div>
                    </div>
                </div>

                <button type="submit" class="signup-btn">Create Account</button>
                
                <div class="login-link">
                    Already have an account? <a href="login.php" class="page-transition">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
</body>
</html>
