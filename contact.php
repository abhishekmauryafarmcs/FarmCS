<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FarmCS</title>
    <link rel="icon" type="image/png" href="images/FarmCSlogo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary-green: #2E7D32;
            --secondary-green: #4CAF50;
            --accent-orange: #FF6B35;
            --tech-blue: #1976D2;
            --white: #ffffff;
            --light-gray: #f5f5f5;
            --dark-text: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--dark-text);
        }

        /* Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100vw;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .main-content {
            width: 100%;
            max-width: 100vw;
            margin-top: 80px;
            overflow-x: hidden;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            min-width: 60px;
        }

        .logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 10px;
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }

        nav {
            flex: 1;
            display: flex;
            justify-content: center;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
            width: auto;
        }

        .nav-links a {
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 15px;
        }

        .nav-links a:hover {
            color: var(--primary-green);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .login-btn, .signup-btn {
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .login-btn {
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            background: transparent;
        }

        .signup-btn {
            background: var(--primary-green);
            color: white;
            border: 2px solid var(--primary-green);
        }

        .login-btn:hover {
            background: rgba(46, 125, 50, 0.1);
        }

        .signup-btn:hover {
            background: #1B5E20;
            border-color: #1B5E20;
        }

        .hamburger-menu {
            display: none;
            cursor: pointer;
            padding: 5px;
        }

        .bar {
            width: 25px;
            height: 3px;
            background-color: var(--primary-green);
            margin: 4px 0;
            transition: 0.4s;
            border-radius: 3px;
        }

        @media screen and (max-width: 768px) {
            .hamburger-menu {
                display: block;
            }

            .nav-links {
                display: none;
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background-color: var(--white);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 20px;
                transition: 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                left: 0;
                display: flex !important;
            }

            .nav-links a {
                margin: 15px 0;
                font-size: 1.2rem;
                width: 100%;
                text-align: center;
                padding: 10px 0;
            }

            .hamburger-menu.active .bar:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }

            .hamburger-menu.active .bar:nth-child(2) {
                opacity: 0;
            }

            .hamburger-menu.active .bar:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }

            .auth-buttons {
                gap: 0.5rem;
            }

            .login-btn, .signup-btn {
                padding: 6px 12px;
                font-size: 14px;
            }
        }

        /* Contact Container */
        .contact-container {
            padding: 120px 5% 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header-section h1 {
            color: var(--primary-green);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .header-section p {
            color: var(--dark-text);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        /* Contact Form */
        .contact-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid transparent;
            border-radius: 25px;
            background: var(--light-gray);
            transition: all 0.3s;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
        }

        textarea.form-input {
            min-height: 150px;
            resize: vertical;
            padding-left: 1rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 25px;
            background: var(--primary-green);
            color: var(--white);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Contact Info */
        .contact-info {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item i {
            width: 40px;
            height: 40px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-size: 1.2rem;
        }

        .info-item a {
            color: var(--dark-text);
            text-decoration: none;
            transition: color 0.3s;
        }

        .info-item a:hover {
            color: var(--primary-green);
        }

        .map-container {
            margin-top: 2rem;
            border-radius: 10px;
            overflow: hidden;
            height: 300px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--primary-green);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Success Message */
        .success-message {
            display: none;
            background: #4CAF50;
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }

            .logo img {
                width: 50px;
            }
        }

        /* Responsive Styles */
        @media screen and (max-width: 1200px) {
            .container {
                width: 95%;
                padding: 0 20px;
            }

            .contact-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .contact-form,
            .contact-info {
                padding: 2rem;
            }

            .hero-content {
                padding: 2rem 1rem;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            section {
                padding: 3rem 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .contact-methods {
                flex-direction: column;
            }

            .contact-method {
                width: 100%;
                margin: 1rem 0;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }
        }

        @media screen and (max-width: 480px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .contact-form,
            .contact-info {
                padding: 1.5rem;
            }

            input,
            textarea {
                padding: 0.8rem;
            }

            .contact-method i {
                font-size: 2rem;
            }
        }

        /* Footer */
        .footer {
            background: var(--white);
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Dark Mode Global Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #fff;
        }

        /* Hero Section Dark Mode */
        body.dark-mode .contact-hero {
            background-image: 
                linear-gradient(rgba(26, 26, 26, 0.7), rgba(42, 42, 42, 0.7)), 
                url('images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
        }

        body.dark-mode .contact-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(26, 26, 26, 0.5);
            z-index: 1;
        }

        body.dark-mode .contact-hero-content {
            position: relative;
            z-index: 2;
        }

        body.dark-mode .contact-hero-content h1 {
            color: #fff;
        }

        body.dark-mode .contact-hero-content p {
            color: #e0e0e0;
        }

        /* Contact Form Dark Mode */
        body.dark-mode .contact-section {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
        }

        body.dark-mode .contact-form {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        body.dark-mode .contact-form:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .contact-form h2 {
            color: #fff;
        }

        body.dark-mode .contact-form .form-group label {
            color: #e0e0e0;
        }

        body.dark-mode .contact-form .form-control {
            background-color: #2a2a2a;
            color: #fff;
            border: 1px solid #444;
        }

        body.dark-mode .contact-form .form-control:focus {
            background-color: #333;
            border-color: var(--primary-green);
            box-shadow: 0 0 10px rgba(46, 125, 50, 0.3);
        }

        body.dark-mode .contact-form .btn-submit {
            background-color: var(--primary-green);
            color: white;
            border: 2px solid var(--primary-green);
            transition: all 0.3s ease;
        }

        body.dark-mode .contact-form .btn-submit:hover {
            background-color: var(--secondary-green);
            border-color: var(--secondary-green);
        }

        /* Contact Info Dark Mode */
        body.dark-mode .contact-info {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        body.dark-mode .contact-info:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--accent-orange);
        }

        body.dark-mode .contact-info h3 {
            color: #fff;
        }

        body.dark-mode .contact-info p {
            color: #e0e0e0;
        }

        body.dark-mode .contact-info i {
            color: var(--primary-green);
        }

        /* Dark Mode Header Styles */
        body.dark-mode header {
            background: rgba(26, 26, 26, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .logo {
            color: #fff;
        }

        body.dark-mode .nav-links a {
            color: #e0e0e0;
        }

        body.dark-mode .nav-links a:hover {
            color: var(--primary-green);
        }

        /* Dark Mode Footer Styles */
        body.dark-mode .footer {
            background-color: #1a1a1a;
            border-top: 1px solid #333;
            color: #e0e0e0;
        }

        body.dark-mode .footer-content {
            background-color: transparent;
        }

        body.dark-mode .footer-content p {
            color: #e0e0e0;
        }

        /* Dark mode toggle button */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--primary-green);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle i {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .dark-mode-toggle:hover {
            background-color: var(--secondary-green);
            transform: scale(1.1);
        }

        .dark-mode-toggle.dark-mode {
            background-color: #333;
        }

        .dark-mode-toggle.dark-mode i {
            transform: rotate(180deg);
        }

        /* Fixed Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            gap: 20px;
            height: 70px;
        }

        /* Adjust content spacing for fixed header */
        .contact-container {
            padding-top: 90px;
            padding-bottom: 80px;
            position: relative;
            z-index: 1;
        }

        @media screen and (max-width: 768px) {
            header {
                padding: 10px;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background-color: var(--white);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 20px;
                transition: 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex !important;
                z-index: 999;
            }

            .contact-container {
                padding-top: 90px;
                padding-bottom: 80px;
            }
        }

        /* Dark Mode Header Styles */
        body.dark-mode header {
            background: rgba(26, 26, 26, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .dashboard-btn {
            background: #2E7D32;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(46, 125, 50, 0.08);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            text-decoration: none;
            margin-right: 10px;
        }
        .dashboard-btn:hover {
            background: #256026;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(46, 125, 50, 0.15);
        }
        .language-selector {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header>
        <a href="index.php" class="logo">
            <img src="images/FarmCSlogo.png" alt="FarmCS Logo">
        </a>
        <nav>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="index.php#features">Features</a>
                <a href="cropdata.php">Crop Data</a>
                <a href="learn-more.php">Learn More</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
            </div>
        </nav>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="dashboard-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
                <a href="signup.php" class="signup-btn">Sign Up</a>
            <?php endif; ?>
            <div class="language-selector">
                <div id="google_translate_element"></div>
            </div>
        </div>
        <div class="hamburger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </header>

    <div class="contact-container">
        <div class="header-section" data-aos="fade-up">
            <h1>Get in Touch</h1>
            <p>We'd love to hear from you! Whether you have questions about our solutions or need support, we're here to help.</p>
        </div>

        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form" data-aos="fade-right">
                <form id="contactForm">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-input" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-input" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-phone"></i>
                        <input type="tel" class="form-input" placeholder="Phone Number" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-input" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Send Message</button>
                    <div class="success-message">
                        Thank you for your message! We'll get back to you soon.
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info" data-aos="fade-left">
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <a href="mailto:support@farmcs.com">support@farmcs.com</a>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Phone</h3>
                        <a href="tel:+911234567890">+91 123 456 7890</a>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Address</h3>
                        <p>LNCT College, Bhopal, MP, India</p>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3665.6504165918088!2d77.45974937533893!3d23.25006447913415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x397c4264af4bcd7f%3A0x4c4c75785a84c94!2sLNCT%20Group%20of%20Colleges!5e0!3m2!1sen!2sin!4v1704893547943!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" aria-label="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 FarmCS. Made with ❤️ in India</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        // Form Validation and Submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple form validation
            const inputs = this.querySelectorAll('input, textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#ff0000';
                } else {
                    input.style.borderColor = 'transparent';
                }
            });

            if (isValid) {
                // Show success message
                const successMessage = this.querySelector('.success-message');
                successMessage.style.display = 'block';
                
                // Reset form
                this.reset();
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 3000);
            }
        });

        // Phone number validation
        const phoneInput = document.querySelector('input[type="tel"]');
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });

        // Hamburger Menu Toggle
        const hamburger = document.querySelector('.hamburger-menu');
        const navLinks = document.querySelector('.nav-links');
        const body = document.body;

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            }
        });

        // Close menu when clicking on a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });

        // Dark Mode Toggle
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
        }

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            
            // Save dark mode preference
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', null);
            }
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,bn,te,ta,mr,gu,kn,ml,pa',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
function adjustForGoogleTranslate() {
    var banner = document.querySelector('.goog-te-banner-frame');
    if (banner) {
        document.body.classList.add('has-google-banner');
    } else {
        document.body.classList.remove('has-google-banner');
    }
}
window.addEventListener('load', adjustForGoogleTranslate);
setInterval(adjustForGoogleTranslate, 500);
</script>
</body>
</html>
