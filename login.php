<?php
session_start();
include 'db.php'; // Файл за свързване с базата данни

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка на потребителя в базата данни
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Невалидно потребителско име или парола.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Center the container */
        .container {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            background: linear-gradient(135deg, #a8c0ff, #3f2b96); /* Optional background */
        }

        /* Form container styling */
        .form-box {
            background-color: rgba(255, 255, 255, 0.9); /* Light transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            max-width: 400px;
            width: 100%; /* Responsive width */
            border: 2px solid #3498db; /* Border around the form */
        }

        /* Input fields styling */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 1em;
        }

        /* Form heading styling */
        h2 {
            margin-bottom: 20px;
            font-size: 2em;
            color: black; /* Black color for the heading */
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Centered error messages */
        .error {
            color: red;
            background-color: rgba(255, 0, 0, 0.1);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <h2>Login</h2>
        
        <!-- Error Display -->
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <!-- Register Button -->
        <form action="register.php" method="GET">
            <button type="submit" style="margin-top: 10px; background-color: #2ecc71;">Register</button>
        </form>
    </div>
</div>


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Yulian Yuriev</p>
    </footer>

</body>
</html>
