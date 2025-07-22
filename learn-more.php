<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn More - FarmCS</title>
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
            background-color: #dbffdbc4;
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

        /* Updated Mobile Navigation Styles */
        @media screen and (max-width: 768px) {
            header {
                padding: 10px;
            }

            .logo img {
                width: 50px;
                height: 50px;
            }

            .hamburger-menu {
                display: block;
                order: 3;
            }

            .auth-buttons {
                order: 2;
                gap: 8px;
            }

            nav {
                order: 1;
            }

            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: var(--white);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 40px;
                transition: 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex !important;
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
                padding: 6px 12px;
                font-size: 13px;
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
        }

        @media screen and (max-width: 480px) {
            .logo img {
                width: 45px;
                height: 45px;
            }

            .login-btn, .signup-btn {
                padding: 5px 10px;
                font-size: 12px;
            }
        }

        /* Dark Mode Styles for Mobile Menu */
        body.dark-mode .bar {
            background-color: var(--white);
        }

        body.dark-mode .nav-links {
            background-color: #1a1a1a;
        }

        body.dark-mode .nav-links a {
            color: var(--white);
        }

        /* Learn More Content */
        .learn-more-container {
            padding: 100px 5% 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page {
            margin-bottom: 4rem;
            padding: 2rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .page h2 {
            color: var(--primary-green);
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .page h3 {
            color: var(--tech-blue);
            font-size: 1.8rem;
            margin: 1.5rem 0 1rem;
        }

        .page p {
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .feature-item {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-green);
        }

        .feature-item:hover i {
            transform: scale(1.2);
            color: var(--primary-green);
        }

        .feature-item:hover h4 {
            color: var(--primary-green);
        }

        .feature-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(46, 125, 50, 0.03));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-item:hover:before {
            opacity: 1;
        }

        .feature-item i {
            font-size: 2.5rem;
            color: var(--tech-blue);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .feature-item h4 {
            color: var(--dark-text);
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .feature-item p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .benefit-item {
            text-align: center;
            padding: 1.5rem;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .timeline {
            position: relative;
            margin: 2rem 0;
            padding: 2rem 0;
        }

        .timeline-item {
            margin-bottom: 2rem;
            padding-left: 2rem;
            border-left: 3px solid var(--primary-green);
        }

        .image-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }

        .comparison-item {
            text-align: center;
        }

        .comparison-item img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        /* Dark Mode Global Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #fff;
        }

        /* Header Dark Mode */
        body.dark-mode header {
            background-color: #1a1a1a;
            border-bottom: 1px solid #333;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
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

        /* Hero Section Dark Mode */
        body.dark-mode .hero {
            background-image: linear-gradient(rgba(26, 26, 26, 0.8), rgba(42, 42, 42, 0.8));
            color: #fff;
        }

        body.dark-mode .hero h1 {
            color: #fff;
        }

        body.dark-mode .hero p {
            color: #e0e0e0;
        }

        /* Smart Irrigation Section Dark Mode */
        body.dark-mode .smart-irrigation {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
        }

        body.dark-mode .smart-irrigation h2 {
            color: #fff;
        }

        body.dark-mode .smart-irrigation p {
            color: #e0e0e0;
        }

        body.dark-mode .irrigation-card {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .irrigation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-green);
        }

        body.dark-mode .irrigation-card h3 {
            color: #fff;
        }

        body.dark-mode .irrigation-card p {
            color: #e0e0e0;
        }

        /* Technology Section Dark Mode */
        body.dark-mode .technology {
            background-image: linear-gradient(to bottom, #2a2a2a, #1a1a1a);
        }

        body.dark-mode .technology h2 {
            color: #fff;
        }

        body.dark-mode .technology p {
            color: #e0e0e0;
        }

        body.dark-mode .tech-card {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .tech-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-green);
        }

        body.dark-mode .tech-card h3 {
            color: #fff;
        }

        body.dark-mode .tech-card p {
            color: #e0e0e0;
        }

        /* Benefits Section Dark Mode */
        body.dark-mode .benefits {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
        }

        body.dark-mode .benefits h2 {
            color: #fff;
        }

        body.dark-mode .benefits p {
            color: #e0e0e0;
        }

        body.dark-mode .benefit-card {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--accent-orange);
        }

        body.dark-mode .benefit-card i {
            color: var(--primary-green);
            text-shadow: 0 0 10px rgba(46, 125, 50, 0.3);
        }

        body.dark-mode .benefit-card h3 {
            color: #fff;
        }

        body.dark-mode .benefit-card p {
            color: #e0e0e0;
        }

        /* Features Section Dark Mode */
        body.dark-mode .features {
            background-image: linear-gradient(to bottom, #2a2a2a, #1a1a1a);
        }

        body.dark-mode .features h2 {
            color: #fff;
        }

        body.dark-mode .features .section-subtitle {
            color: var(--accent-orange);
        }

        body.dark-mode .feature-item {
            background-color: #333;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-green);
        }

        body.dark-mode .feature-item i {
            color: var(--primary-green);
            text-shadow: 0 0 10px rgba(46, 125, 50, 0.3);
        }

        body.dark-mode .feature-item h4 {
            color: #fff;
        }

        body.dark-mode .feature-item p {
            color: #e0e0e0;
        }

        /* Benefits to Farmers Section Dark Mode */
        body.dark-mode .benefits-to-farmers {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
            padding: 4rem 5%;
        }

        body.dark-mode .benefits-to-farmers .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        body.dark-mode .benefits-to-farmers .section-header h2 {
            color: #ffffff;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .benefits-to-farmers .section-header p {
            color: var(--accent-orange);
            font-size: 1.2rem;
        }

        body.dark-mode .benefits-card {
            background-color: #2d2d2d;
            border: 1px solid #444;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        body.dark-mode .benefits-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-green);
        }

        body.dark-mode .benefits-card .icon {
            color: var(--primary-green);
            font-size: 3rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 15px rgba(46, 125, 50, 0.3);
        }

        body.dark-mode .benefits-card h3 {
            color: var(--primary-green);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        body.dark-mode .benefits-card p {
            color: #e0e0e0;
            line-height: 1.6;
            font-size: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            body.dark-mode .benefits-card {
                padding: 1.5rem;
            }

            body.dark-mode .benefits-card .icon {
                font-size: 2.5rem;
            }

            body.dark-mode .benefits-card h3 {
                font-size: 1.3rem;
            }
        }

        /* Footer Dark Mode */
        body.dark-mode footer {
            background-color: #1a1a1a;
            border-top: 1px solid #333;
        }

        body.dark-mode .footer-content {
            color: #e0e0e0;
        }

        body.dark-mode .footer-content h3 {
            color: #fff;
        }

        body.dark-mode .footer-content a {
            color: #e0e0e0;
        }

        body.dark-mode .footer-content a:hover {
            color: var(--primary-green);
        }

        /* Dark mode toggle button */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        /* Text and Background Visibility Enhancements */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #fff;
        }

        body.dark-mode section {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
            padding: 4rem 5%;
        }

        body.dark-mode h1, 
        body.dark-mode h2, 
        body.dark-mode h3, 
        body.dark-mode h4 {
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode p {
            color: #f0f0f0;
            line-height: 1.6;
        }

        body.dark-mode .section-title {
            color: #ffffff;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .section-subtitle {
            color: var(--accent-orange);
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        /* Card Enhancements */
        body.dark-mode .card,
        body.dark-mode .feature-item {
            background-color: #2d2d2d;
            border: 1px solid #444;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            transition: all 0.3s ease;
        }

        body.dark-mode .card:hover,
        body.dark-mode .feature-item:hover {
            background-color: #333;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-green);
        }

        body.dark-mode .card h3,
        body.dark-mode .feature-item h4 {
            color: var(--primary-green);
            margin-bottom: 1rem;
        }

        body.dark-mode .card p,
        body.dark-mode .feature-item p {
            color: #e0e0e0;
            line-height: 1.6;
        }

        /* Icon Enhancements */
        body.dark-mode .feature-item i {
            color: var(--primary-green);
            font-size: 2rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(46, 125, 50, 0.3);
        }

        /* List Item Enhancements */
        body.dark-mode ul li {
            color: #e0e0e0;
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        body.dark-mode ul li::before {
            content: "•";
            color: var(--primary-green);
            font-weight: bold;
            margin-right: 0.5rem;
        }

        /* Section Background Alternation */
        body.dark-mode section:nth-child(odd) {
            background-image: linear-gradient(to bottom, #1a1a1a, #2a2a2a);
        }

        body.dark-mode section:nth-child(even) {
            background-image: linear-gradient(to bottom, #2a2a2a, #1a1a1a);
        }

        /* Important Text Highlights */
        body.dark-mode .highlight {
            color: var(--accent-orange);
            font-weight: 600;
        }

        body.dark-mode .important-text {
            color: var(--primary-green);
            font-weight: 600;
        }

        /* Link Enhancements */
        body.dark-mode a {
            color: var(--primary-green);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        body.dark-mode a:hover {
            color: var(--secondary-green);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .image-comparison {
                grid-template-columns: 1fr;
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

            .grid-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
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

            .feature-grid,
            .benefits-grid,
            .technology-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .card {
                margin: 1rem 0;
            }
        }

        @media screen and (max-width: 480px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .card {
                padding: 1.5rem;
            }

            .feature-item,
            .benefit-item {
                padding: 1rem;
            }

            .feature-item i,
            .benefit-item i {
                font-size: 2rem;
            }
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

    <div class="learn-more-container">
        <!-- Page 1: Introduction -->
        <section class="page" data-aos="fade-up">
            <h2>Revolutionizing Agriculture with IoT</h2>
            <p>FarmCS is an innovative IoT-enabled smart irrigation system designed to optimize water usage, improve crop yields, and enhance the efficiency of farming operations. By combining technology with sustainable practices, FarmCS empowers farmers to make data-driven decisions and reduce resource wastage.</p>
            
            <h3>Our Vision</h3>
            <p>Our vision is to transform agriculture by integrating cutting-edge technologies, ensuring sustainable farming practices that promote environmental and economic prosperity.</p>

            <h3>The Challenge</h3>
            <p>Traditional irrigation methods often lead to inefficiencies such as over-irrigation, water wastage, and labor-intensive processes. FarmCS addresses these challenges by providing a smart sprinkler system that operates dynamically based on real-time data, ensuring optimal irrigation.</p>

            <div class="image-comparison">
                <div class="comparison-item">
                    <img src="https://images.unsplash.com/photo-1519092437326-bfd121eb53ae?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Traditional Irrigation">
                    <h4>Traditional Irrigation</h4>
                </div>
                <div class="comparison-item">
                    <img src="https://plus.unsplash.com/premium_photo-1661875241767-7a587c5b9718?q=80&w=1810&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Smart Irrigation">
                    <h4>Smart Irrigation with FarmCS</h4>
                </div>
            </div>
        </section>

        <!-- Page 2: How FarmCS Works -->
        <section class="page" data-aos="fade-up">
            <h2>IoT-Enabled Smart Irrigation: A Game Changer</h2>
            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-tint"></i>
                    <h4>Real-Time Soil Moisture Sensing</h4>
                    <p>Continuously monitors soil conditions to ensure precise irrigation</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-robot"></i>
                    <h4>Automated Operation</h4>
                    <p>Dynamic adjustments based on real-time data, eliminating manual intervention</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-sliders-h"></i>
                    <h4>Customizable Sprinkler System</h4>
                    <p>Tailored water distribution for diverse crop types and growth stages</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-flask"></i>
                    <h4>Integrated Fertilization</h4>
                    <p>Dispenses liquid fertilizers automatically, protecting farmers from harmful exposure</p>
                </div>
            </div>
        </section>

        <!-- Page 3: Key Innovations & Benefits -->
        <section class="page" data-aos="fade-up">
            <h2>Smarter Farming, Sustainable Future</h2>
            <h3>Key Innovations</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-microchip"></i>
                    <h4>IoT Integration</h4>
                    <p>Seamless sensor-to-sprinkler communication</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-solar-panel"></i>
                    <h4>Solar Powered</h4>
                    <p>Energy-efficient operations powered by solar panels</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-dove"></i>
                    <h4>Bird Deterrence</h4>
                    <p>Eco-friendly sound systems for crop protection</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-fire-extinguisher"></i>
                    <h4>Fire Detection</h4>
                    <p>360° cameras for early fire detection and prevention</p>
                </div>
            </div>

            <h3>Benefits to Farmers</h3>
            <div class="benefits-grid benefits-to-farmers">
                <div class="benefit-item benefits-card">
                    <i class="fas fa-chart-line icon"></i>
                    <h4>Enhanced Yields</h4>
                    <p>Optimize irrigation for better crop production</p>
                </div>
                <div class="benefit-item benefits-card">
                    <i class="fas fa-coins icon"></i>
                    <h4>Cost Savings</h4>
                    <p>Reduce water and fertilizer usage</p>
                </div>
                <div class="benefit-item benefits-card">
                    <i class="fas fa-user-shield icon"></i>
                    <h4>Protection</h4>
                    <p>Guard against hazards and crop damage</p>
                </div>
            </div>
        </section>

        <!-- Page 4: Future Scope -->
        <section class="page" data-aos="fade-up">
            <h2>Advancing Agriculture Through Technology</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <h4>Advanced Sensor Technologies</h4>
                    <p>Drone-based monitoring for comprehensive field analysis</p>
                </div>
                <div class="timeline-item">
                    <h4>AI Integration</h4>
                    <p>Machine learning for predictive irrigation and schedule optimization</p>
                </div>
                <div class="timeline-item">
                    <h4>Weather Integration</h4>
                    <p>Real-time weather forecasting for dynamic irrigation adjustment</p>
                </div>
                <div class="timeline-item">
                    <h4>Blockchain Implementation</h4>
                    <p>Secure record-keeping and supply chain traceability</p>
                </div>
            </div>

            <div class="conclusion" style="text-align: center; margin-top: 3rem;">
                <h3>Join Us in Shaping the Future of Agriculture</h3>
                <p>We are not just a product; it's a movement towards sustainable farming. By integrating IoT and renewable energy, we aim to create a future where technology works hand-in-hand with nature, empowering farmers and protecting resources.</p>
            </div>
        </section>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" aria-label="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        const moonIcon = darkModeToggle.querySelector('i');

        // Check for saved dark mode preference
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeToggle.classList.add('dark-mode');
            moonIcon.classList.remove('fa-moon');
            moonIcon.classList.add('fa-sun');
        }

        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            darkModeToggle.classList.toggle('dark-mode');

            // Toggle moon/sun icon
            if (document.body.classList.contains('dark-mode')) {
                moonIcon.classList.remove('fa-moon');
                moonIcon.classList.add('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                moonIcon.classList.remove('fa-sun');
                moonIcon.classList.add('fa-moon');
                localStorage.setItem('darkMode', null);
            }
        });
    </script>

    <script>
        // Updated mobile menu JavaScript
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
