<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Data - FarmCS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- D3.js for map visualization -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        :root {
            --primary-green: #2E7D32;
            --accent-orange: #FF5722;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
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
            line-height: 1.6;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100vw;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        }

        .nav-links a:hover {
            color: var(--primary-green);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .login-btn, .signup-btn {
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
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

        .main-content {
            width: 100%;
            max-width: 100vw;
            margin-top: 80px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-left: auto;
            margin-right: auto;
            overflow-x: hidden;
        }

        .section-container {
            background: white;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            min-height: 600px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .section-title {
            color: var(--primary-green);
            font-size: 1.8rem;
            margin-bottom: 0.4cm;
            text-align: center;
            font-weight: 600;
            padding-top: 0;
            line-height: 1.2;
            height: 40px;
        }

        .section-content {
            display: flex;
            gap: 20px;
            align-items: stretch;
            min-height: 500px;
        }

        .crops-list {
            width: 700px;
            padding: 12px;
            background: rgba(46, 125, 50, 0.05);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .crops-title {
            color: var(--primary-green);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            background: white;
            padding: 8px;
            border-radius: 6px;
            width: 100%;
            grid-column: 1 / -1;
        }

        .crops-items {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            width: 100%;
        }

        .crops-items li {
            padding: 8px 15px;
            background: white;
            border-radius: 4px;
            color: var(--dark-text);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .crops-items li:hover {
            transform: translateX(5px);
            background: rgba(46, 125, 50, 0.1);
        }

        .crops-items li::before {
            content: '🌱';
            font-size: 13px;
            flex-shrink: 0;
        }

        #indiaMap {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            min-height: 600px;
        }

        #indiaMap svg {
            width: 100%;
            height: 100%;
            max-height: 700px;
        }

        #districtMap {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            max-width: 1400px;
            height: 100%;
            min-height: 500px;
            overflow: hidden;
        }

        .map-title {
            color: var(--primary-green);
            font-size: 1.8rem;
            margin-bottom: 0.6cm;
            text-align: center;
            font-weight: 600;
            flex-shrink: 0;
            padding-top: 0;
            line-height: 1.2;
            height: 40px;
        }

        .state {
            fill: #e0e0e0;
            stroke: #fff;
            stroke-width: 1px;
            transition: fill 0.3s;
        }

        .state:hover {
            fill: #2E7D32;
            cursor: pointer;
        }

        .tooltip {
            position: fixed;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #ddd;
            border-radius: 4px;
            pointer-events: none;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            transform: translate(-50%, -120%);
        }

        .legend {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 8px 0;
        }

        .legend-color {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            border-radius: 4px;
        }

        @media screen and (min-width: 1200px) {
            .map-container {
                padding: 25px;
                min-height: 700px;
            }

            .map-title {
                font-size: 2rem;
                margin-bottom: 15px;
            }
        }

        @media screen and (max-width: 1400px) {
            .crops-list {
                width: 600px;
            }
            
            .crops-items {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
            }
            
            .crops-items li {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        @media screen and (max-width: 1000px) {
            .crops-list {
                width: 500px;
            }
            
            .crops-items {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
        }

        @media screen and (max-width: 768px) {
            header {
                padding: 10px;
                gap: 10px;
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

            .login-btn, .signup-btn {
                padding: 5px 10px;
                font-size: 12px;
            }

            .main-content {
                padding: 10px;
            }

            .map-container {
                padding: 10px;
                min-height: auto;
                height: calc(100vh - 100px);
            }

            .map-content {
                flex-direction: column;
                height: calc(100% - 40px);
            }

            .crops-list {
                width: 100%;
                margin-bottom: 10px;
                flex-shrink: 1;
            }

            .map-title {
                font-size: 1.4rem;
                margin-bottom: 0.3cm;
                height: 30px;
            }

            #indiaMap, #districtMap {
                min-height: 300px;
            }

            .section-container {
                padding: 10px;
                min-height: 500px;
            }

            .section-content {
                flex-direction: column;
                min-height: 400px;
            }

            .section-title {
                font-size: 1.4rem;
                margin-bottom: 0.3cm;
                height: 30px;
            }
        }

        @media screen and (max-width: 480px) {
            .crops-items {
                grid-template-columns: repeat(1, 1fr);
            }
            
            .crops-items li {
                padding: 8px 15px;
                font-size: 13px;
            }
        }

        /* Add new styles for the district analysis section */
        .analysis-controls {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 15px;
            background: rgba(46, 125, 50, 0.05);
            border-radius: 8px;
            margin-bottom: 20px;
            width: 250px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .control-group label {
            font-size: 14px;
            color: var(--dark-text);
            font-weight: 500;
        }

        .select-input {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: var(--dark-text);
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
            width: 100%;
        }

        .select-input:hover, .select-input:focus {
            border-color: var(--primary-green);
            outline: none;
        }

        #districtChart {
            flex: 1;
            min-height: 400px;
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        @media screen and (max-width: 768px) {
            .section-content {
                flex-direction: column;
            }

            .analysis-controls {
                width: 100%;
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

        body.dark-mode .section-container {
            background: #252525;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .section-title {
            color: #fff;
        }

        body.dark-mode .crops-list {
            background: rgba(46, 125, 50, 0.15);
        }

        body.dark-mode .crops-title,
        body.dark-mode .crops-items li {
            background: #333;
            color: #fff;
        }

        body.dark-mode .crops-items li:hover {
            background: rgba(46, 125, 50, 0.3);
        }

        body.dark-mode .state {
            fill: #444;
        }

        body.dark-mode .tooltip {
            background: rgba(50, 50, 50, 0.95);
            border-color: #555;
            color: #fff;
        }

        body.dark-mode .legend {
            background: #333;
            color: #fff;
        }

        body.dark-mode .analysis-controls {
            background: rgba(46, 125, 50, 0.15);
        }

        body.dark-mode .control-group label {
            color: #eee;
        }

        body.dark-mode .select-input {
            background: #333;
            color: #fff;
            border-color: #555;
        }

        body.dark-mode .select-input:hover,
        body.dark-mode .select-input:focus {
            border-color: var(--primary-green);
        }

        body.dark-mode #districtChart {
            background: #333;
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

    <div class="main-content">
        <div class="section-container">
            <h2 class="section-title">State-wise Crop Production</h2>
            <div class="section-content">
                <div class="crops-list">
                    <h3 class="crops-title">Sprinkler Suitable Crops</h3>
                    <ul class="crops-items">
                        <!-- Crops will be loaded dynamically -->
                    </ul>
                </div>
                <div id="indiaMap"></div>
            </div>
        </div>

        <div class="section-container">
            <h2 class="section-title">District-wise Crop Analysis</h2>
            <div class="section-content">
                <div class="analysis-controls">
                    <div class="control-group">
                        <label for="stateSelect">Select State:</label>
                        <select id="stateSelect" class="select-input">
                            <!-- States will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="graphSelect">Select Graph:</label>
                        <select id="graphSelect" class="select-input">
                            <option value="piechart">Donut chart</option>
                            <option value="barchart">Bar Chart</option>
                            <option value="sunburst">Sunburst</option>
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="seasonSelect">Select Season:</label>
                        <select id="seasonSelect" class="select-input">
                            <option value="Kharif">Kharif</option>
                            <option value="Rabi">Rabi</option>
                            <option value="Whole Year">Whole Year</option>
                        </select>
                    </div>
                </div>
                <div id="districtChart"></div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button id="darkModeToggle" class="dark-mode-toggle">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        // Hamburger Menu Toggle
        const hamburger = document.querySelector('.hamburger-menu');
        const navLinks = document.querySelector('.nav-links');

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

        // Load Crops List
        async function loadCropsList() {
            try {
                const response = await fetch('India-map-cropdata/df2013.csv');
                if (!response.ok) {
                    throw new Error('Failed to load crops data');
                }

                const csvText = await response.text();
                const lines = csvText.split('\n').slice(1); // Skip header
                
                // Extract unique crops
                const uniqueCrops = new Set();
                lines.forEach(line => {
                    if (line) {
                        const columns = line.split(',');
                        if (columns.length >= 5) {
                            const crop = columns[4].trim();
                            uniqueCrops.add(crop);
                        }
                    }
                });

                // Sort crops alphabetically
                const sortedCrops = Array.from(uniqueCrops).sort();

                // Update the crops list
                const cropsList = document.querySelector('.crops-items');
                cropsList.innerHTML = sortedCrops.map(crop => 
                    `<li>${crop}</li>`
                ).join('');

            } catch (error) {
                console.error('Error loading crops:', error);
                document.querySelector('.crops-items').innerHTML = `
                    <li style="color: red;">Error loading crops data</li>`;
            }
        }

        // India Map Visualization
        async function createIndiaMap() {
            try {
                // Load India GeoJSON data and production data
                const [geoResponse, dataResponse] = await Promise.all([
                    fetch('India-map-cropdata/india.json'),
                    fetch('India-map-cropdata/state_crop_production.csv')
                ]);

                if (!geoResponse.ok || !dataResponse.ok) {
                    throw new Error('Failed to load map or data');
                }

                const indiaData = await geoResponse.json();
                const productionText = await dataResponse.text();
                
                // Parse CSV data
                const productionData = {};
                const lines = productionText.split('\n').slice(1); // Skip header
                lines.forEach(line => {
                    if (line) {
                        const [state, production] = line.split(',');
                        productionData[state.trim()] = parseFloat(production);
                    }
                });

                // Clear any existing content
                d3.select('#indiaMap').html('');

                // Set up map dimensions
                const container = document.getElementById('indiaMap');
                const width = container.clientWidth;
                const height = container.clientHeight;
                const minDimension = Math.min(width, height);
                const scale = window.innerWidth > 1200 ? minDimension * 1.3 : minDimension * 1.1;

                // Create SVG container
                const svg = d3.select('#indiaMap')
                    .append('svg')
                    .attr('width', '100%')
                    .attr('height', '100%')
                    .attr('viewBox', `0 0 ${width} ${height}`)
                    .attr('preserveAspectRatio', 'xMidYMid meet');

                // Create projection with adjusted scale
                const projection = d3.geoMercator()
                    .center([82, 23])
                    .scale(scale)
                    .translate([width / 2, height / 2]);

                // Create path generator
                const path = d3.geoPath().projection(projection);

                // Initialize color scale
                const colorScale = d3.scaleSequential()
                    .domain([0, 12])
                    .interpolator(d3.interpolateYlGn);

                // Create tooltip
                const tooltip = d3.select('#indiaMap')
                    .append('div')
                    .attr('class', 'tooltip')
                    .style('opacity', 0);

                // Add container for map
                const mapGroup = svg.append('g')
                    .attr('class', 'map-group');

                // Draw states with adjusted styles
                const states = mapGroup.selectAll('path')
                    .data(indiaData.features)
                    .enter()
                    .append('path')
                    .attr('class', 'state')
                    .attr('d', path)
                    .style('fill', d => {
                        const production = productionData[d.properties.st_nm];
                        return production !== undefined ? colorScale(production) : '#e0e0e0';
                    })
                    .style('stroke-width', '0.5px');

                // Add hover effects
                states
                    .on('mouseover', function(event, d) {
                        const production = productionData[d.properties.st_nm];
                        d3.select(this)
                            .style('fill', '#2E7D32')
                            .style('stroke-width', '1px');
                        
                        tooltip.transition()
                            .duration(200)
                            .style('opacity', .9);

                        tooltip.html(`
                            <strong>${d.properties.st_nm}</strong><br/>
                            Production: ${production !== undefined ? production.toFixed(1) : 'N/A'}%
                        `)
                            .style('left', (event.clientX) + 'px')
                            .style('top', (event.clientY) + 'px');
                    })
                    .on('mousemove', function(event, d) {
                        tooltip
                            .style('left', (event.clientX) + 'px')
                            .style('top', (event.clientY) + 'px');
                    })
                    .on('mouseout', function(d) {
                        d3.select(this)
                            .style('fill', d => {
                                const production = productionData[d.properties.st_nm];
                                return production !== undefined ? colorScale(production) : '#e0e0e0';
                            })
                            .style('stroke-width', '0.5px');
                        
                        tooltip.transition()
                            .duration(500)
                            .style('opacity', 0);
                    });

                // Update legend position for better visibility
                const legend = svg.append('g')
                    .attr('class', 'legend')
                    .attr('transform', `translate(${width - 150}, ${height - 150})`);

                const legendData = [
                    { color: colorScale(0), label: '0%' },
                    { color: colorScale(4), label: '4%' },
                    { color: colorScale(8), label: '8%' },
                    { color: colorScale(12), label: '12%' }
                ];

                const legendItems = legend.selectAll('.legend-item')
                    .data(legendData)
                    .enter()
                    .append('g')
                    .attr('class', 'legend-item')
                    .attr('transform', (d, i) => `translate(0, ${i * 25})`);

                legendItems.append('rect')
                    .attr('width', 20)
                    .attr('height', 20)
                    .style('fill', d => d.color);

                legendItems.append('text')
                    .attr('x', 30)
                    .attr('y', 15)
                    .text(d => d.label)
                    .style('font-size', '12px');

            } catch (error) {
                console.error('Error creating map:', error);
                console.error('Fetch responses:', {
                    geoResponse: geoResponse ? geoResponse.status : 'No response',
                    dataResponse: dataResponse ? dataResponse.status : 'No response'
                });
                document.getElementById('indiaMap').innerHTML = `
                    <div style="color: red; text-align: center; padding: 20px;">
                        Error loading map data. Please try again later.<br>
                        ${error.message}
                    </div>`;
            }
        }

        // District Analysis Visualization
        async function loadDistrictAnalysis() {
            try {
                // Load data from graphdata2013.csv
                const response = await fetch('all-graph-data/graphdata2013.csv');
                if (!response.ok) {
                    throw new Error('Failed to load data');
                }

                const csvText = await response.text();
                const lines = csvText.split('\n').slice(1); // Skip header
                const data = lines.map(line => {
                    if (!line) return null;
                    const [state, district, year, season, crop, area, production, percentage] = line.split(',');
                    return {
                        state: state?.trim(),
                        district: district?.trim(),
                        season: season?.trim(),
                        crop: crop?.trim(),
                        area: parseFloat(area),
                        production: parseFloat(production),
                        percentage: parseFloat(percentage)
                    };
                }).filter(item => item && item.state && item.district && !isNaN(item.production));

                // Get unique states
                const states = [...new Set(data.map(item => item.state))].sort();

                // Populate state dropdown
                const stateSelect = document.getElementById('stateSelect');
                stateSelect.innerHTML = `
                    <option value="">Select a State</option>
                    ${states.map(state => 
                        `<option value="${state}">${state}</option>`
                    ).join('')}
                `;

                // Add event listeners
                stateSelect.addEventListener('change', updateChart);
                seasonSelect.addEventListener('change', updateChart);
                graphSelect.addEventListener('change', updateChart);

                async function updateChart() {
                    const selectedState = stateSelect.value;
                    if (!selectedState) return;

                    const selectedSeason = seasonSelect.value;
                    const selectedGraph = graphSelect.value;

                    // Filter data based on selections
                    const filteredData = data.filter(item => {
                        const seasonMatch = selectedSeason === "Whole Year" ? 
                            (item.season.includes('Kharif') || item.season.includes('Rabi')) :
                            item.season.includes(selectedSeason);
                        return item.state === selectedState && seasonMatch;
                    });

                    // Aggregate production by district
                    const districtProduction = {};
                    filteredData.forEach(item => {
                        if (!districtProduction[item.district]) {
                            districtProduction[item.district] = 0;
                        }
                        districtProduction[item.district] += item.production;
                    });

                    // Convert to array and sort
                    const sortedDistricts = Object.entries(districtProduction)
                        .map(([district, production]) => ({ district, production }))
                        .sort((a, b) => b.production - a.production);

                    let chartData, layout;
                    const seasonText = selectedSeason === "Whole Year" ? "Annual" : selectedSeason;

                    switch(selectedGraph) {
                        case 'piechart':
                            // Take top 10 districts for better pie chart visibility
                            const top10Districts = sortedDistricts.slice(0, 10);
                            const otherDistricts = sortedDistricts.slice(10);
                            const otherProduction = otherDistricts.reduce((sum, d) => sum + d.production, 0);

                            let pieData = [...top10Districts];
                            if (otherDistricts.length > 0) {
                                pieData.push({ district: 'Others', production: otherProduction });
                            }

                            chartData = [{
                                values: pieData.map(d => d.production),
                                labels: pieData.map(d => d.district),
                                type: 'pie',
                                hole: 0.4,
                                textinfo: 'label+percent',
                                hoverinfo: 'label+value+percent',
                                marker: {
                                    colors: pieData.map((_, i) => 
                                        `hsl(${(i * 360 / pieData.length)}, 70%, 50%)`)
                                }
                            }];
                            layout = {
                                title: {
                                    text: `Top 10 Districts by ${seasonText} Agricultural Production in ${selectedState}`,
                                    font: { size: 18 }
                                },
                                height: 600,
                                showlegend: true,
                                legend: {
                                    orientation: 'v',
                                    xanchor: 'right',
                                    x: 1.1,
                                    font: { size: 10 }
                                }
                            };
                            break;

                        case 'barchart':
                            // Take top 15 districts for bar chart
                            const top15Districts = sortedDistricts.slice(0, 15);
                            chartData = [{
                                x: top15Districts.map(d => d.district),
                                y: top15Districts.map(d => d.production),
                                type: 'bar',
                                marker: {
                                    color: top15Districts.map((_, i) => 
                                        `hsl(120, ${50 + (i * 30 / top15Districts.length)}%, 50%)`),
                                },
                                text: top15Districts.map(d => d.production.toLocaleString()),
                                textposition: 'auto',
                            }];
                            layout = {
                                title: {
                                    text: `Top 15 Districts by ${seasonText} Agricultural Production in ${selectedState}`,
                                    font: { size: 18 }
                                },
                                xaxis: {
                                    title: 'District',
                                    tickangle: -45,
                                    tickfont: { size: 10 }
                                },
                                yaxis: {
                                    title: 'Total Production',
                                    tickformat: '.2s'
                                },
                                height: 600,
                                margin: { b: 100, l: 100, r: 50, t: 50 }
                            };
                            break;

                        case 'sunburst':
                            const totalProduction = sortedDistricts.reduce((sum, d) => sum + d.production, 0);
                            
                            // Group districts into regions based on production percentages
                            const regions = {};
                            sortedDistricts.forEach(d => {
                                const percentage = (d.production / totalProduction) * 100;
                                let region = 'High Production';
                                if (percentage < 5) region = 'Low Production';
                                else if (percentage < 15) region = 'Medium Production';
                                
                                if (!regions[region]) regions[region] = [];
                                regions[region].push(d);
                            });

                            // Create hierarchical data
                            const labels = ['Total', selectedState];
                            const parents = ['', 'Total'];
                            const values = [totalProduction, totalProduction];

                            Object.entries(regions).forEach(([region, districts]) => {
                                labels.push(region);
                                parents.push(selectedState);
                                values.push(districts.reduce((sum, d) => sum + d.production, 0));

                                districts.forEach(d => {
                                    labels.push(d.district);
                                    parents.push(region);
                                    values.push(d.production);
                                });
                            });

                            chartData = [{
                                type: 'sunburst',
                                labels: labels,
                                parents: parents,
                                values: values,
                                branchvalues: 'total',
                                textinfo: 'label+percent parent',
                                maxdepth: 3,
                                insidetextorientation: 'radial',
                                textfont: { size: 11 }
                            }];
                            layout = {
                                title: {
                                    text: `${seasonText} District Production Distribution in ${selectedState}`,
                                    font: { size: 18 }
                                },
                                height: 550,
                                width: 550,
                                margin: { l: 0, r: 0, b: 0, t: 50 },
                                showlegend: false
                            };
                            break;
                    }

                    const config = {
                        responsive: true,
                        displayModeBar: false,
                        toImageButtonOptions: {
                            format: 'png',
                            filename: `${selectedState}_${selectedGraph}_production`,
                            height: 800,
                            width: 1200,
                            scale: 2
                        }
                    };

                    Plotly.newPlot('districtChart', chartData, layout, config);
                }

            } catch (error) {
                console.error('Error loading district analysis:', error);
                console.error('Fetch response:', {
                    response: response ? response.status : 'No response'
                });
                document.getElementById('districtChart').innerHTML = `
                    <div style="color: red; text-align: center; padding: 20px;">
                        Error loading district analysis data.<br>
                        ${error.message}
                    </div>`;
            }
        }

        // Initialize visualizations when page loads
        document.addEventListener('DOMContentLoaded', () => {
            createIndiaMap();
            loadCropsList();
            loadDistrictAnalysis();
        });

        // Add resize handler to redraw map when window is resized
        window.addEventListener('resize', () => {
            createIndiaMap();
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
    </script>

    <!-- Add Plotly.js for the bar chart -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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