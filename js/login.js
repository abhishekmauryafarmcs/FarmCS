document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorDisplay = document.getElementById('errorDisplay');

    function showError(message) {
        if (errorDisplay) {
            errorDisplay.textContent = message;
            errorDisplay.style.display = 'block';
        } else {
            alert(message);
        }
    }

    function hideError() {
        if (errorDisplay) {
            errorDisplay.style.display = 'none';
        }
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            hideError();

            const mobile = document.getElementById('mobile')?.value;
            const password = document.getElementById('password')?.value;

            // Basic validation
            if (!mobile || !password) {
                showError('Please enter both mobile number and password');
                return;
            }

            if (!/^[0-9]{10}$/.test(mobile)) {
                showError('Please enter a valid 10-digit mobile number');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Logging in...';

            try {
                const response = await fetch('handlers/login_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile: mobile,
                        password: password
                    }),
                    credentials: 'same-origin' // This ensures cookies/sessions are sent
                });

                // Check if response is OK and is JSON
                let data;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    throw new Error('Invalid response format from server');
                }
                
                if (data.success) {
                    // Store user data in localStorage
                    localStorage.setItem('user', JSON.stringify(data.data));
                    localStorage.setItem('isLoggedIn', 'true');
                    
                    // Redirect to dashboard
                    window.location.href = 'farmerdashboard.php';
                } else {
                    showError(data.message || 'Login failed. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Login error:', error);
                showError('An error occurred while logging in. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

    // Handle background click
    const overlay = document.querySelector('.overlay');
    const loginContainer = document.querySelector('.login-container');

    if (overlay && loginContainer) {
        document.body.addEventListener('click', function(e) {
            if (!loginContainer.contains(e.target)) {
                window.location.href = 'index.php';
            }
        });
        
        loginContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Handle signup link transition
    document.querySelector('.page-transition')?.addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.querySelector('.login-card');
        container.classList.add('fade-out');
        
        setTimeout(() => {
            window.location.href = this.href;
        }, 500);
    });
});
