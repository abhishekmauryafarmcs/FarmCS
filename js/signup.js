document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const errorDisplay = document.getElementById('errorDisplay');

    function validateForm(formData) {
        const errors = [];
        
        if (!formData.firstName.trim()) errors.push('First Name is required');
        if (!formData.lastName.trim()) errors.push('Last Name is required');
        if (!formData.mobile.trim()) errors.push('Mobile number is required');
        if (!isValidMobile(formData.mobile)) errors.push('Please enter a valid 10-digit mobile number');
        if (!formData.password) errors.push('Password is required');
        if (formData.password.length < 8) errors.push('Password must be at least 8 characters long');
        if (formData.password !== formData.confirmPassword) errors.push('Passwords do not match');
        if (!formData.state) errors.push('State is required');
        if (!formData.district) errors.push('District is required');
        return errors;
    }

    function isValidMobile(mobile) {
        return /^[0-9]{10}$/.test(mobile);
    }

    function displayErrors(errors) {
        if (!errorDisplay) return;
        
        if (Array.isArray(errors)) {
            errorDisplay.innerHTML = errors.map(error => `<p>${error}</p>`).join('');
        } else if (typeof errors === 'string') {
            errorDisplay.innerHTML = `<p>${errors}</p>`;
        }
        errorDisplay.style.display = errors && (Array.isArray(errors) ? errors.length > 0 : true) ? 'block' : 'none';
    }

    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Form submitted');

            const submitButton = this.querySelector('button[type="submit"]');
            if (!submitButton) {
                console.error('Submit button not found');
                return;
            }

            const formData = {
                firstName: document.getElementById('firstName')?.value || '',
                lastName: document.getElementById('lastName')?.value || '',
                mobile: document.getElementById('mobile')?.value || '',
                password: document.getElementById('password')?.value || '',
                confirmPassword: document.getElementById('confirmPassword')?.value || '',
                state: document.getElementById('state')?.value || '',
                district: document.getElementById('district')?.value || ''
            };

            console.log('Form data:', { ...formData, password: '***', confirmPassword: '***' });

            const validationErrors = validateForm(formData);
            if (validationErrors.length > 0) {
                console.log('Validation errors:', validationErrors);
                displayErrors(validationErrors);
                return;
            }

            displayErrors([]);
            submitButton.disabled = true;
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Creating Account...';

            try {
                delete formData.confirmPassword;
                console.log('Sending request to server...');
                
                const response = await fetch('handlers/signup_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                console.log('Server response status:', response.status);
                const data = await response.json();
                console.log('Server response:', data);
                
                if (data.success) {
                    submitButton.textContent = 'Account Created!';
                    alert('Account created successfully! Please login to continue.');
                    window.location.href = 'login.php';
                } else {
                    console.error('Signup failed:', data.message);
                    displayErrors(data.message || 'Failed to create account');
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            } catch (error) {
                console.error('Error during signup:', error);
                displayErrors('Failed to create account. Please try again.');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    }

    // Handle district selection
    const stateSelect = document.getElementById('state');
    const districtSelect = document.getElementById('district');

    if (stateSelect && districtSelect) {
        stateSelect.addEventListener('change', function() {
            const state = this.value;
            districtSelect.disabled = !state;
            
            if (!state) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                return;
            }

            loadDistricts(state);
        });
    }
});
