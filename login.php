<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear the error message after displaying it
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #4caf50, #81c784);
            overflow: hidden;
        }

        .container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
            padding: 30px;
            position: relative;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            margin: 0 0 20px;
            color: #4caf50;
            font-size: 24px;
        }

        .input-field {
            width: 100%;
            margin: 10px 0;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            box-sizing: border-box;
            transition: 0.3s;
        }

        .input-field:focus {
            border-color: #4caf50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .button {
            background: #4caf50;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .button:hover {
            background: #43a047;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="authenticate.php" method="POST">
            <input type="email" name="email" class="input-field" placeholder="Email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
        </form>
        <div class="footer">
            © 2024 Driving School Booking System
        </div>
    </div>
</body>
</html>
