<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmCS: India's First Smart Sprinkler System</title>
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
            overflow-x: hidden;
        }

        /* Navigation */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
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

        nav {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin: 0 20px;
        }

        .nav-links a {
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-green);
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-left: auto;
        }

        .login-btn, .signup-btn, .dashboard-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            white-space: nowrap;
        }

        .login-btn {
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            background: transparent;
        }

        .login-btn:hover {
            background: rgba(46, 125, 50, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.15);
        }

        .signup-btn {
            background: var(--primary-green);
            color: white;
            border: 2px solid var(--primary-green);
        }

        .signup-btn:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.25);
        }

        .login-btn:active, .signup-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Add ripple effect */
        .login-btn::after, .signup-btn::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.3) 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }

        .login-btn:active::after, .signup-btn:active::after {
            transform: scale(0, 0);
            opacity: .3;
            transition: 0s;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border: 2px solid #fff;
            color: #fff;
        }

        .btn:hover {
            background-color: #fff;
            color: #2E7D32;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary {
            background: var(--primary-green);
            color: var(--white);
            background-color: #2E7D32;
            border-color: #2E7D32;
        }

        .btn-primary:hover {
            background-color: #1B5E20;
            border-color: #1B5E20;
            color: #fff;
        }

        .btn-outline {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            background: transparent;
        }

        .dashboard-btn {
            background-color: var(--primary-green);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        .dashboard-btn:hover {
            background-color: var(--secondary-green);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.251), rgba(0, 0, 0, 0.5)),
                        url('images/home page background.png');
            background-size: cover;
            background-position: center 30%;
            display: flex;
            align-items: center;
            padding: 0 5%;
            color: var(--white);
            position: relative;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .founder-area {
            position: absolute;
            width: 150px;
            height: 150px;
            right: 42%;
            top: 22%;
            transform: translate(10%, 10%);
            cursor: pointer;
            z-index: 2;
        }

        .founder-tooltip {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: none;
            white-space: nowrap;
            font-size: 14px;
            margin-top: 10px;
        }

        .founder-area:hover .founder-tooltip {
            display: block;
        }

        /* Features Section */
        .features {
            padding: 5rem 5%;
            background: var(--white);
        }

        /* Utility Section Styles */
        .utility-section {
            padding: 5rem 5%;
            background: var(--light-gray);
        }
        .utility-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .utility-intro {
            font-size: 1.1rem;
            color: var(--dark-text);
            margin-bottom: 2.5rem;
            text-align: center;
        }
        .utility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .utility-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 2rem 1.5rem;
            text-align: left;
            transition: box-shadow 0.3s, transform 0.3s;
            border: 2px solid transparent;
        }
        .utility-card:hover {
            box-shadow: 0 8px 24px rgba(46,125,50,0.12);
            border-color: var(--primary-green);
            transform: translateY(-4px);
        }
        .utility-card h3 {
            color: var(--primary-green);
            margin-bottom: 1rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .utility-card ul {
            padding-left: 1.2rem;
            color: #555;
            font-size: 1rem;
            margin-bottom: 0;
        }
        .utility-card ul li {
            margin-bottom: 0.7rem;
            line-height: 1.5;
        }
        @media screen and (max-width: 768px) {
            .utility-section {
                padding: 3rem 1rem;
            }
            .utility-container {
                padding: 0;
            }
            .utility-grid {
                grid-template-columns: 1fr;
            }
        }
        @media screen and (max-width: 480px) {
            .utility-section {
                padding: 2rem 0.5rem;
            }
            .utility-card {
                padding: 1.2rem 0.7rem;
            }
        }
        body.dark-mode .utility-section {
            background: #252525;
        }
        body.dark-mode .utility-card {
            background: #333;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        body.dark-mode .utility-card h3,
        body.dark-mode .utility-card ul li {
            color: #fff;
        }
        body.dark-mode .utility-intro {
            color: #e0e0e0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            color: var(--primary-green);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 0 5%;
        }

        .feature-card {
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

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-green);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.2);
            color: var(--primary-green);
        }

        .feature-card:hover h3 {
            color: var(--primary-green);
        }

        .feature-card:before {
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

        .feature-card:hover:before {
            opacity: 1;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--tech-blue);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card h3 {
            color: var(--dark-text);
            margin-bottom: 1rem;
            font-size: 1.3rem;
            transition: color 0.3s ease;
        }

        .feature-card p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Metrics Section */
        .metrics {
            padding: 5rem 5%;
            background: var(--primary-green);
            color: var(--white);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .metric-item h3 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 5rem 5%;
        }

        .testimonial-carousel {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 2rem;
            padding: 1rem;
            scrollbar-width: none;
        }

        .testimonial-carousel::-webkit-scrollbar {
            display: none;
        }

        .testimonial-card {
            min-width: 300px;
            background: var(--light-gray);
            padding: 2rem;
            border-radius: 15px;
            scroll-snap-align: start;
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        /* Footer Styles */
        footer {
            background-color: var(--white);
            padding: 4rem 0 2rem 0;
            margin-top: 0;
            border-top: 1px solid #eee;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        .footer-logo {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-logo img {
            height: 50px;
            margin-bottom: 1rem;
        }

        .footer-logo p {
            color: var(--dark-text);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-top: 1rem;
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .footer-links-column {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-links-column h4 {
            color: var(--primary-green);
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-links-column h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--accent-orange);
            border-radius: 2px;
        }

        .footer-links-column a {
            color: var(--dark-text);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-links-column a:hover {
            color: var(--accent-orange);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .social-links h4 {
            color: var(--primary-green);
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .social-links h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--accent-orange);
            border-radius: 2px;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--primary-green);
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            text-align: center;
            color: var(--dark-text);
            font-size: 0.9rem;
            perspective: 1000px;
        }

        .footer-bottom .copyright-container {
            position: relative;
            width: 100%;
            height: 20px;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .footer-bottom .copyright-container:hover {
            transform: rotateX(180deg);
        }

        .footer-bottom .copyright-front,
        .footer-bottom .copyright-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-bottom .copyright-back {
            transform: rotateX(180deg);
            color: var(--dark-text);
        }

        .footer-bottom .heart {
            color: #ff4444;
            display: inline-block;
            animation: heartBeat 1.2s infinite;
        }

        @keyframes heartBeat {
            0% { transform: scale(1); }
            14% { transform: scale(1.3); }
            28% { transform: scale(1); }
            42% { transform: scale(1.3); }
            70% { transform: scale(1); }
        }

        /* Footer Responsive */
        @media screen and (max-width: 992px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
            }

            .footer-links {
                grid-column: span 2;
                margin-top: 2rem;
            }
        }

        @media screen and (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-logo, .footer-links, .social-links {
                align-items: center;
                text-align: center;
            }

            .footer-links {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-links-column h4::after,
            .social-links h4::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .social-icons {
                justify-content: center;
            }
        }

        @media screen and (max-width: 480px) {
            .footer-links {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .footer-content {
                padding: 0 1rem;
            }
        }

        /* Dark Mode Footer */
        body.dark-mode footer {
            background-color: #1a1a1a;
            border-top: 1px solid #333;
        }

        body.dark-mode .footer-logo p,
        body.dark-mode .footer-links-column a,
        body.dark-mode .footer-bottom .copyright-front,
        body.dark-mode .footer-bottom .copyright-back {
            color: #fff;
        }

        body.dark-mode .social-icons a {
            background: #333;
            color: var(--accent-orange);
        }

        body.dark-mode .social-icons a:hover {
            background: var(--accent-orange);
            color: #fff;
        }

        /* Section Titles Dark Mode */
        body.dark-mode .section-title h2 {
            color: #fff;
        }

        body.dark-mode .section-title p {
            color: #e0e0e0;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .popup {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            max-width: 500px;
            width: 90%;
            transform: translateY(-20px) scale(0.95);
            transition: all 0.3s ease;
        }

        .popup-overlay.show .popup {
            transform: translateY(0) scale(1);
        }

        .popup-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--light-gray);
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            color: var(--dark-text);
        }

        .popup-close:hover {
            background: var(--accent-orange);
            color: white;
            transform: rotate(90deg);
        }

        .popup h2 {
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        .popup h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-orange);
            border-radius: 3px;
        }

        .popup p {
            color: var(--dark-text);
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
            animation-delay: 0.3s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dark Mode Popup Styles */
        body.dark-mode .popup {
            background: #2a2a2a;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        body.dark-mode .popup h2 {
            color: var(--accent-orange);
        }

        body.dark-mode .popup h2::after {
            background: var(--primary-green);
        }

        body.dark-mode .popup p {
            color: #e0e0e0;
        }

        body.dark-mode .popup-close {
            background: #333;
            color: #fff;
        }

        body.dark-mode .popup-close:hover {
            background: var(--accent-orange);
        }

        /* Popup Animation */
        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .popup.animate {
            animation: slideIn 0.5s ease forwards;
        }

        /* Popup Responsive Styles */
        @media screen and (max-width: 576px) {
            .popup {
                padding: 2rem;
                width: 95%;
                margin: 0 10px;
            }

            .popup h2 {
                font-size: 1.75rem;
            }

            .popup p {
                font-size: 1rem;
                line-height: 1.6;
            }

            .popup-close {
                top: 15px;
                right: 15px;
                width: 30px;
                height: 30px;
            }
        }

        .hamburger-menu {
            display: none;
            cursor: pointer;
            padding: 10px;
            z-index: 1000;
            margin-left: 10px;
        }

        .bar {
            width: 25px;
            height: 3px;
            background-color: var(--primary-green);
            margin: 5px 0;
            transition: 0.4s;
            border-radius: 3px;
        }

        /* Dark mode styles for hamburger menu */
        body.dark-mode .bar {
            background-color: var(--white);
        }

        @media screen and (max-width: 768px) {
            .hamburger-menu {
                display: block;
                order: 3;
            }

            header {
                padding: 10px 15px;
            }
            
            .auth-buttons {
                order: 2;
            }
            
            nav {
                order: 1;
                width: 100%;
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
                padding-top: 30px;
                transition: 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            
            /* Enhanced mobile styles */
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .feature-cards {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-buttons {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .hero-buttons .btn {
                width: 80%;
                text-align: center;
            }
            
            /* Dark mode specific mobile styles */
            body.dark-mode .nav-links {
                background-color: #1a1a1a;
            }
            
            body.dark-mode .nav-links a {
                color: #fff;
            }
            
            body.dark-mode .nav-links a:hover {
                background-color: rgba(255, 107, 53, 0.1);
            }
        }

        /* Small Mobile Devices */
        @media screen and (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .feature-card {
                padding: 1.5rem;
            }
            
            .feature-icon {
                font-size: 2rem;
            }
            
            .utility-card {
                padding: 1.5rem;
            }
            
            .auth-buttons {
                gap: 5px;
            }
            
            .login-btn, .signup-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        /* Dark Mode Toggle Button */
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

        /* Dark Mode Styles for Content */
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

        body.dark-mode .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('images/home page background.png');
            background-size: cover;
            background-position: center 30%;
        }

        body.dark-mode .hero h1,
        body.dark-mode .hero p {
            color: #fff;
        }

        body.dark-mode .feature-card {
            background: #333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .feature-card h3,
        body.dark-mode .feature-card p {
            color: #fff;
        }

        body.dark-mode .utility-section {
            background: #252525;
        }

        body.dark-mode .utility-card {
            background: #333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .utility-card h3,
        body.dark-mode .utility-card ul li {
            color: #fff;
        }

        body.dark-mode footer {
            background-color: #1a1a1a;
            border-top: 1px solid #333;
        }

        body.dark-mode .footer-logo p,
        body.dark-mode .footer-links-column a,
        body.dark-mode .footer-bottom .copyright-front,
        body.dark-mode .footer-bottom .copyright-back {
            color: #fff;
        }

        body.dark-mode .social-icons a {
            background: #333;
            color: var(--accent-orange);
        }

        body.dark-mode .social-icons a:hover {
            background: var(--accent-orange);
            color: #fff;
        }

        /* Extra small mobile devices */
        @media screen and (max-width: 380px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .hero p {
                font-size: 0.9rem;
            }
            
            .section-title h2 {
                font-size: 1.6rem;
            }
            
            .auth-buttons {
                flex-wrap: wrap;
                justify-content: flex-end;
                max-width: 120px;
            }
            
            .login-btn, .signup-btn, .dashboard-btn {
                padding: 5px 10px;
                font-size: 11px;
                margin-bottom: 5px;
            }
            
            .language-selector {
                margin-top: 5px;
            }
            
            .feature-card {
                padding: 1.2rem;
            }
            
            .footer-links {
                gap: 1rem;
            }
        }

        body.dark-mode .features {
            background-color: #1a1a1a;
        }

        body.dark-mode .feature-icon {
            color: var(--accent-orange);
        }

        body.dark-mode .utility-intro {
            color: #e0e0e0;
        }
    </style>
    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,bn,te,ta,mr,gu,kn,ml,pa', // Languages we want to support
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>
<body>
    <!-- Welcome Popup -->
    <div class="popup-overlay" id="popup-overlay">
        <div class="popup">
            <button class="popup-close" onclick="closePopup()"></button>
            <h2>Welcome to The FarmCS!</h2>
            <p>Empowering Indian Farmers with Smart Irrigation Solutions.</p>
            <p>If you're a FarmCS customer, please <b>log in</b> to access your dashboard and explore real-time data of your fields.</p>
        </div>
    </div>

    <!-- Navigation -->
    <header>
        <a href="index.php" class="logo">
            <img src="images/FarmCSlogo.png" alt="FarmCS Logo">
        </a>
        <nav>
            <div class="nav-links">
                <a href="index.php" data-translate="nav.home">Home</a>
                <a href="index.php#features" data-translate="nav.features">Features</a>
                <a href="cropdata.php" data-translate="nav.cropdata">Crop Data</a>
                <a href="learn-more.php" data-translate="nav.learn-more">Learn More</a>
                <a href="about.php" data-translate="nav.about">About Us</a>
                <a href="contact.php" data-translate="nav.contact">Contact</a>
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
            <!-- Language Selector -->
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

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="founder-area">
            <div class="founder-tooltip">FarmCS Founder</div>
        </div>
        <div class="hero-content" data-aos="fade-up">
            <h1 data-translate="hero.title">Empowering Indian Farmers with Smart Irrigation Solutions</h1>
            <p data-translate="hero.subtitle">Save water, improve yields, and embrace innovation in agriculture with India's first smart sprinkler system.</p>
            <div class="hero-buttons">
                <a href="learn-more.php" class="btn" data-translate="hero.cta_button">Learn More</a>
                <a href="signup.php" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-title" data-aos="fade-up">
            <h2 data-translate="features.title">Smart Features for Modern Farming</h2>
            <p data-translate="features.subtitle">Discover how FarmCS revolutionizes irrigation</p>
        </div>
        <div class="feature-cards">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3 data-translate="features.real-time-data">Real-Time Field Data</h3>
                <p data-translate="features.real-time-data-description">Monitor soil moisture, temperature, and humidity in real-time</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-seedling feature-icon"></i>
                <h3 data-translate="features.smart-fertilization">Smart Fertilization</h3>
                <p data-translate="features.smart-fertilization-description">Optimize nutrient delivery with intelligent fertigation control</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-fire-extinguisher feature-icon"></i>
                <h3 data-translate="features.fire-detection">Fire Detection</h3>
                <p data-translate="features.fire-detection-description">Early warning system for fire prevention and control</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-dove feature-icon"></i>
                <h3 data-translate="features.bird-deterrence">Bird Deterrence</h3>
                <p data-translate="features.bird-deterrence-description">Protect your crops with smart bird control system</p>
            </div>
        </div>
    </section>

    <!-- Utility of Project Section -->
    <section class="utility-section">
        <div class="section-title" data-aos="fade-up">
            <h2>Utility of Project: FarmCS – Smart Irrigation System</h2>
        </div>
        <div class="utility-container" data-aos="fade-up">
            <p class="utility-intro">
                FarmCS offers a high-utility, scalable solution to one of agriculture's most pressing problems: inefficient water use. By integrating IoT sensors, adjustable smart sprinklers, and a remote monitoring system, the project delivers real-time, need-based irrigation and fertigation. Here's how it benefits stakeholders:
            </p>
            
            <div class="utility-grid">
                <div class="utility-card" data-aos="fade-up">
                    <h3><i class="fas fa-user-alt"></i> For Farmers:</h3>
                    <ul>
                        <li>Reduces water usage by up to 90%, lowering irrigation costs.</li>
                        <li>Increases crop yield by 85-95% through precise water and nutrient delivery.</li>
                        <li>Saves labor via automation and remote control via a mobile app.</li>
                        <li>Reduces exposure to harmful fertilizers with smart fertigation.</li>
                    </ul>
                </div>
                
                <div class="utility-card" data-aos="fade-up" data-aos-delay="100">
                    <h3><i class="fas fa-leaf"></i> For the Environment:</h3>
                    <ul>
                        <li>Promotes sustainable farming practices.</li>
                        <li>Minimizes groundwater depletion.</li>
                        <li>Reduces chemical runoff with targeted fertilizer use.</li>
                        <li>Eco-friendly bird deterrence and fire detection systems help protect crops without harming wildlife.</li>
                    </ul>
                </div>
                
                <div class="utility-card" data-aos="fade-up" data-aos-delay="200">
                    <h3><i class="fas fa-building"></i> For Policymakers & Agribusinesses:</h3>
                    <ul>
                        <li>Aligns with government goals like PMKSY (Per Drop More Crop).</li>
                        <li>Can be integrated into large-scale precision agriculture strategies.</li>
                        <li>Encourages adoption of clean, renewable energy through solar integration.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="images/FarmCSlogo.png" alt="FarmCS Logo">
                <p data-translate="footer.description">Revolutionizing agriculture through smart irrigation and IoT technology. Making farming smarter, sustainable, and more efficient.</p>
            </div>
            
            <div class="footer-links">
                <div class="footer-links-column">
                    <h4 data-translate="footer.features.title">Our Features</h4>
                    <a href="index.html#smart-irrigation" data-translate="footer.features.smart-irrigation">Smart Irrigation</a>
                    <a href="index.html#soil-monitoring" data-translate="footer.features.soil-monitoring">Soil Monitoring</a>
                    <a href="index.html#weather-integration" data-translate="footer.features.weather-integration">Weather Integration</a>
                    <a href="index.html#mobile-control" data-translate="footer.features.mobile-control">Mobile Control</a>
                    <a href="index.html#data-analytics" data-translate="footer.features.data-analytics">Data Analytics</a>
                </div>
                
                <div class="footer-links-column">
                    <h4 data-translate="footer.resources.title">Resources</h4>
                    <a href="#" data-translate="footer.resources.documentation">Documentation</a>
                    <a href="#" data-translate="footer.resources.support-center">Support Center</a>
                    <a href="#" data-translate="footer.resources.installation-guide">Installation Guide</a>
                    <a href="#" data-translate="footer.resources.system-updates">System Updates</a>
                    <a href="#" data-translate="footer.resources.user-manual">User Manual</a>
                </div>
                
                <div class="footer-links-column">
                    <h4 data-translate="footer.contact.title">Contact & Legal</h4>
                    <a href="contact.php" data-translate="footer.contact.contact-us">Contact Us</a>
                    <a href="#" data-translate="footer.contact.privacy-policy">Privacy Policy</a>
                    <a href="#" data-translate="footer.contact.terms-of-service">Terms of Service</a>
                    <a href="#" data-translate="footer.contact.warranty-info">Warranty Info</a>
                    <a href="#" data-translate="footer.contact.support-policy">Support Policy</a>
                </div>
            </div>
            
            <div class="social-links">
                <h4 data-translate="footer.social.title">Connect With Us</h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook" data-translate="footer.social.facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" data-translate="footer.social.twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn" data-translate="footer.social.linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="Instagram" data-translate="footer.social.instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="copyright-container">
                <div class="copyright-front">
                    <p data-translate="footer.copyright">&copy; 2023 FarmCS. All rights reserved.</p>
                </div>
                <div class="copyright-back">
                    <p data-translate="footer.made-with-love">Made with <span class="heart">❤</span> in India</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Check login status for header buttons
        function isUserLoggedIn() {
            // Check PHP session status via AJAX
            return fetch('handlers/session_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'check' }),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.loggedIn) {
                    // Clear localStorage if session is not valid
                    localStorage.removeItem('user');
                    localStorage.removeItem('isLoggedIn');
                }
                return data.loggedIn;
            })
            .catch(() => {
                // On error, assume not logged in
                localStorage.removeItem('user');
                localStorage.removeItem('isLoggedIn');
                return false;
            });
        }

        // Update auth buttons when page loads
        async function updateAuthButtons() {
            const authButtons = document.querySelector('.auth-buttons');
            if (!authButtons) return;
            
            const loggedIn = await isUserLoggedIn();
            
            if (loggedIn) {
                authButtons.innerHTML = `
                    <a href="dashboard.php" class="dashboard-btn">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <div class="language-selector">
                        <div id="google_translate_element"></div>
                    </div>`;
            } else {
                authButtons.innerHTML = `
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="signup.php" class="signup-btn">Sign Up</a>
                    <div class="language-selector">
                        <div id="google_translate_element"></div>
                    </div>`;
            }
        }

        // Update auth buttons when page loads
        document.addEventListener('DOMContentLoaded', updateAuthButtons);

        // Update auth buttons periodically
        setInterval(updateAuthButtons, 60000); // Check every minute

        // Popup functionality
        function showPopup() {
            if (!isUserLoggedIn()) {
                document.getElementById('popup-overlay').classList.add('show');
            }
        }

        function closePopup() {
            document.getElementById('popup-overlay').classList.remove('show');
        }

        // Show popup after a delay only for non-logged-in users
        window.addEventListener('load', function() {
            if (!isUserLoggedIn()) {
                setTimeout(showPopup, 2000);
            }
        });

        // Close popup when clicking outside
        document.addEventListener('click', function(event) {
            const popup = document.getElementById('popup-overlay');
            if (event.target === popup) {
                closePopup();
            }
        });

        // Dark Mode Toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.querySelector('.dark-mode-toggle i');
            
            if (document.body.classList.contains('dark-mode')) {
                icon.className = 'fas fa-sun';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.className = 'fas fa-moon';
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Check Dark Mode Preference
        function checkDarkModePreference() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                document.querySelector('.dark-mode-toggle i').className = 'fas fa-sun';
            }
        }

        // Check dark mode preference when page loads
        document.addEventListener('DOMContentLoaded', checkDarkModePreference);

        // Initialize all functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Update auth buttons
            updateAuthButtons();

            // Check dark mode preference
            checkDarkModePreference();

            // Mobile Menu Functionality
            const hamburger = document.querySelector('.hamburger-menu');
            const navLinks = document.querySelector('.nav-links');
            const navLinksItems = document.querySelectorAll('.nav-links a');

            if (hamburger && navLinks) {
                hamburger.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent document click from immediately closing
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
                document.addEventListener('click', function(event) {
                    if (navLinks.classList.contains('active') && 
                        !hamburger.contains(event.target) && 
                        !navLinks.contains(event.target)) {
                        hamburger.classList.remove('active');
                        navLinks.classList.remove('active');
                    }
                });
            }
        });

        // Check login status function
        function isUserLoggedIn() {
            return localStorage.getItem('isLoggedIn') === 'true';
        }

        // Update auth buttons based on login status
        function updateAuthButtons() {
            const loggedOutButtons = document.getElementById('loggedOutButtons');
            const loggedInButtons = document.getElementById('loggedInButtons');
            
            if (loggedOutButtons && loggedInButtons) {
                if (isUserLoggedIn()) {
                    loggedOutButtons.style.display = 'none';
                    loggedInButtons.style.display = 'flex';
                } else {
                    loggedOutButtons.style.display = 'flex';
                    loggedInButtons.style.display = 'none';
                }
            }
        }

        // Redirect to dashboard if logged in
        if (window.location.pathname.includes('login.php') || 
            window.location.pathname.includes('signup.php')) {
            if (isUserLoggedIn()) {
                window.location.href = 'farmerdashboard.php';
            }
        }

        // Redirect to login if not logged in
        if (window.location.pathname.includes('farmerdashboard.php')) {
            if (!isUserLoggedIn()) {
                window.location.href = 'login.php';
            }
        }

        // Language selector functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Remove old language selection code since we're using Google Translate
            const languageSelector = document.querySelector('.language-selector');
            if (languageSelector) {
                languageSelector.style.position = 'relative';
                languageSelector.style.marginLeft = '15px';
            }

            // Style the Google Translate widget
            const styleGoogleTranslate = () => {
                const googleTranslateSelect = document.querySelector('.goog-te-combo');
                if (googleTranslateSelect) {
                    googleTranslateSelect.style.border = '1px solid var(--primary-green)';
                    googleTranslateSelect.style.borderRadius = '8px';
                    googleTranslateSelect.style.padding = '8px 12px';
                    googleTranslateSelect.style.backgroundColor = 'transparent';
                    googleTranslateSelect.style.color = 'var(--dark-text)';
                    googleTranslateSelect.style.cursor = 'pointer';
                    googleTranslateSelect.style.fontSize = '14px';
                    googleTranslateSelect.style.outline = 'none';
                }
            };

            // Apply styles after a short delay to ensure Google Translate has loaded
            setTimeout(styleGoogleTranslate, 1000);
        });

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
