<?php
session_start();
require_once 'config/db_connect.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmCS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* Dark Mode Variables */
        :root {
            /* Light Mode Colors */
            --bg-light: #f4f6f9;
            --text-light: #333;
            --card-bg-light: #ffffff;
            --card-shadow-light: rgba(0, 0, 0, 0.1);
            --sidebar-bg-light: #2c3e50;
            --sidebar-text-light: rgba(255,255,255,0.7);
            --primary-light: #2ecc71;
            
            /* Dark Mode Colors */
            --bg-dark: #1a1a2e;
            --text-dark: #e6e6e6;
            --card-bg-dark: #16213e;
            --card-shadow-dark: rgba(255, 255, 255, 0.1);
            --sidebar-bg-dark: #0f3460;
            --sidebar-text-dark: rgba(255,255,255,0.8);
            --primary-dark: #4CAF50;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        body.dark-mode .dashboard-header {
            background-color: var(--sidebar-bg-dark);
            color: var(--text-dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Header Icons in Dark Mode */
        body.dark-mode .header-brand i,
        body.dark-mode .header-user .menu-item i,
        body.dark-mode .home-link i {
            color: #ffffff !important;
        }

        body.dark-mode .menu-toggle i {
            color: #ffffff;
        }

        body.dark-mode .dashboard-sidebar {
            background-color: var(--sidebar-bg-dark);
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        body.dark-mode .dashboard-sidebar .sidebar-menu a {
            color: var(--sidebar-text-dark);
            transition: background-color 0.3s ease;
        }

        body.dark-mode .dashboard-sidebar .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.05);
        }

        body.dark-mode .dashboard-sidebar .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
            color: var(--text-dark);
        }

        body.dark-mode .stat-card {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
            border: 1px solid rgba(255,255,255,0.05);
        }

        body.dark-mode .stat-card i,
        body.dark-mode .chart-container i {
            color: var(--primary-dark);
        }

        body.dark-mode .stat-card h3 {
            color: var(--primary-dark);
        }

        body.dark-mode .chart-container {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
            border: 1px solid rgba(255,255,255,0.05);
        }

        body.dark-mode .chart-container h2 {
            color: var(--text-dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
        }

        body.dark-mode .section-title {
            color: var(--text-dark);
        }

        body.dark-mode .user-avatar {
            background-color: var(--primary-dark);
            color: white;
        }

        body.dark-mode .header-user {
            color: var(--text-dark);
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary-light);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        body.dark-mode .dark-mode-toggle {
            background-color: var(--primary-dark);
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1);
        }

        .dark-mode-toggle i {
            font-size: 24px;
        }

        /* Responsive Dark Mode Toggle */
        @media (max-width: 768px) {
            .dark-mode-toggle {
                bottom: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
            }

            .dark-mode-toggle i {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-brand">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-leaf"></i> FarmCS
            <a href="index.php" class="home-link" title="Go to Home Page">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <div class="header-user">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['first_name'], 0, 1)); ?>
                </div>
                <span><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
            </div>
            <a href="logout.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="dashboard-sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="farmerdashboard.php" class="menu-item active">
                    <i class="fas fa-home menu-icon"></i>
                    Overview
                </a>
            </li>
            <li>
                <a href="weather.php" class="menu-item">
                    <i class="fas fa-cloud-sun menu-icon"></i>
                    Weather
                </a>
            </li>
            <li>
                <a href="analytics.php" class="menu-item">
                    <i class="fas fa-chart-line menu-icon"></i>
                    Analytics
                </a>
            </li>
            <li>
                <a href="alerts.php" class="menu-item">
                    <i class="fas fa-bell menu-icon"></i>
                    Alerts
                </a>
            </li>
            <li>
                <a href="invoice.php" class="menu-item">
                    <i class="fas fa-file-invoice menu-icon"></i>
                    Invoice
                </a>
            </li>
            <li>
                <a href="system_control.php" class="menu-item">
                    <i class="fas fa-sliders-h menu-icon"></i>
                    System Control
                </a>
            </li>
            <li>
                <a href="settings.php" class="menu-item">
                    <i class="fas fa-cog menu-icon"></i>
                    Settings
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="dashboard-content">
        <section id="overview" class="fade-in">
            <h1 class="section-title">Dashboard Overview</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                    <p><?php echo htmlspecialchars($_SESSION['district'] . ', ' . $_SESSION['state']); ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-calendar-alt"></i> Growing Season</h3>
                    <p>Kharif 2024</p>
                </div>
            </div>

            <div class="chart-container">
                <h2>Crop Performance</h2>
                <canvas id="cropPerformanceChart"></canvas>
            </div>

            <div class="stats-grid">
                <div class="chart-container">
                    <h2>Weather Forecast</h2>
                    <div id="weatherWidget"></div>
                </div>
                <div class="chart-container">
                    <h2>Recent Activities</h2>
                    <div id="activities"></div>
                </div>
            </div>
        </section>
    </main>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Hamburger Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);

        // Close menu when clicking a link on mobile
        document.querySelectorAll('.dashboard-sidebar .menu-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        // Dark Mode Functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Check for saved dark mode preference
        function checkDarkMode() {
            const savedDarkMode = localStorage.getItem('farmcs_dark_mode');
            if (savedDarkMode === 'enabled') {
                body.classList.add('dark-mode');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                body.classList.remove('dark-mode');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        }

        // Toggle dark mode
        function toggleDarkMode() {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('farmcs_dark_mode', 'enabled');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem('farmcs_dark_mode', 'disabled');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        }

        // Initialize dark mode on page load
        checkDarkMode();

        // Add event listener to dark mode toggle
        darkModeToggle.addEventListener('click', toggleDarkMode);

        // Initialize Charts
        const ctx = document.getElementById('cropPerformanceChart').getContext('2d');
        const chartConfig = {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Crop Growth',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: body.classList.contains('dark-mode') ? '#4CAF50' : '#2ecc71',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
                        }
                    },
                    y: {
                        ticks: {
                            color: body.classList.contains('dark-mode') ? '#e0e0e0' : '#333'
                        }
                    }
                }
            }
        };

        const cropPerformanceChart = new Chart(ctx, chartConfig);

        // Update chart colors when dark mode is toggled
        darkModeToggle.addEventListener('click', () => {
            const isDarkMode = body.classList.contains('dark-mode');
            
            chartConfig.data.datasets[0].borderColor = isDarkMode ? '#4CAF50' : '#2ecc71';
            chartConfig.options.plugins.legend.labels.color = isDarkMode ? '#e0e0e0' : '#333';
            chartConfig.options.scales.x.ticks.color = isDarkMode ? '#e0e0e0' : '#333';
            chartConfig.options.scales.y.ticks.color = isDarkMode ? '#e0e0e0' : '#333';
            
            cropPerformanceChart.update();
        });

        // Add active class to current menu item
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
