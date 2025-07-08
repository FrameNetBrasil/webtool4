// Mobile sidebar functionality with dynamic overlay creation
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.app-sidebar');
    let overlay = document.querySelector('.sidebar-overlay');

    // Create overlay if it doesn't exist
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function() {
            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent body scroll
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Restore body scroll
    }

    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });

    // Close sidebar when window is resized to desktop size
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });
});
