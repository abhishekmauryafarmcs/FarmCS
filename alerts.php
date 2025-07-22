<?php
session_start();
require_once 'config/db_connect.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch alerts from database
$alerts_query = "SELECT * FROM alerts WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC";
$stmt = $conn->prepare($alerts_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$alerts_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts - FarmCS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        :root {
            --bg-light: #f4f6f9;
            --text-light: #333;
            --card-bg-light: #ffffff;
            --card-shadow-light: rgba(0, 0, 0, 0.1);
            --sidebar-bg-light: #2c3e50;
            --sidebar-text-light: rgba(255,255,255,0.7);
            --primary-light: #2ecc71;
            
            --bg-dark: #1a1a2e;
            --text-dark: #e6e6e6;
            --card-bg-dark: #16213e;
            --card-shadow-dark: rgba(255, 255, 255, 0.1);
            --sidebar-bg-dark: #0f3460;
            --sidebar-text-dark: rgba(255,255,255,0.8);
            --primary-dark: #4CAF50;
            --secondary-dark: #3498db;
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        body.dark-mode .dashboard-header {
            background-color: var(--sidebar-bg-dark);
            color: var(--text-dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

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

        body.dark-mode .alerts-container {
            background-color: var(--bg-dark);
        }

        body.dark-mode .alert-card {
            background-color: var(--card-bg-dark);
            color: var(--text-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
            border-left-color: var(--primary-dark) !important;
        }

        body.dark-mode .alert-card.high {
            border-left-color: #ff4444 !important;
        }

        body.dark-mode .alert-card.medium {
            border-left-color: #ffbb33 !important;
        }

        body.dark-mode .alert-card.low {
            border-left-color: #00C851 !important;
        }

        body.dark-mode .alert-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        body.dark-mode .alert-title {
            color: var(--text-dark);
        }

        body.dark-mode .alert-timestamp {
            color: rgba(255,255,255,0.6);
        }

        body.dark-mode .alert-content {
            color: var(--text-dark);
        }

        body.dark-mode .no-alerts {
            color: rgba(255,255,255,0.6);
        }

        body.dark-mode .alert-filters {
            background-color: var(--card-bg-dark);
        }

        body.dark-mode .filter-btn {
            background-color: rgba(255,255,255,0.05);
            color: var(--text-dark);
        }

        body.dark-mode .filter-btn.active {
            background-color: var(--primary-dark);
            color: white;
        }

        body.dark-mode .filter-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }

        body.dark-mode .alert-type {
            opacity: 0.9;
        }

        body.dark-mode .mark-read-btn {
            background-color: rgba(255,255,255,0.1);
            color: var(--text-dark);
        }

        body.dark-mode .delete-btn {
            background-color: rgba(255,0,0,0.1);
            color: #ff4444;
        }

        body.dark-mode .quick-nav-menu {
            background-color: var(--card-bg-dark);
            box-shadow: 0 4px 6px var(--card-shadow-dark);
        }

        body.dark-mode .quick-nav-item {
            color: var(--text-dark);
        }

        body.dark-mode .quick-nav-item:hover {
            background-color: rgba(255,255,255,0.05);
        }

        body.dark-mode .quick-nav-item.active {
            background-color: var(--primary-dark);
            color: white;
        }

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

        .alerts-container {
            padding: 20px;
        }

        .alert-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid;
        }

        .alert-card:hover {
            transform: translateY(-5px);
        }

        .alert-card.high {
            border-left-color: #ff4444;
        }

        .alert-card.medium {
            border-left-color: #ffbb33;
        }

        .alert-card.low {
            border-left-color: #00C851;
        }

        .alert-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .alert-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .alert-timestamp {
            color: #666;
            font-size: 0.9rem;
        }

        .alert-content {
            color: #444;
            line-height: 1.5;
        }

        .alert-footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-type {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            color: white;
        }

        .alert-type.weather {
            background-color: #33b5e5;
        }

        .alert-type.sensor {
            background-color: #aa66cc;
        }

        .alert-type.system {
            background-color: #00C851;
        }

        .alert-actions {
            display: flex;
            gap: 10px;
        }

        .alert-action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .mark-read-btn {
            background-color: #eee;
            color: #333;
        }

        .mark-read-btn:hover {
            background-color: #ddd;
        }

        .delete-btn {
            background-color: #ffebee;
            color: #ff4444;
        }

        .delete-btn:hover {
            background-color: #ffcdd2;
        }

        .no-alerts {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .alert-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f5f5f5;
            color: #333;
        }

        .filter-btn.active {
            background-color: var(--primary-light);
            color: white;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
        }

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

        @media (max-width: 768px) {
            .alerts-container {
                padding: 10px;
            }

            .alert-card {
                padding: 15px;
                margin-bottom: 15px;
            }

            .alert-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .alert-timestamp {
                margin-top: 5px;
            }

            .alert-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .alert-actions {
                width: 100%;
                justify-content: space-between;
            }

            .alert-type {
                margin-top: 10px;
            }

            .alert-filters {
                flex-direction: column;
                gap: 10px;
            }

            .filter-btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .alert-title {
                font-size: 1rem;
            }

            .alert-content {
                font-size: 0.9rem;
            }

            .alert-timestamp {
                font-size: 0.8rem;
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
                <a href="analytics.php" class="menu-item">
                    <i class="fas fa-chart-line menu-icon"></i>
                    Analytics
                </a>
            </li>
            <li>
                <a href="alerts.php" class="menu-item active">
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
        <section id="alerts" class="fade-in">
            <h1 class="section-title">Alerts & Notifications</h1>
            
            <div class="alerts-container">
                <!-- Alert Filters -->
                <div class="alert-filters">
                    <button class="filter-btn active" data-filter="all">All Alerts</button>
                    <button class="filter-btn" data-filter="weather">Weather</button>
                    <button class="filter-btn" data-filter="sensor">Sensor</button>
                    <button class="filter-btn" data-filter="system">System</button>
                </div>

                <?php if ($alerts_result->num_rows > 0): ?>
                    <?php while ($alert = $alerts_result->fetch_assoc()): ?>
                        <div class="alert-card <?php echo htmlspecialchars($alert['priority']); ?>" data-type="<?php echo htmlspecialchars($alert['type']); ?>">
                            <div class="alert-header">
                                <span class="alert-title"><?php echo htmlspecialchars($alert['title']); ?></span>
                                <span class="alert-timestamp"><?php echo date('M d, Y H:i', strtotime($alert['created_at'])); ?></span>
                            </div>
                            <div class="alert-content">
                                <?php echo htmlspecialchars($alert['message']); ?>
                            </div>
                            <div class="alert-footer">
                                <span class="alert-type <?php echo htmlspecialchars($alert['type']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($alert['type'])); ?>
                                </span>
                                <div class="alert-actions">
                                    <?php if (!$alert['is_read']): ?>
                                        <button class="alert-action-btn mark-read-btn" data-alert-id="<?php echo $alert['id']; ?>">
                                            Mark as Read
                                        </button>
                                    <?php endif; ?>
                                    <button class="alert-action-btn delete-btn" data-alert-id="<?php echo $alert['id']; ?>">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-alerts">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #00C851; margin-bottom: 15px;"></i>
                        <h3>All Caught Up!</h3>
                        <p>You have no new alerts at this time.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

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

        // Filter alerts
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Update active state
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filter = button.dataset.filter;
                document.querySelectorAll('.alert-card').forEach(card => {
                    if (filter === 'all' || card.dataset.type === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Handle alert actions
        document.querySelectorAll('.mark-read-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const alertId = button.dataset.alertId;
                try {
                    const response = await fetch('handlers/mark_alert_read.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ alert_id: alertId })
                    });
                    
                    if (response.ok) {
                        button.closest('.alert-actions').removeChild(button);
                    }
                } catch (error) {
                    console.error('Error marking alert as read:', error);
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                if (!confirm('Are you sure you want to delete this alert?')) return;
                
                const alertId = button.dataset.alertId;
                try {
                    const response = await fetch('handlers/delete_alert.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ alert_id: alertId })
                    });
                    
                    if (response.ok) {
                        button.closest('.alert-card').remove();
                        
                        // Check if there are any remaining alerts
                        if (document.querySelectorAll('.alert-card').length === 0) {
                            const noAlerts = document.createElement('div');
                            noAlerts.className = 'no-alerts';
                            noAlerts.innerHTML = `
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: #00C851; margin-bottom: 15px;"></i>
                                <h3>All Caught Up!</h3>
                                <p>You have no new alerts at this time.</p>
                            `;
                            document.querySelector('.alerts-container').appendChild(noAlerts);
                        }
                    }
                } catch (error) {
                    console.error('Error deleting alert:', error);
                }
            });
        });

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
    </script>
</body>
</html>
