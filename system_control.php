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
    <title>System Control - FarmCS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .control-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .control-card {
            background: var(--card-bg-light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        body.dark-mode .control-card {
            background: var(--card-bg-dark);
        }

        .control-card:hover {
            transform: translateY(-5px);
        }

        .control-card h3 {
            margin-top: 0;
            color: var(--primary-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        body.dark-mode .control-card h3 {
            color: var(--primary-dark);
        }

        .control-card .status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ccc;
        }

        .status-active {
            background: #2ecc71;
        }

        .control-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .control-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            flex: 1;
        }

        .control-btn.primary {
            background: var(--primary-light);
            color: white;
        }

        .control-btn.secondary {
            background: #e74c3c;
            color: white;
        }

        body.dark-mode .control-btn.primary {
            background: var(--primary-dark);
        }

        .control-btn:hover {
            opacity: 0.9;
        }

        .servo-control {
            margin-top: 15px;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 10px;
            border-radius: 5px;
            background: #ddd;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-light);
            cursor: pointer;
        }

        .slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-light);
            cursor: pointer;
        }

        body.dark-mode .slider {
            background: #444;
        }

        body.dark-mode .slider::-webkit-slider-thumb {
            background: var(--primary-dark);
        }

        body.dark-mode .slider::-moz-range-thumb {
            background: var(--primary-dark);
        }

        .angle-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            color: var(--text-light);
        }

        body.dark-mode .angle-labels {
            color: var(--text-dark);
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
                <a href="system-control.php" class="menu-item active">
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
        <section class="fade-in">
            <h1 class="section-title">System Control</h1>
            
            <div class="control-grid">
                <div class="control-card">
                    <h3><i class="fas fa-tint"></i> Irrigation System</h3>
                    <div class="status">
                        <span class="status-indicator status-active"></span>
                        <span>Active</span>
                    </div>
                    <p>Current Schedule: Daily at 6:00 AM</p>
                    <div class="control-actions">
                        <button class="control-btn primary">Adjust Schedule</button>
                        <button class="control-btn secondary">Stop</button>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-temperature-high"></i> Temperature Control</h3>
                    <div class="status">
                        <span class="status-indicator status-active"></span>
                        <span>Active</span>
                    </div>
                    <p>Current Setting: 25°C</p>
                    <div class="control-actions">
                        <button class="control-btn primary">Adjust Temperature</button>
                        <button class="control-btn secondary">Disable</button>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-wind"></i> Ventilation System</h3>
                    <div class="status">
                        <span class="status-indicator"></span>
                        <span>Inactive</span>
                    </div>
                    <p>Last Active: 2 hours ago</p>
                    <div class="control-actions">
                        <button class="control-btn primary">Enable</button>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-lightbulb"></i> Lighting Control</h3>
                    <div class="status">
                        <span class="status-indicator status-active"></span>
                        <span>Active</span>
                    </div>
                    <p>Mode: Automatic (Dawn to Dusk)</p>
                    <div class="control-actions">
                        <button class="control-btn primary">Change Mode</button>
                        <button class="control-btn secondary">Turn Off</button>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-power-off"></i> Light Switch</h3>
                    <div class="status">
                        <span class="status-indicator" id="lightStatus"></span>
                        <span id="lightStatusText">Off</span>
                    </div>
                    <p>Manual light control</p>
                    <div class="control-actions">
                        <button class="control-btn primary" id="toggleLight" onclick="toggleLight()">Turn On</button>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-sync-alt"></i> Servo Control</h3>
                    <div class="status">
                        <span id="servoAngleText">0°</span>
                    </div>
                    <p>Adjust servo motor angle</p>
                    <div class="servo-control">
                        <input type="range" id="servoSlider" min="0" max="180" value="0" class="slider">
                        <div class="angle-labels">
                            <span>0°</span>
                            <span>90°</span>
                            <span>180°</span>
                        </div>
                    </div>
                </div>

                <div class="control-card">
                    <h3><i class="fas fa-sync-alt"></i> Servo Motor 2</h3>
                    <div class="status">
                        <span id="servo2AngleText">0°</span>
                    </div>
                    <p>Control second servo motor</p>
                    <div class="servo2-control">
                        <div class="control-actions">
                            <button class="control-btn primary" id="servo2Up">Up</button>
                            <button class="control-btn secondary" id="servo2Stop">Stop</button>
                            <button class="control-btn primary" id="servo2Down">Down</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <script>
        // Light Switch Control
        let isLightOn = false;
        const toggleLightBtn = document.getElementById('toggleLight');
        const lightStatus = document.getElementById('lightStatus');
        const lightStatusText = document.getElementById('lightStatusText');

        // Check initial LED state
        fetch('handlers/led_control.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateLightUI(data.state);
                }
            })
            .catch(error => console.error('Error:', error));

        function updateLightUI(state) {
            isLightOn = state;
            if (isLightOn) {
                lightStatus.classList.add('status-active');
                lightStatusText.textContent = 'On';
                toggleLightBtn.textContent = 'Turn Off';
                toggleLightBtn.classList.remove('primary');
                toggleLightBtn.classList.add('secondary');
            } else {
                lightStatus.classList.remove('status-active');
                lightStatusText.textContent = 'Off';
                toggleLightBtn.textContent = 'Turn On';
                toggleLightBtn.classList.remove('secondary');
                toggleLightBtn.classList.add('primary');
            }
        }

        function toggleLight() {
            fetch('handlers/led_control.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    state: !isLightOn
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateLightUI(data.state);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Servo Control
        const servoSlider = document.getElementById('servoSlider');
        const servoAngleText = document.getElementById('servoAngleText');
        let lastServoUpdate = 0;
        const SERVO_UPDATE_DELAY = 100; // Minimum time between updates in milliseconds

        // Check initial servo angle
        fetch('handlers/servo_control.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateServoUI(data.angle);
                }
            })
            .catch(error => console.error('Error:', error));

        function updateServoUI(angle) {
            servoSlider.value = angle;
            servoAngleText.textContent = angle + '°';
        }

        function updateServoAngle() {
            const currentTime = Date.now();
            if (currentTime - lastServoUpdate >= SERVO_UPDATE_DELAY) {
                const angle = parseInt(servoSlider.value);
                fetch('handlers/servo_control.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        angle: angle
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateServoUI(data.angle);
                    }
                })
                .catch(error => console.error('Error:', error));
                lastServoUpdate = currentTime;
            }
        }

        servoSlider.addEventListener('input', function() {
            servoAngleText.textContent = this.value + '°';
        });

        servoSlider.addEventListener('change', updateServoAngle);

        // Servo Motor 2 Control
        const servo2Up = document.getElementById('servo2Up');
        const servo2Down = document.getElementById('servo2Down');
        const servo2Stop = document.getElementById('servo2Stop');
        const servo2AngleText = document.getElementById('servo2AngleText');
        let servo2Interval = null;
        let currentServo2Angle = 0;
        let isMovingUp = false;
        let isMovingDown = false;
        const SERVO2_INTERVAL = 50; // Milliseconds between updates

        // Function to update servo 2 angle
        function updateServo2Angle(angle) {
            currentServo2Angle = Math.max(0, Math.min(180, angle));
            servo2AngleText.textContent = currentServo2Angle + '°';
            
            fetch('handlers/servo2_control.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    angle: currentServo2Angle
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    servo2AngleText.textContent = data.angle + '°';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Check initial servo 2 angle
        fetch('handlers/servo2_control.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentServo2Angle = data.angle;
                    servo2AngleText.textContent = currentServo2Angle + '°';
                }
            })
            .catch(error => console.error('Error:', error));

        // Up button - rotate from 0 to 180
        servo2Up.addEventListener('click', () => {
            if (isMovingUp) return; // Prevent multiple clicks
            isMovingUp = true;
            isMovingDown = false;
            clearInterval(servo2Interval);
            
            // Start at current angle and move to 180
            servo2Interval = setInterval(() => {
                if (currentServo2Angle >= 180) {
                    clearInterval(servo2Interval);
                    isMovingUp = false;
                    return;
                }
                updateServo2Angle(currentServo2Angle + 1);
            }, SERVO2_INTERVAL);
        });

        // Down button - rotate from 180 to 0
        servo2Down.addEventListener('click', () => {
            if (isMovingDown) return; // Prevent multiple clicks
            isMovingDown = true;
            isMovingUp = false;
            clearInterval(servo2Interval);
            
            // Start at current angle and move to 0
            servo2Interval = setInterval(() => {
                if (currentServo2Angle <= 0) {
                    clearInterval(servo2Interval);
                    isMovingDown = false;
                    return;
                }
                updateServo2Angle(currentServo2Angle - 1);
            }, SERVO2_INTERVAL);
        });

        // Stop button
        servo2Stop.addEventListener('click', () => {
            clearInterval(servo2Interval);
            isMovingUp = false;
            isMovingDown = false;
        });

        // Hamburger Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
        });

        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            mobileOverlay.classList.remove('active');
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;
        const darkModeIcon = darkModeToggle.querySelector('i');

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            } else {
                localStorage.setItem('darkMode', null);
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
            }
        });
    </script>
</body>
</html>
