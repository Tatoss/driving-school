// Select all menu items
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');

    // Add click event listener for each item
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove 'active' class from all items
            menuItems.forEach(i => i.classList.remove('active'));

            // Add 'active' class to the clicked item
            item.classList.add('active');

            // Perform navigation (example)
            if (item.id === 'home') {
                window.location.href = 'home.php';
            } else if (item.id === 'calendar') {
                window.location.href = 'calendar.php';
            } else if (item.id === 'schedule') {
                window.location.href = 'schedule.php';
            } else if (item.id === 'profile') {
                window.location.href = 'profile.php';
            }
        });
    });
});
