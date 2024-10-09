<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget App</title>
    <style>
        /* Body and Background */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a8c0ff, #3f2b96);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Welcome Page Styles */
        .welcome-container {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center vertically */
            align-items: center; /* Center horizontally */
            height: 100vh; /* Full viewport height */
            text-align: center; /* Center text */
        }

        .welcome-title {
            font-size: 3em; /* Larger font size for the welcome message */
            color: #ffffff; /* White color for better contrast */
            margin-bottom: 20px; /* Space below the title */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Optional: shadow for better readability */
        }

        .button-container {
            display: flex; /* Use flexbox for button alignment */
            justify-content: center; /* Center buttons */
            gap: 15px; /* Space between buttons */
        }

        .btn {
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            padding: 15px 30px; /* More padding for larger buttons */
            border: none;
            border-radius: 5px;
            text-decoration: none; /* Remove underline from links */
            font-size: 1.2em; /* Slightly larger text */
            transition: background-color 0.3s; /* Smooth transition */
        }

        .btn:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        footer {
            position: fixed; /* Keep it at the bottom */
            bottom: 0; /* Maximum down */
            right: 0; /* Maximum right */
            padding: 10px; /* Padding if needed */
            background-color: transparent; /* Transparent background */
        }

        footer p {
            color: #FAF3E0; /* Light cream color */
            margin: 0; /* Remove margins */
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1 class="welcome-title">Welcome to the Budgeting App!</h1>
        <div class="button-container">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Login</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Yulian Yuriev</p>
    </footer>
</body>
</html>
