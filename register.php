<?php
require 'db.php'; // Include your database connection file
session_start();

// Initialize variables
$username = '';
$password = '';
$email = '';
$errors = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate input
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check if the username or email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->execute(['username' => $username, 'email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['username'] === $username) {
                $errors[] = 'Username already exists.';
            }
            if ($user['email'] === $email) {
                $errors[] = 'Email already exists.';
            }
        }
    }

    // If no errors, proceed to insert the user into the database
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $stmt = $pdo->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
        $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'email' => $email]);

        // Redirect or display success message
        $_SESSION['success'] = 'Registration successful! You can now log in.';
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Form container styling */
        .form-box {
            background-color: rgba(255, 255, 255, 0.9); /* Light transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            max-width: 400px;
            margin: auto;
            border: 2px solid #3498db; /* Border around the form */
        }

        /* Input fields styling */
        input[type="text"],
        input[type="password"],
        input[type="email"] {
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
        h1 {
            margin-bottom: 20px;
            font-size: 2em;
            color: black; /* Променен цвят на заглавието Register на черен */
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
            <h1>Register</h1>

            <!-- Error Display -->
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <button type="submit">Register</button>
            </form>

            <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.php">Log in here</a>.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Yulian Yuriev</p>
    </footer>

</body>
</html>
