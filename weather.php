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
    <title>Weather Dashboard - FarmCS</title>
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

        /* Weather Specific Dark Mode Styles */
        body.dark-mode .weather-card {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
            border: 1px solid rgba(255,255,255,0.05);
        }

        body.dark-mode .weather-icon {
            color: var(--primary-dark);
        }

        body.dark-mode .detail-item i {
            color: var(--primary-dark);
        }

        body.dark-mode .forecast-item {
            background-color: var(--card-bg-dark);
            border-color: rgba(255,255,255,0.05);
            color: var(--text-dark);
        }

        body.dark-mode .forecast-item:hover {
            box-shadow: 0 4px 6px rgba(255,255,255,0.1);
        }

        body.dark-mode .quick-nav-menu {
            background-color: var(--card-bg-dark);
            box-shadow: 0 2px 4px var(--card-shadow-dark);
        }

        body.dark-mode .quick-nav-item {
            color: var(--text-dark);
            background-color: rgba(255,255,255,0.05);
        }

        body.dark-mode .quick-nav-item:hover {
            background-color: rgba(255,255,255,0.1);
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

        .weather-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .weather-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .current-weather {
            text-align: center;
            padding: 30px;
        }

        .weather-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--primary-light);
        }

        .temperature {
            font-size: 42px;
            font-weight: bold;
            margin: 10px 0;
        }

        .weather-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-item i {
            color: var(--primary-light);
        }

        .forecast-list {
            display: flex;
            justify-content: flex-start;
            overflow-x: auto;
            padding: 10px 0;
            gap: 15px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-light) transparent;
        }

        .forecast-list::-webkit-scrollbar {
            height: 6px;
        }

        .forecast-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .forecast-list::-webkit-scrollbar-thumb {
            background-color: var(--primary-light);
            border-radius: 3px;
        }

        .forecast-item {
            flex: 0 0 auto;
            width: 120px;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: var(--card-bg-light);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .forecast-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .quick-nav-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .quick-nav-item {
            flex: 1 1 auto;
            min-width: 120px;
            max-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-align: center;
        }

        @media (max-width: 768px) {
            .weather-grid {
                grid-template-columns: 1fr;
            }

            .weather-details {
                grid-template-columns: 1fr;
            }

            .current-weather {
                padding: 20px;
            }

            .temperature {
                font-size: 36px;
            }

            .weather-icon {
                font-size: 40px;
            }

            .forecast-item {
                width: 100px;
                padding: 10px;
            }

            .forecast-item .icon {
                font-size: 30px;
            }

            .quick-nav-item {
                min-width: calc(50% - 20px);
                padding: 6px 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .weather-card {
                padding: 15px;
            }

            .temperature {
                font-size: 32px;
            }

            .weather-icon {
                font-size: 36px;
            }

            .forecast-item {
                width: 90px;
            }

            .quick-nav-item {
                min-width: calc(100% - 20px);
            }
        }

        /* Add translation note styles to the existing style section */
        .translation-note {
            text-align: center;
            padding: 10px;
            background-color: var(--bg-light);
            margin: 10px 0;
            border-radius: 5px;
            font-size: 0.9em;
            border: 1px solid rgba(0,0,0,0.1);
        }

        body.dark-mode .translation-note {
            background-color: var(--card-bg-dark);
            border-color: rgba(255,255,255,0.1);
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
                <a href="farmerdashboard.php" class="menu-item">
                    <i class="fas fa-home menu-icon"></i>
                    Overview
                </a>
            </li>
            <li>
                <a href="weather.php" class="menu-item active">
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
        <!-- Use your browser's translate feature for language translation (Right-click → Translate or use browser settings) -->
        <section id="weather" class="fade-in">
            <h1 class="section-title">Weather Information</h1>
            
            <div class="weather-alert">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="alert-message"></span>
            </div>

            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span id="error-message-text"></span>
            </div>

            <div id="loading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading weather data...
            </div>

            <div class="weather-grid" id="weather-content" style="display: none;">
                <div class="weather-card current-weather">
                    <h2>Current Weather</h2>
                    <div class="weather-icon">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="location">
                        <?php echo htmlspecialchars($_SESSION['district'] . ', ' . $_SESSION['state']); ?>
                    </div>
                    <div class="temperature">Loading...</div>
                    <div class="weather-description">Loading weather data...</div>
                    <div class="weather-details">
                        <div class="detail-item">
                            <i class="fas fa-tint"></i>
                            <span class="humidity">--</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-wind"></i>
                            <span class="wind-speed">--</span>
                        </div>
                    </div>
                </div>

                <div class="weather-card">
                    <h2>Weekly Forecast</h2>
                    <div class="forecast-list" id="forecast-list">
                        <!-- Forecast items will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="weather-card" id="advisory-content" style="display: none;">
                <h2>Agricultural Weather Advisory</h2>
                <ul class="advisory-list" id="advisory-list">
                    <!-- Advisory items will be populated by JavaScript -->
                </ul>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            return days[date.getDay()];
        }

        function updateWeatherAdvisory(currentTemp, humidity, windSpeed, precipitation, weatherCode) {
            const advisoryList = document.getElementById('advisory-list');
            advisoryList.innerHTML = '';
            
            const advisories = [];

            if (precipitation > 5) {
                advisories.push('Rainfall expected - Consider postponing outdoor activities');
            }

            if (humidity > 80) {
                advisories.push('High humidity levels - Monitor crops for fungal diseases');
            }

            if (windSpeed > 20) {
                advisories.push('Strong winds expected - Take necessary crop protection measures');
            }

            if (currentTemp > 35) {
                advisories.push('High temperature alert - Ensure adequate irrigation');
            }

            // Add weather-specific advisories
            switch (weatherCode) {
                case 61:
                case 63:
                case 65:
                    advisories.push('Moderate to heavy rain - Protect crops from waterlogging');
                    break;
                case 95:
                case 96:
                case 99:
                    advisories.push('Thunderstorm warning - Secure farm equipment and livestock');
                    break;
                case 71:
                case 73:
                case 75:
                    advisories.push('Snow expected - Protect sensitive crops and equipment');
                    break;
            }

            advisories.forEach(advisory => {
                const li = document.createElement('li');
                li.textContent = advisory;
                advisoryList.appendChild(li);
            });
        }

        // Weather display functions
        function updateWeatherDisplay(weatherData, weatherCodes) {
            try {
                // Update current weather
                const current = weatherData.current;
                const weatherCode = current.weather_code;
                const weatherInfo = weatherCodes[weatherCode] || { description: 'Unknown', icon: 'fa-question' };

                // Update temperature
                document.querySelector('.temperature').textContent = `${Math.round(current.temperature_2m)}°C`;
                
                // Update weather icon and description
                const weatherIcon = document.querySelector('.weather-icon i');
                weatherIcon.className = `fas ${weatherInfo.icon}`;
                document.querySelector('.weather-description').textContent = weatherInfo.description;

                // Update weather details
                document.querySelector('.humidity').textContent = `${Math.round(current.relative_humidity_2m)}% Humidity`;
                document.querySelector('.wind-speed').textContent = `${Math.round(current.wind_speed_10m)} km/h`;

                // Update forecast
                const forecastList = document.getElementById('forecast-list');
                forecastList.innerHTML = ''; // Clear existing forecast

                // Add next 7 days forecast
                for (let i = 0; i < 7; i++) {
                    const date = new Date(weatherData.daily.time[i]);
                    const dayWeatherCode = weatherData.daily.weather_code[i];
                    const dayWeatherInfo = weatherCodes[dayWeatherCode] || { description: 'Unknown', icon: 'fa-question' };

                    const forecastItem = document.createElement('div');
                    forecastItem.className = 'forecast-item';
                    forecastItem.innerHTML = `
                        <div class="forecast-date">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                        <div class="forecast-icon">
                            <i class="fas ${dayWeatherInfo.icon}"></i>
                        </div>
                        <div class="forecast-temp">
                            <span class="max">${Math.round(weatherData.daily.temperature_2m_max[i])}°</span>
                            <span class="min">${Math.round(weatherData.daily.temperature_2m_min[i])}°</span>
                        </div>
                        <div class="forecast-desc">${dayWeatherInfo.description}</div>
                    `;
                    forecastList.appendChild(forecastItem);
                }

                // Update weather advisory
                updateWeatherAdvisory(
                    current.temperature_2m,
                    current.relative_humidity_2m,
                    current.wind_speed_10m,
                    current.precipitation || 0,
                    weatherCode
                );

                // Show weather alert if high precipitation probability
                const maxPrecipProb = Math.max(...weatherData.daily.precipitation_probability_max);
                const alertMessage = document.getElementById('alert-message');
                const weatherAlert = document.querySelector('.weather-alert');
                
                if (maxPrecipProb > 70) {
                    alertMessage.textContent = `High probability of rainfall (${maxPrecipProb}%) in the next few days`;
                    weatherAlert.style.display = 'block';
                } else {
                    weatherAlert.style.display = 'none';
                }

                // Show weather content
                document.getElementById('loading').style.display = 'none';
                document.getElementById('weather-content').style.display = 'block';
                document.getElementById('advisory-content').style.display = 'block';

            } catch (error) {
                console.error('Error updating weather display:', error);
                showError('Failed to update weather display');
            }
        }

        // Utility functions
        function showError(message) {
            const errorDiv = document.getElementById('error-message-text');
            const errorContainer = document.querySelector('.error-message');
            const loadingDiv = document.getElementById('loading');
            const weatherContent = document.getElementById('weather-content');
            const advisoryContent = document.getElementById('advisory-content');

            if (errorDiv && errorContainer) {
                errorDiv.textContent = message;
                errorContainer.style.display = 'block';
            }

            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }

            if (weatherContent) {
                weatherContent.style.display = 'none';
            }

            if (advisoryContent) {
                advisoryContent.style.display = 'none';
            }
        }

        function showLoading() {
            const loadingDiv = document.getElementById('loading');
            const errorContainer = document.querySelector('.error-message');
            const weatherContent = document.getElementById('weather-content');
            const advisoryContent = document.getElementById('advisory-content');

            if (loadingDiv) {
                loadingDiv.style.display = 'block';
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }

            if (weatherContent) {
                weatherContent.style.display = 'none';
            }

            if (advisoryContent) {
                advisoryContent.style.display = 'none';
            }
        }

        // Weather data fetching
        async function fetchWeatherData() {
            try {
                showLoading();
                
                const response = await fetch('handlers/weather_data.php');
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to fetch weather data');
                }

                if (!data.success) {
                    throw new Error(data.message || 'Error loading weather data');
                }

                updateWeatherDisplay(data.data, data.weatherCodes);
            } catch (error) {
                console.error('Weather fetch error:', error);
                showError(error.message || 'Failed to load weather data. Please try again later.');
            }
        }

        // Initialize weather data
        document.addEventListener('DOMContentLoaded', fetchWeatherData);
        
        // Refresh weather data every 30 minutes
        setInterval(fetchWeatherData, 30 * 60 * 1000);

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

        $(document).ready(function() {
            function loadWeatherData() {
                $.ajax({
                    url: 'handlers/weather_data.php',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            updateWeatherDisplay(response.data, response.weatherCodes);
                        } else {
                            showError('Failed to load weather data');
                        }
                    },
                    error: function() {
                        showError('Error connecting to weather service');
                    }
                });
            }

            function updateWeatherDisplay(data, weatherCodes) {
                // Update current weather
                const current = data.current;
                const weatherCode = current.weather_code;
                const weatherInfo = weatherCodes[weatherCode] || { description: 'Unknown', icon: 'fa-question' };

                $('.weather-icon').html(`<i class="fas ${weatherInfo.icon}"></i>`);
                $('.temperature').text(`${Math.round(current.temperature_2m)}°C`);
                $('.weather-description').text(weatherInfo.description);
                $('.humidity').text(`${Math.round(current.relative_humidity_2m)}% Humidity`);
                $('.wind-speed').text(`${Math.round(current.wind_speed_10m)} km/h`);

                // Update forecast
                const forecastContainer = $('.forecast-list');
                forecastContainer.empty();

                // Add next 7 days forecast
                for (let i = 0; i < 7; i++) {
                    const date = new Date(data.daily.time[i]);
                    const dayWeatherCode = data.daily.weather_code[i];
                    const dayWeatherInfo = weatherCodes[dayWeatherCode] || { description: 'Unknown', icon: 'fa-question' };

                    const forecastItem = $(`
                        <div class="forecast-item">
                            <div class="forecast-date">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                            <div class="forecast-icon">
                                <i class="fas ${dayWeatherInfo.icon}"></i>
                            </div>
                            <div class="forecast-temp">
                                <span class="max">${Math.round(data.daily.temperature_2m_max[i])}°</span>
                                <span class="min">${Math.round(data.daily.temperature_2m_min[i])}°</span>
                            </div>
                            <div class="forecast-desc">${dayWeatherInfo.description}</div>
                        </div>
                    `);

                    forecastContainer.append(forecastItem);
                }
            }

            function showError(message) {
                $('.temperature').text('Error');
                $('.weather-description').text(message);
                $('.weather-icon i').removeClass('fa-spin').addClass('fa-exclamation-triangle');
            }

            // Load weather data immediately and refresh every 30 minutes
            loadWeatherData();
            setInterval(loadWeatherData, 30 * 60 * 1000);
        });
    </script>
</body>
</html>
