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
    <title>Analytics Dashboard - FarmCS</title>
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
            --secondary-dark: #3498db;
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

        /* Analytics Cards Dark Mode */
        body.dark-mode .analytics-card {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
            border: 1px solid rgba(255,255,255,0.05);
        }

        body.dark-mode .analytics-card h2 {
            color: var(--primary-dark);
        }

        body.dark-mode .sensor-value {
            color: var(--secondary-dark);
        }

        body.dark-mode .sensor-icon {
            color: var(--primary-dark);
            opacity: 0.8;
        }

        body.dark-mode .sensor-unit {
            color: rgba(255,255,255,0.6);
        }

        /* Graph Section Dark Mode */
        body.dark-mode .graph-section {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
        }

        body.dark-mode .graph-section h2 {
            color: var(--text-dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Modal Dark Mode */
        body.dark-mode .modal {
            background-color: rgba(0,0,0,0.7);
        }

        body.dark-mode .modal-content {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
        }

        body.dark-mode .modal-btn-confirm {
            background-color: #d9534f;
            color: white;
        }

        body.dark-mode .modal-btn-cancel {
            background-color: rgba(255,255,255,0.1);
            color: var(--text-dark);
        }

        /* Dark Mode Chart Styles */
        body.dark-mode .chart-container {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
        }

        /* Improved Chart Visibility */
        body.dark-mode .chart-container canvas {
            filter: brightness(0.9) contrast(1.2);
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

        /* Analytics Grid */
        .analytics-grid {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            gap: 20px;
            margin-bottom: 30px;
        }

        .analytics-card {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .analytics-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .analytics-card h2 {
            margin-bottom: 15px;
            color: var(--primary-light);
            font-size: 1.2rem;
        }

        .sensor-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--secondary-light);
            margin: 10px 0;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .sensor-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary-light);
            opacity: 0.8;
        }

        .sensor-unit {
            font-size: 1rem;
            color: #666;
            margin-top: 5px;
        }

        .sensor-value.value-updating {
            color: var(--primary-light);
            transform: scale(1.05);
        }

        #error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .analytics-grid {
                flex-direction: column;
            }
        }

        /* Styles for section title container */
        .section-title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .clear-sensor-btn {
            background-color: var(--primary-light);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .clear-sensor-btn:hover {
            background-color: var(--secondary-light);
            transform: scale(1.05);
        }

        .clear-sensor-btn:active {
            transform: scale(0.95);
        }

        .clear-sensor-btn i {
            margin-right: 5px;
        }

        /* Additional style for button click effect */
        .clear-sensor-btn.btn-clicked {
            background-color: #4CAF50;
            transform: scale(0.95);
        }

        /* Styles for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-btn-confirm {
            background-color: #d9534f;
            color: white;
        }

        .modal-btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1500;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-light);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced Graph Section Styles */
        .graph-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .graph-section:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .graph-container {
            width: 100%;
            height: 500px; /* Increased height */
            position: relative;
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e0e0e0;
        }

        .graph-title {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-light);
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .graph-title::before,
        .graph-title::after {
            content: '';
            flex-grow: 1;
            height: 2px;
            background-color: var(--primary-light);
            margin: 0 15px;
            opacity: 0.3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .graph-container {
                height: 350px;
            }

            .graph-title {
                font-size: 1.2rem;
            }
        }

        /* Temperature and Humidity Graph Styles */
        .temperature-graph-section,
        .humidity-graph-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .temperature-graph-section:hover,
        .humidity-graph-section:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .graph-container {
                height: 350px;
            }
        }

        /* Light Intensity Graph Styles */
        .light-intensity-graph-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .light-intensity-graph-section:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        /* Sensor Overview Styles */
        .sensor-overview-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .sensor-overview-section:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .sensor-overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .sensor-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sensor-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .sensor-card h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 1.1rem;
        }

        .sensor-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .sensor-unit {
            font-size: 0.8rem;
            color: #7f8c8d;
            margin-left: 5px;
        }

        .status-low { color: #ff6384; }
        .status-optimal { color: #4bc0c0; }
        .status-high { color: #ffcd56; }

        .quick-nav-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .quick-nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .quick-nav-item:hover {
            background-color: #e0e0e0;
            color: #2c3e50;
        }

        .quick-nav-item.active {
            background-color: #3498db;
            color: white;
        }

        .quick-nav-item i {
            font-size: 16px;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .analytics-grid {
                flex-direction: column;
            }

            .analytics-card {
                margin-bottom: 15px;
            }

            .section-title-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .clear-sensor-btn {
                margin-top: 10px;
                width: 100%;
                justify-content: center;
            }

            .graph-section {
                padding: 15px;
            }

            .sensor-value {
                font-size: 2rem;
            }

            .sensor-icon {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .section-title {
                font-size: 1.2rem;
            }

            .analytics-card h2 {
                font-size: 1rem;
            }

            .sensor-value {
                font-size: 1.8rem;
            }

            .sensor-icon {
                font-size: 2rem;
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
                <a href="farmerdashboard.php" class="menu-item">
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
                <a href="analytics.php" class="menu-item active">
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
        <section id="analytics" class="fade-in">
            <div class="section-title-container">
                <h1 class="section-title">Real-Time Sensor Data</h1>
                <button id="clear-sensor-data-btn" class="clear-sensor-btn">
                    <i class="fas fa-trash"></i> Clear Data
                </button>
            </div>
            <div id="error-message"></div>
            <div style="text-align: center; margin-top: 15px; color: #666;">
                <span id="last-updated">Last Updated: --</span>
            </div>
        </section>

        <!-- Sensor Overview Section -->
        <section class="graph-section sensor-overview-section">
            <h2 class="graph-title">Sensor Overview</h2>
            <div class="sensor-overview-grid" id="sensorOverviewGrid">
                <div class="sensor-card" id="soilMoistureOverview">
                    <h3>Soil Moisture</h3>
                    <div class="sensor-value" id="soilMoistureValue">--</div>
                    <span class="sensor-unit">%</span>
                </div>
                <div class="sensor-card" id="temperatureOverview">
                    <h3>Temperature</h3>
                    <div class="sensor-value" id="temperatureValue">--</div>
                    <span class="sensor-unit">°C</span>
                </div>
                <div class="sensor-card" id="humidityOverview">
                    <h3>Humidity</h3>
                    <div class="sensor-value" id="humidityValue">--</div>
                    <span class="sensor-unit">%</span>
                </div>
                <div class="sensor-card" id="lightIntensityOverview">
                    <h3>Light Intensity</h3>
                    <div class="sensor-value" id="lightIntensityValue">--</div>
                    <span class="sensor-unit">Lux</span>
                </div>
            </div>
        </section>

        <!-- Soil Moisture Graph Section -->
        <section class="graph-section">
            <h2 class="graph-title">Real-Time Soil Moisture Levels</h2>
            <div class="graph-container">
                <canvas id="soilMoistureChart"></canvas>
            </div>
        </section>

        <!-- Temperature Graph Section -->
        <section class="graph-section temperature-graph-section">
            <h2 class="graph-title">Real-Time Temperature Monitoring</h2>
            <div class="graph-container">
                <canvas id="temperatureChart"></canvas>
            </div>
        </section>

        <!-- Humidity Graph Section -->
        <section class="graph-section humidity-graph-section">
            <h2 class="graph-title">Real-Time Humidity Tracking</h2>
            <div class="graph-container">
                <canvas id="humidityChart"></canvas>
            </div>
        </section>

        <!-- Light Intensity Graph Section -->
        <section class="graph-section light-intensity-graph-section">
            <h2 class="graph-title">Real-Time Light Intensity Tracking</h2>
            <div class="graph-container">
                <canvas id="lightIntensityChart"></canvas>
            </div>
        </section>
    </main>

    <!-- Confirmation Modal -->
    <div id="clearDataModal" class="modal">
        <div class="modal-content">
            <h2>Clear Sensor Data</h2>
            <p>Are you sure you want to clear all stored sensor data?</p>
            <div class="modal-buttons">
                <button id="confirmClearBtn" class="modal-btn modal-btn-confirm">Clear Data</button>
                <button id="cancelClearBtn" class="modal-btn modal-btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // DOM Elements
        const lastUpdatedEl = document.getElementById('last-updated');
        const errorMessageEl = document.getElementById('error-message');

        // Flag to prevent multiple simultaneous requests
        let isFetching = false;

        async function fetchSensorData() {
            // Prevent concurrent fetches
            if (isFetching) return;
            
            isFetching = true;

            try {
                // Clear previous error message
                errorMessageEl.textContent = '';

                const response = await fetch('handlers/sensor_handler.php');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to fetch sensor data');
                }

                const sensorData = result.data;

                // Update timestamp with precise current time
                lastUpdatedEl.textContent = `Last Updated: ${new Date().toLocaleString()}`;

                // Update charts with sensor data
                updateSoilMoistureChart(parseFloat(sensorData.soilMoisture));
                updateTemperatureChart(parseFloat(sensorData.temperature));
                updateHumidityChart(parseFloat(sensorData.humidity));
                updateLightIntensityChart(parseFloat(sensorData.lightIntensity));

                // Update overview cards
                document.getElementById('soilMoistureValue').textContent = (sensorData.soilMoisture !== null && sensorData.soilMoisture !== undefined) ? sensorData.soilMoisture.toFixed(1) : '--';
                document.getElementById('temperatureValue').textContent = (sensorData.temperature !== null && sensorData.temperature !== undefined) ? sensorData.temperature.toFixed(1) : '--';
                document.getElementById('humidityValue').textContent = (sensorData.humidity !== null && sensorData.humidity !== undefined) ? sensorData.humidity.toFixed(1) : '--';
                document.getElementById('lightIntensityValue').textContent = (sensorData.lightIntensity !== null && sensorData.lightIntensity !== undefined) ? sensorData.lightIntensity.toFixed(1) : '--';

                // Color-coded status for overview cards
                updateSensorCardStatus('soilMoistureOverview', parseFloat(sensorData.soilMoisture), [30, 70]);
                updateSensorCardStatus('temperatureOverview', parseFloat(sensorData.temperature), [15, 30]);
                updateSensorCardStatus('humidityOverview', parseFloat(sensorData.humidity), [40, 70]);
                updateSensorCardStatus('lightIntensityOverview', parseFloat(sensorData.lightIntensity), [500, 2000]);

            } catch (error) {
                console.error('Error fetching sensor data:', error);
                
                // Display error message
                errorMessageEl.textContent = `Error: ${error.message}. Retrying...`;
                
                // Restore default values
            } finally {
                // Reset fetching flag
                isFetching = false;
            }
        }

        // Use precise interval timing
        function startSensorDataFetch() {
            // Clear any existing intervals
            if (window.sensorDataInterval) {
                clearInterval(window.sensorDataInterval);
            }

            // Set a precise 2-second interval
            window.sensorDataInterval = setInterval(fetchSensorData, 2000);
        }

        // Initial setup
        startSensorDataFetch();

        // Optional: Add a way to stop fetching if needed
        function stopSensorDataFetch() {
            if (window.sensorDataInterval) {
                clearInterval(window.sensorDataInterval);
            }
        }

        // Clear sensor data function
        async function clearSensorData() {
            // Show confirmation modal
            const modal = document.getElementById('clearDataModal');
            modal.style.display = 'flex';
        }

        // Modal event listeners
        document.getElementById('confirmClearBtn').addEventListener('click', async () => {
            const modal = document.getElementById('clearDataModal');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const clearBtn = document.getElementById('clear-sensor-data-btn');

            try {
                // Hide modal and show loading
                modal.style.display = 'none';
                loadingOverlay.style.display = 'flex';

                // Send request to clear sensor data
                const response = await fetch('handlers/clear_sensor_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                // Check if response is ok
                if (!response.ok) {
                    // Log error without showing user-facing message
                    console.error(`Server error: ${response.status}`);
                    throw new Error('Server error');
                }

                const result = await response.json();

                if (result.success) {
                    // Show detailed success message
                    errorMessageEl.innerHTML = `
                        <strong>Success:</strong> 
                        Cleared ${result.deleted_rows} sensor data records
                    `;
                    errorMessageEl.style.color = 'green';

                    // Visual feedback
                    clearBtn.classList.add('btn-clicked');
                    setTimeout(() => {
                        clearBtn.classList.remove('btn-clicked');
                    }, 300);
                } else {
                    // Show error message
                    errorMessageEl.innerHTML = `
                        <strong>Error:</strong> 
                        ${result.message || 'Failed to clear sensor data'}
                    `;
                    errorMessageEl.style.color = 'red';

                    // Log additional error details if available
                    if (result.error_details) {
                        console.error('Sensor data clear error:', result.error_details);
                    }
                }
            } catch (error) {
                console.error('Error clearing sensor data:', error);
                errorMessageEl.innerHTML = `
                    <strong>Error:</strong> 
                    Unable to clear sensor data. 
                    Please try again later.
                `;
                errorMessageEl.style.color = 'red';
            } finally {
                // Hide loading overlay
                loadingOverlay.style.display = 'none';
            }
        });

        // Cancel button listener
        document.getElementById('cancelClearBtn').addEventListener('click', () => {
            const modal = document.getElementById('clearDataModal');
            modal.style.display = 'none';
        });

        // Add event listener to clear button
        document.getElementById('clear-sensor-data-btn').addEventListener('click', clearSensorData);

        // Soil Moisture Chart Setup
        const ctx = document.getElementById('soilMoistureChart').getContext('2d');
        const soilMoistureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Soil Moisture (%)',
                    data: [],
                    borderColor: 'rgb(54, 162, 235)', // Vibrant blue
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(54, 162, 235)',
                    pointBorderColor: 'white',
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: 'rgb(54, 162, 235)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // More curved line
                    fill: {
                        target: 'origin',
                        above: 'rgba(54, 162, 235, 0.2)' // Light blue fill
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderDash: [5, 5]
                        },
                        title: {
                            display: true,
                            text: 'Moisture (%)',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Time',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Function to update chart
        function updateSoilMoistureChart(soilMoisture) {
            const now = new Date().toLocaleTimeString();
            
            // Add new data point
            soilMoistureChart.data.labels.push(now);
            soilMoistureChart.data.datasets[0].data.push(soilMoisture);

            // Limit to last 10 data points
            if (soilMoistureChart.data.labels.length > 10) {
                soilMoistureChart.data.labels.shift();
                soilMoistureChart.data.datasets[0].data.shift();
            }

            // Update chart
            soilMoistureChart.update('none'); // Smooth update without animation
        }

        // Temperature Chart Setup
        const tempCtx = document.getElementById('temperatureChart').getContext('2d');
        const temperatureChart = new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperature (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)', // Vibrant red
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: 'white',
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: 'rgb(255, 99, 132)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // More curved line
                    fill: {
                        target: 'origin',
                        above: 'rgba(255, 99, 132, 0.2)' // Light red fill
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderDash: [5, 5]
                        },
                        title: {
                            display: true,
                            text: 'Temperature (°C)',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Time',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Function to update temperature chart
        function updateTemperatureChart(temperature) {
            const now = new Date().toLocaleTimeString();
            
            // Add new data point
            temperatureChart.data.labels.push(now);
            temperatureChart.data.datasets[0].data.push(temperature);

            // Limit to last 10 data points
            if (temperatureChart.data.labels.length > 10) {
                temperatureChart.data.labels.shift();
                temperatureChart.data.datasets[0].data.shift();
            }

            // Update chart
            temperatureChart.update('none');
        }

        // Humidity Chart Setup
        const humidityCtx = document.getElementById('humidityChart').getContext('2d');
        const humidityChart = new Chart(humidityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Humidity (%)',
                    data: [],
                    borderColor: 'rgb(54, 162, 235)', // Vibrant blue
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(54, 162, 235)',
                    pointBorderColor: 'white',
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: 'rgb(54, 162, 235)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // More curved line
                    fill: {
                        target: 'origin',
                        above: 'rgba(54, 162, 235, 0.2)' // Light blue fill
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderDash: [5, 5]
                        },
                        title: {
                            display: true,
                            text: 'Humidity (%)',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Time',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Function to update humidity chart
        function updateHumidityChart(humidity) {
            const now = new Date().toLocaleTimeString();
            
            // Add new data point
            humidityChart.data.labels.push(now);
            humidityChart.data.datasets[0].data.push(humidity);

            // Limit to last 10 data points
            if (humidityChart.data.labels.length > 10) {
                humidityChart.data.labels.shift();
                humidityChart.data.datasets[0].data.shift();
            }

            // Update chart
            humidityChart.update('none');
        }

        // Light Intensity Chart Setup
        const lightIntensityCtx = document.getElementById('lightIntensityChart').getContext('2d');
        const lightIntensityChart = new Chart(lightIntensityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Light Intensity (Lux)',
                    data: [],
                    borderColor: 'rgb(255, 205, 86)', // Vibrant yellow
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(255, 205, 86)',
                    pointBorderColor: 'white',
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: 'rgb(255, 205, 86)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // More curved line
                    fill: {
                        target: 'origin',
                        above: 'rgba(255, 205, 86, 0.2)' // Light yellow fill
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderDash: [5, 5]
                        },
                        title: {
                            display: true,
                            text: 'Light Intensity (Lux)',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Time',
                            color: 'rgba(0, 0, 0, 0.7)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgb(255, 205, 86)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Function to update light intensity chart
        function updateLightIntensityChart(lightIntensity) {
            const now = new Date().toLocaleTimeString();
            
            // Add new data point
            lightIntensityChart.data.labels.push(now);
            lightIntensityChart.data.datasets[0].data.push(lightIntensity);

            // Limit to last 10 data points
            if (lightIntensityChart.data.labels.length > 10) {
                lightIntensityChart.data.labels.shift();
                lightIntensityChart.data.datasets[0].data.shift();
            }

            // Update chart
            lightIntensityChart.update('none');
        }

        // Function to update sensor card status with color coding
        function updateSensorCardStatus(cardId, value, thresholds) {
            const card = document.getElementById(cardId);
            const [lowThreshold, highThreshold] = thresholds;

            // Remove previous status classes
            card.classList.remove('status-low', 'status-optimal', 'status-high');

            if (value < lowThreshold) {
                card.classList.add('status-low');
                card.style.backgroundColor = 'rgba(255, 99, 132, 0.1)';
                card.style.borderLeft = '5px solid #ff6384';
            } else if (value > highThreshold) {
                card.classList.add('status-high');
                card.style.backgroundColor = 'rgba(255, 205, 86, 0.1)';
                card.style.borderLeft = '5px solid #ffcd56';
            } else {
                card.classList.add('status-optimal');
                card.style.backgroundColor = 'rgba(75, 192, 192, 0.1)';
                card.style.borderLeft = '5px solid #4bc0c0';
            }
        }

        // Add additional styles for status
        const statusStyle = document.createElement('style');
        statusStyle.textContent = `
            .status-low { color: #ff6384; }
            .status-optimal { color: #4bc0c0; }
            .status-high { color: #ffcd56; }
        `;
        document.head.appendChild(statusStyle);

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
                updateChartColors(true);
            } else {
                body.classList.remove('dark-mode');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                updateChartColors(false);
            }
        }

        // Toggle dark mode
        function toggleDarkMode() {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('farmcs_dark_mode', 'enabled');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                updateChartColors(true);
            } else {
                localStorage.setItem('farmcs_dark_mode', 'disabled');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                updateChartColors(false);
            }
        }

        // Function to update chart colors based on mode
        function updateChartColors(isDarkMode) {
            const chartConfigs = [
                soilMoistureChart, 
                temperatureChart, 
                humidityChart, 
                lightIntensityChart
            ];

            const darkModeColors = {
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                gridColor: 'rgba(255, 255, 255, 0.1)'
            };

            const lightModeColors = {
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointHoverBackgroundColor: 'rgba(0, 0, 0, 1)',
                gridColor: 'rgba(0, 0, 0, 0.1)'
            };

            chartConfigs.forEach(chart => {
                const colors = isDarkMode ? darkModeColors : lightModeColors;
                
                chart.data.datasets.forEach(dataset => {
                    dataset.backgroundColor = colors.backgroundColor;
                    dataset.borderColor = colors.borderColor;
                    dataset.pointBackgroundColor = colors.pointBackgroundColor;
                    dataset.pointHoverBackgroundColor = colors.pointHoverBackgroundColor;
                });

                chart.options.scales.x.grid.color = colors.gridColor;
                chart.options.scales.y.grid.color = colors.gridColor;
                chart.options.scales.x.ticks.color = isDarkMode ? '#e6e6e6' : '#333';
                chart.options.scales.y.ticks.color = isDarkMode ? '#e6e6e6' : '#333';

                chart.update();
            });
        }

        // Initialize dark mode on page load
        checkDarkMode();

        // Add event listener to dark mode toggle
        darkModeToggle.addEventListener('click', toggleDarkMode);
    </script>
</body>
</html>
