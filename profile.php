<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=session_expired");
    exit();
}

// Include database connection
include 'config/connection.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmCS - Profile</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --background-light: #f4f6f9;
            --text-color: #333;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--background-light);
            color: var(--text-color);
            line-height: 1.6;
        }

        .page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            transition: all 0.3s;
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-logo img {
            max-width: 150px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .content-wrapper {
            flex-grow: 1;
            padding: 20px;
            background-color: var(--background-light);
            overflow-y: auto;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1050;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 992px) {
            .page-wrapper {
                flex-direction: column;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                height: 100vh;
                width: 260px;
                z-index: 1040;
                transition: left 0.3s ease;
                overflow-y: auto;
            }

            .sidebar.active {
                left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .content-wrapper {
                margin-left: 0;
                padding: 20px 10px;
            }

            .profile-picture {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar.active {
                left: 0;
            }

            .menu-toggle {
                top: 10px;
                left: 10px;
            }

            .profile-picture {
                width: 120px;
                height: 120px;
            }

            .card-body {
                padding: 15px;
            }
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1030;
        }

        .mobile-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu Toggle -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="page-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="images\FarmCSlogo.png" alt="FarmCS Logo">
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="active">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user me-2"></i> User Profile
                                </h3>
                            </div>
                            <div class="card-body text-center">
                                <img src="<?php 
                                    // First, check user_profile_images table
                                    $profile_image_query = "SELECT image_path FROM user_profile_images 
                                        WHERE user_id = ? AND image_type = 'profile' AND is_active = 1 LIMIT 1";
                                    $profile_stmt = $conn->prepare($profile_image_query);
                                    $profile_stmt->bind_param("i", $user_id);
                                    $profile_stmt->execute();
                                    $profile_result = $profile_stmt->get_result();
                                    
                                    if ($profile_result->num_rows > 0) {
                                        // Use image from user_profile_images
                                        $profile_row = $profile_result->fetch_assoc();
                                        echo htmlspecialchars($profile_row['image_path']);
                                    } else {
                                        // Fallback to users table profile_picture
                                        echo !empty($user['profile_picture']) 
                                            ? htmlspecialchars($user['profile_picture']) 
                                            : 'images/default-profile.png'; 
                                    }
                                    
                                    $profile_stmt->close();
                                ?>" 
                                alt="Profile Picture" class="profile-picture mb-4">
                                
                                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                                <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Profile Details</h5>
                                                <ul class="list-unstyled">
                                                    <li><strong>Language:</strong> <?php 
                                                        echo isset($user['language']) 
                                                            ? ($user['language'] == 'en' ? 'English' : 'Hindi')
                                                            : 'Not Set'; 
                                                    ?></li>
                                                    <li><strong>Account Created:</strong> <?php 
                                                        echo date('F j, Y', strtotime($user['created_at'])); 
                                                    ?></li>
                                                    <li><strong>Last Login:</strong> <?php 
                                                        echo !empty($user['last_login']) 
                                                            ? date('F j, Y, g:i a', strtotime($user['last_login']))
                                                            : 'Never'; 
                                                    ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Hamburger Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);

        // Close menu when clicking a sidebar link
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    toggleMenu();
                }
            });
        });
    </script>
</body>
</html>
<?php
// Close database connection
$stmt->close();
$conn->close();
?>
