<?php
// Start session
session_start();

// Database connection details
$host = "localhost";
$dbname = "DrivingSchool";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Query to fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (md5($password) === $user['password']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } elseif ($user['role'] === 'instructor') {
                    header("Location: instructor_dashboard.php");
                }
                exit;
            } else {
                // Incorrect password
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: login.php");
                exit;
            }
        } else {
            // User not found
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit;
        }
    }
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
