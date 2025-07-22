<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and include debugging
session_start();

// Debugging function
function debug_log($message) {
    error_log("[SETTINGS DEBUG] " . $message);
}

// Include connection with additional debugging
try {
    include 'config/connection.php';
} catch (Exception $e) {
    debug_log("Connection Include Error: " . $e->getMessage());
    die("Database connection failed: " . $e->getMessage());
}

// Validate database connection
if (!$conn) {
    debug_log("Database Connection Failed");
    die("Database connection is null. Check your connection settings.");
}

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    debug_log("No user_id in session");
    header("Location: login.php?error=session_expired");
    exit();
}

// Retrieve and validate user ID
$user_id = $_SESSION['user_id'];
debug_log("User ID from session: " . $user_id);

// Comprehensive user retrieval with extensive error handling
try {
    // Prepare statement with detailed logging
    $query = "SELECT * FROM users WHERE user_id = ?";
    debug_log("Preparing query: " . $query);
    
    // Prepare the statement with error checking
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        debug_log("Statement preparation failed: " . $conn->error);
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }
    
    // Bind parameters with logging
    $stmt->bind_param("i", $user_id);
    
    // Execute with detailed error tracking
    if (!$stmt->execute()) {
        debug_log("Statement execution failed: " . $stmt->error);
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }
    
    // Get result with error handling
    $result = $stmt->get_result();
    
    if ($result === false) {
        debug_log("Result retrieval failed: " . $stmt->error);
        throw new Exception("Failed to get query results: " . $stmt->error);
    }
    
    // Check if user exists
    if ($result->num_rows === 0) {
        debug_log("No user found with ID: " . $user_id);
        throw new Exception("User not found. Please log in again.");
    }
    
    // Fetch user data with null coalescing
    $user = $result->fetch_assoc() ?? [];
    
    // Validate fetched user data
    if (empty($user)) {
        debug_log("Empty user data retrieved");
        throw new Exception("Unable to retrieve user information.");
    }
    
    debug_log("User data retrieved successfully: " . print_r($user, true));
    
} catch (Exception $e) {
    // Comprehensive error logging
    debug_log("Exception in user retrieval: " . $e->getMessage());
    debug_log("Exception trace: " . $e->getTraceAsString());
    
    // Redirect to error page or login
    header("Location: login.php?error=user_retrieval_failed");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmCS - User Settings</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
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

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e1e4e8;
            padding: 15px 20px;
        }

        .form-control {
            border-color: #ced4da;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .page-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
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

            .card {
                margin-bottom: 15px;
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

            .form-control {
                font-size: 14px;
                padding: 8px 12px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 12px;
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
                    <a href="farmerdashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'farmerdashboard.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
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
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-cog me-2"></i> User Settings
                                </h3>
                            </div>
                            <div class="card-body">
                                <form id="settings-form" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="mb-4">
                                                <img src="<?php 
                                                    echo !empty($user['profile_picture']) 
                                                        ? htmlspecialchars($user['profile_picture']) 
                                                        : 'images/default-profile.png'; 
                                                ?>" 
                                                alt="Profile Picture" class="profile-picture" id="profile-picture-preview">
                                                
                                                <div class="mt-3">
                                                    <input type="file" id="profile-picture-upload" name="profile_picture" 
                                                           accept="image/*" class="form-control d-none">
                                                    <button type="button" class="btn btn-primary" 
                                                            onclick="document.getElementById('profile-picture-upload').click()">
                                                        <i class="fas fa-upload me-2"></i> Change Picture
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                                           value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                                           value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div id="settings-message" class="alert" style="display:none;"></div>
                                            
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-primary" id="save-changes-btn">
                                                    <i class="fas fa-save me-2"></i> Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('settings-form');
            const messageDiv = document.getElementById('settings-message');
            const profilePictureUpload = document.getElementById('profile-picture-upload');
            const profilePicturePreview = document.getElementById('profile-picture-preview');
            const saveChangesBtn = document.getElementById('save-changes-btn');

            // Profile Picture Preview
            profilePictureUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        profilePicturePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Form Submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Create FormData object
                const formData = new FormData(form);

                // Disable save button and show loading state
                saveChangesBtn.disabled = true;
                saveChangesBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
                messageDiv.innerHTML = 'Saving changes...';
                messageDiv.className = 'alert alert-info';
                messageDiv.style.display = 'block';

                // Send AJAX request
                fetch('process_settings.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success message
                        messageDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data.message;
                        messageDiv.className = 'alert alert-success';
                        
                        // Update profile picture if a new one was uploaded
                        if (data.profile_picture) {
                            profilePicturePreview.src = data.profile_picture;
                        }

                        // Reload page after 2 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        // Error message
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> ' + data.message;
                        messageDiv.className = 'alert alert-danger';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> An unexpected error occurred.';
                    messageDiv.className = 'alert alert-danger';
                })
                .finally(() => {
                    // Re-enable save button
                    saveChangesBtn.disabled = false;
                    saveChangesBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Changes';
                });
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
