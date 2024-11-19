<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'drivingschool');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT full_name, role FROM users WHERE id = ?";
$stmt = $db->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driving School Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }
        .flutter-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .menu-overlay {
            background: rgba(0, 0, 0, 0.5);
            display: none;
        }
        .menu-overlay.active {
            display: block;
        }
    </style>
</head>
<body x-data="{ isMenuOpen: false }" class="bg-gray-100">
    <!-- App Bar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-blue-600 text-white flutter-shadow h-16 flex items-center justify-between px-4">
        <!-- Menu Toggle Button -->
        <button 
            @click="isMenuOpen = !isMenuOpen" 
            class="focus:outline-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- App Title -->
        <h1 class="text-lg font-bold">Lekoana Driving School</h1>

        <!-- Placeholder for additional actions -->
        <div class="w-6"></div>
    </header>

    <!-- Side Menu Overlay -->
    <div 
        x-show="isMenuOpen" 
        @click="isMenuOpen = false"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="menu-overlay fixed inset-0 z-40 bg-black bg-opacity-50"
    ></div>

    <!-- Side Menu -->
    <div 
        x-show="isMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform -translate-x-full"
        x-transition:enter-end="transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="transform translate-x-0"
        x-transition:leave-end="transform -translate-x-full"
        class="fixed top-0 left-0 bottom-0 w-64 bg-white z-50 flutter-shadow p-4"
    >
        <!-- User Profile Section -->
        <div class="mb-6 pt-16 border-b pb-4">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="font-bold"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['role']); ?></p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav>
            <ul>
                <li class="mb-2">
                    <a href="dashboard.php" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Home
                    </a>
                </li>
                <li class="mb-2">
                    <a href="calendar.php" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        Calendar
                    </a>
                </li>
                <li class="mb-2">
                    <a href="account.php" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Account
                    </a>
                </li>
                <li class="mb-2">
                    <a href="send_report.php" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                        </svg>
                        Send Report
                    </a>
                </li>
                <li class="mb-2">
                    <a href="truck_status.php" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a.5.5 0 00.5-.5v-7A.5.5 0 0010 8H3a1 1 0 00-1 1v2H1a1 1 0 00-1 1v3a1 1 0 001 1h1a3 3 0 106 0h6a3 3 0 106 0h1a1 1 0 001-1v-3a1 1 0 00-1-1h-1V6a1 1 0 00-1-1H3z" />
                        </svg>
                        Truck Status
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="flex items-center p-3 hover:bg-red-50 rounded-lg text-red-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L14.586 11H7a1 1 0 110-2h7.586l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Content Wrapper -->
    <div class="pt-16">
        <!-- Your page content -->
    </div>

    <!-- Alpine.js initialization for body scrolling -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('menu', () => ({
                isMenuOpen: false,
                toggleMenu() {
                    this.isMenuOpen = !this.isMenuOpen;
                    document.body.classList.toggle('menu-open', this.isMenuOpen);
                }
            }));
        });
    </script>
</body>
</html>
