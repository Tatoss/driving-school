<footer>
    <div class="bottom-menu">
        <div class="menu-item" onclick="navigateTo('home')">
            <i class="icon fa fa-home"></i>
            <span>Home</span>
        </div>
        <div class="menu-item" onclick="navigateTo('schedule')">
            <i class="icon fa fa-calendar"></i>
            <span>Schedule</span>
        </div>
        <div class="menu-item" onclick="navigateTo('add-slot')">
            <i class="icon fa fa-plus-circle"></i>
            <span>Add Slot</span>
        </div>
        <div class="menu-item" onclick="navigateTo('profile')">
            <i class="icon fa fa-user"></i>
            <span>Profile</span>
        </div>
    </div>
</footer>

<script>
    // Simple navigation handler
    function navigateTo(page) {
        alert(`Navigating to ${page}`);
        // Replace alert with actual navigation logic
        // Example: window.location.href = `${page}.php`;
    }
</script>

<style>
    /* Footer styling */
    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #ffffff;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .bottom-menu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
    }

    .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #888;
        font-size: 14px;
        cursor: pointer;
        transition: color 0.3s, transform 0.3s;
    }

    .menu-item .icon {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .menu-item:hover {
        color: #007bff;
        transform: scale(1.1);
    }

    /* Mobile-first responsiveness */
    @media (max-width: 768px) {
        footer {
            padding: 0;
        }
    }

    @media (min-width: 769px) {
        .bottom-menu {
            justify-content: space-evenly;
        }
    }
</style>
