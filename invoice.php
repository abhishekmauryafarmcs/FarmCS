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
    <title>Invoice - FarmCS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .invoice-form {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        #invoiceDetails {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        #invoiceDetails h2 {
            color: #333;
            margin-bottom: 15px;
        }

        #invoiceDetails p {
            margin: 10px 0;
            font-size: 16px;
        }

        #invoiceDetails span {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="header-brand">
            <i class="fas fa-leaf"></i> FarmCS
            <a href="farmerdashboard.php" class="home-link" title="Go to Dashboard">
                <i class="fas fa-home"></i>
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
                <a href="invoice.php" class="menu-item active">
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
    <main class="dashboard-content">
        <section id="invoice" class="fade-in">
            <h1 class="section-title">Generate Invoice</h1>
            <div class="invoice-form">
                <form id="invoiceForm">
                    <div class="form-group">
                        <label for="landRegistryNo">Land Registry No.:</label>
                        <select id="landRegistryNo" name="landRegistryNo" required>
                            <option value="">Select Land Registry No.</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tehsil">Tehsil:</label>
                        <select id="tehsil" name="tehsil" required>
                            <option value="">Select Tehsil</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="village">Village:</label>
                        <select id="village" name="village" required>
                            <option value="">Select Village</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plotNumber">Plot Number:</label>
                        <input type="text" id="plotNumber" name="plotNumber" required disabled>
                    </div>

                    <button type="button" onclick="calculateCost()">Generate Invoice</button>
                </form>

                <div id="invoiceDetails">
                    <h2>Invoice Details</h2>
                    <p>Area (Hectares): <span id="area"></span></p>
                    <p>Dimensions (m x m): <span id="dimensions"></span></p>
                    <p>No. of Sprinklers: <span id="sprinklers"></span></p>
                    <p>No. of Moisture Sensors: <span id="sensors"></span></p>
                    <p>Total Cost (INR): <span id="totalCost"></span></p>
                </div>
            </div>
        </section>
    </main>
    <script src="js/invoice.js"></script>
</body>
</html> 