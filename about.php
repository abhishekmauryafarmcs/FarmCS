<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FarmCS</title>
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
            background-color: var(--white);
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
            header {
                padding: 10px;
            }

            .nav-links {
                display: none;
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: var(--white);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 20px;
                transition: 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin: 0;
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

        /* Mobile Navigation Styles */
        @media screen and (max-width: 768px) {
            header {
                padding: 10px;
                gap: 10px;
            }

            .logo img {
                width: 40px;
                height: 40px;
            }

            .hamburger-menu {
                display: block;
                order: 3;
            }

            .auth-buttons {
                order: 2;
            }

            nav {
                order: 1;
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

            .nav-links.active {
                left: 0;
            }

            .nav-links a {
                margin: 15px 0;
                font-size: 1.2rem;
                width: 100%;
                text-align: center;
                padding: 10px 0;
                color: var(--primary-green);
                font-weight: 600;
                border-bottom: 1px solid #eee;
            }

            .nav-links a:hover {
                background-color: rgba(46, 125, 50, 0.1);
                padding-left: 25px;
            }

            .login-btn, .signup-btn {
                padding: 5px 10px;
                font-size: 12px;
            }

            /* Hamburger Animation */
            .hamburger-menu.active .bar:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }

            .hamburger-menu.active .bar:nth-child(2) {
                opacity: 0;
            }

            .hamburger-menu.active .bar:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }

            /* Dark mode styles for mobile menu */
            body.dark-mode .nav-links {
                background-color: #1a1a1a;
                border-left: 1px solid #333;
            }

            body.dark-mode .nav-links a {
                color: #fff;
                border-bottom: 1px solid #333;
            }

            body.dark-mode .nav-links a:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: var(--accent-orange);
            }

            /* Mobile Responsive Content */
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .mission-vision {
                grid-template-columns: 1fr;
                padding: 2rem 5%;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .team-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .hero {
                margin-top: 70px;
                padding-top: 20px;
            }

            /* Ensure content starts below fixed header */
            .learn-more-container,
            .mission-vision,
            .our-story,
            .features,
            .team {
                padding-top: 20px;
            }
        }

        @media screen and (max-width: 360px) {
            header {
                padding: 8px;
                gap: 5px;
            }

            .logo img {
                width: 35px;
                height: 35px;
            }

            .login-btn, .signup-btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }

        /* Hero Section */
        .hero {
            margin-top: 70px;
            min-height: 60vh;
            position: relative;
            z-index: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('images/about us image.jpg') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            padding: 0 1rem;
            margin-bottom: 4rem;
        }

        .hero-content {
            max-width: 800px;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(120deg, var(--white) 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            background-clip: text;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--accent-orange);
            position: relative;
        }

        .hero h1::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 80%;
            height: 3px;
            background: var(--accent-orange);
            animation: borderGlow 2s infinite;
        }

        .hero h2 {
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-weight: 500;
            line-height: 1.4;
            text-align: left;
            max-width: 800px;
            background: linear-gradient(120deg, var(--accent-orange) 0%, #ffb74d 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            padding: 10px 0;
            position: relative;
            margin-left: 20px;
        }

        .hero h2::before {
            content: '';
            position: absolute;
            top: 0;
            left: -20px;
            width: 4px;
            height: 100%;
            background: var(--accent-orange);
            transform: skewX(-15deg);
        }

        /* Mission & Vision */
        .mission-vision {
            padding: 4rem 5%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .mission-card, .vision-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card-icon {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }

        /* Our Story */
        .our-story {
            padding: 4rem 5%;
            background: var(--light-gray);
        }

        .timeline {
            max-width: 800px;
            margin: 2rem auto;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: var(--primary-green);
        }

        .timeline-item {
            margin: 2rem 0;
            position: relative;
            width: 50%;
            padding: 0 2rem;
        }

        .timeline-item:nth-child(odd) {
            left: 0;
        }

        .timeline-item:nth-child(even) {
            left: 50%;
        }

        .timeline-content {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Features */
        .features {
            padding: 4rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        /* Team */
        .team {
            padding: 4rem 5%;
            background: var(--light-gray);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto 0;
        }

        .team-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .team-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }

        /* CTA Section */
        .cta {
            padding: 4rem 5%;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: var(--white);
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-white {
            background: var(--white);
            color: var(--primary-green);
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            color: var(--primary-green);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mission-vision {
                grid-template-columns: 1fr;
            }

            .timeline::before {
                left: 0;
            }

            .timeline-item {
                width: 100%;
                left: 0 !important;
                padding-left: 2rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }
            
            .logo img {
                width: 50px;
            }
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
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

        .dark-mode-toggle:hover {
            transform: scale(1.1);
            background-color: var(--secondary-green);
        }

        .dark-mode-toggle i {
            transition: transform 0.5s ease;
        }

        body.dark-mode .dark-mode-toggle i {
            transform: rotate(360deg);
        }

        body.dark-mode .dark-mode-toggle {
            background-color: var(--accent-orange);
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #fff;
        }

        body.dark-mode header {
            background: rgba(30, 30, 30, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .nav-links a {
            color: #fff;
        }

        body.dark-mode .nav-links a:hover {
            color: var(--accent-orange);
        }

        body.dark-mode .mission-vision {
            background-color: #2a2a2a;
        }

        body.dark-mode .mission-card,
        body.dark-mode .vision-card {
            background-color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .our-story {
            background: #2a2a2a;
        }

        body.dark-mode .timeline-content {
            background: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .features {
            background-color: #1a1a1a;
        }

        body.dark-mode .feature-card {
            background-color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .team {
            background: #2a2a2a;
        }

        body.dark-mode .team-card {
            background-color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .section-header h2 {
            color: #fff;
        }

        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo img {
            width: 70px;
            height: auto;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 10px;
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }

        .footer-logo img {
            height: 50px;
            width: auto;
            margin-bottom: 1rem;
            padding: 5px;
            border-radius: 10px;
        }

        /* Dark Mode Logo */
        body.dark-mode .logo img,
        body.dark-mode .footer-logo img {
            background-color: #fff;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .logo img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.15);
        }

        /* Footer */
        .footer {
            padding: 2rem 5%;
            background: var(--primary-green);
            color: var(--white);
            text-align: center;
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

    <!-- Dark Mode Toggle Button -->
    <button id="darkModeToggle" class="dark-mode-toggle">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content" data-aos="fade-up">
            <h1>About FarmCS</h1>
            <p>Revolutionizing Agriculture Through Smart Irrigation and IoT<br> Empowering farmers with technology for a sustainable future </p>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="mission-card" data-aos="fade-right">
            <i class="fas fa-seedling card-icon"></i>
            <h2>Our Mission</h2>
            <p>To empower farmers with IoT-enabled smart solutions for efficient resource management and sustainable farming practices.</p>
        </div>
        <div class="vision-card" data-aos="fade-left">
            <i class="fas fa-globe-asia card-icon"></i>
            <h2>Our Vision</h2>
            <p>A world where technology transforms agriculture into a sustainable and eco-friendly industry, ensuring food security for future generations.</p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="our-story">
        <div class="section-header" data-aos="fade-up">
            <h2>Our Journey</h2>
            <p>The story of innovation and determination</p>
        </div>
        <div class="timeline">
            <div class="timeline-item" data-aos="fade-right">
                <div class="timeline-content">
                    <h3>2023</h3>
                    <p>Founded FarmCS with a vision to revolutionize agricultural irrigation</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-left">
                <div class="timeline-content">
                    <h3>2023</h3>
                    <p>Developed first IoT-enabled smart sprinkler prototype</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-right">
                <div class="timeline-content">
                    <h3>2024</h3>
                    <p>Selected for Bharat Billion Impact Challenge</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-left">
                <div class="timeline-content">
                    <h3>2024</h3>
                    <p>Launched comprehensive farm management platform</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-right">
                <div class="timeline-content">
                    <h3>2024</h3>
                    <p>Selected in SIH Smart India Hackathon</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-left">
                <div class="timeline-content">
                    <h3>2025</h3>
                    <p>Selected in B-Plan Pitching national level competition in MANIT Bhopal - 1st position in Agritech sector and overall 5th position</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-right">
                <div class="timeline-content">
                    <h3>2025</h3>
                    <p>Presented our idea in the AICTE tech fest Delhi - received recognition from ministry and National TV Doordarshan Interview</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="section-header" data-aos="fade-up">
            <h2>Core Features & Innovations</h2>
            <p>Transforming agriculture through technology</p>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up">
                <i class="fas fa-tint card-icon"></i>
                <h3>Smart Irrigation</h3>
                <p>IoT-enabled sprinkler system with real-time monitoring and control</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-leaf card-icon"></i>
                <h3>Soil Monitoring</h3>
                <p>Real-time soil health tracking and analysis</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-sun card-icon"></i>
                <h3>Solar Powered</h3>
                <p>Energy-efficient solution powered by renewable energy</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-robot card-icon"></i>
                <h3>Automation</h3>
                <p>Automated fertilization and bird deterrence systems</p>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="team">
        <div class="section-header" data-aos="fade-up">
            <h2>Meet Our Founders</h2>
            <p>The innovators behind FarmCS</p>
        </div>
        <div class="team-grid">
            <div class="team-card" data-aos="fade-up">
                <img src="images/abhishek.jpg" alt="Abhishek Maurya">
                <h3>Abhishek Maurya</h3>
                <p>Founder & CEO</p>
                <p>Visionary leader passionate about agricultural innovation</p>
            </div>
            <div class="team-card" data-aos="fade-up" data-aos-delay="100">
                <img src="images/Abhinav_Jain.jpg" alt="Abhinav Jain">
                <h3>Abhinav Jain</h3>
                <p>Co-Founder & CTO</p>
                <p>Technical expert driving innovation in agricultural technology</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div data-aos="fade-up">
            <h2>Join Us in Transforming Agriculture</h2>
            <p>Be part of the agricultural revolution</p>
            <a href="contact.php" class="btn btn-white">Get Involved</a>
        </div>
    </section>

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

        // Dark Mode Toggle Function
        function toggleDarkMode() {
            const body = document.body;
            const darkModeToggle = document.getElementById('darkModeToggle');
            const icon = darkModeToggle.querySelector('i');
            
            body.classList.toggle('dark-mode');
            
            // Update icon
            if (body.classList.contains('dark-mode')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Check Dark Mode Preference
        function checkDarkModePreference() {
            const darkMode = localStorage.getItem('darkMode');
            const darkModeToggle = document.getElementById('darkModeToggle');
            const icon = darkModeToggle.querySelector('i');
            
            if (darkMode === 'enabled') {
                document.body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }

        // Add event listener to dark mode toggle
        document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

        // Check dark mode preference when page loads
        document.addEventListener('DOMContentLoaded', checkDarkModePreference);

        // Add mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger-menu');
            const navLinks = document.querySelector('.nav-links');
            const navLinksItems = document.querySelectorAll('.nav-links a');

            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            // Close menu when clicking a link
            navLinksItems.forEach(item => {
                item.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                }
            });
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