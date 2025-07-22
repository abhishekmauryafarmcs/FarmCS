    </div> <!-- Close container from header -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Custom global scripts -->
    <script>
        // Global error handling
        window.addEventListener('error', function(event) {
            console.error('Unhandled error:', event.error);
            alert('An unexpected error occurred. Please check the browser console.');
        });

        // Optional: Add any global JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Tooltips initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>
</html>
<?php
// Optional: Add any final PHP processing or logging here
?>
