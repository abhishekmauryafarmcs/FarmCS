document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const overlay = document.querySelector('.overlay');
    const container = document.querySelector('.signup-container, .login-container');
    const card = document.querySelector('.signup-card, .login-card');
    const header = document.querySelector('.signup-header, .login-header');
    const formGroups = document.querySelectorAll('.form-group');

    // Handle background click
    if (overlay && container) {
        document.body.addEventListener('click', function(e) {
            if (!container.contains(e.target) && !card.contains(e.target)) {
                window.location.href = 'index.php';
            }
        });
        
        container.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Handle page transition
    document.querySelector('.page-transition')?.addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.querySelector('.signup-card, .login-card');
        container.classList.add('fade-out');
        
        setTimeout(() => {
            window.location.href = this.href;
        }, 500);
    });

    // 3D hover effect
    if (card && header && formGroups.length) {
        let isHovered = false;
        let rafId = null;
        let mouseX = 0;
        let mouseY = 0;
        let centerX = 0;
        let centerY = 0;
        
        function updateCardPosition(e) {
            const rect = card.getBoundingClientRect();
            centerX = rect.left + rect.width / 2;
            centerY = rect.top + rect.height / 2;
            mouseX = e.clientX - centerX;
            mouseY = e.clientY - centerY;
        }
        
        function animate() {
            if (!isHovered) {
                mouseX *= 0.9;
                mouseY *= 0.9;
                
                if (Math.abs(mouseX) < 0.001 && Math.abs(mouseY) < 0.001) {
                    cancelAnimationFrame(rafId);
                    return;
                }
            }
            
            const rotateX = Math.min(Math.max(mouseY * -0.03, -5), 5);
            const rotateY = Math.min(Math.max(mouseX * 0.03, -5), 5);
            
            card.style.transform = `
                perspective(1000px)
                rotateX(${rotateX}deg)
                rotateY(${rotateY}deg)
                translateZ(10px)
                scale3d(1.01, 1.01, 1.01)
            `;
            
            header.style.transform = `
                translateZ(25px)
                rotateX(${rotateX * 0.5}deg)
                rotateY(${rotateY * 0.5}deg)
            `;
            
            formGroups.forEach((group, i) => {
                const z = 15 + (i * 3);
                group.style.transform = `
                    translateZ(${z}px)
                    rotateX(${rotateX * 0.3}deg)
                    rotateY(${rotateY * 0.3}deg)
                `;
            });
            
            rafId = requestAnimationFrame(animate);
        }
        
        card.addEventListener('mouseenter', () => {
            isHovered = true;
            rafId = requestAnimationFrame(animate);
        });
        
        card.addEventListener('mousemove', updateCardPosition);
        
        card.addEventListener('mouseleave', () => {
            isHovered = false;
            if (rafId) {
                cancelAnimationFrame(rafId);
            }
            // Reset transforms
            card.style.transform = '';
            header.style.transform = '';
            formGroups.forEach(group => {
                group.style.transform = '';
            });
        });
        
        // Floating animation for touch devices
        function floatingAnimation() {
            const floatY = Math.sin(Date.now() / 1000) * 2;
            card.style.transform = `
                perspective(1000px)
                translateY(${floatY}px)
                scale3d(1, 1, 1)
            `;
            requestAnimationFrame(floatingAnimation);
        }
        
        // Check if device supports hover
        if (!window.matchMedia('(hover: hover)').matches) {
            floatingAnimation();
        }
    }

    // Add transform-style: preserve-3d to all form groups
    formGroups.forEach(group => {
        group.style.transformStyle = 'preserve-3d';
    });
}); 