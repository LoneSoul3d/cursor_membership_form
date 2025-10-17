    </div> <!-- .admin-main -->

    <script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Confirm before dangerous actions
    function confirmAction(message) {
        return confirm(message || 'Are you sure you want to perform this action?');
    }
    </script>
</body>
</html>