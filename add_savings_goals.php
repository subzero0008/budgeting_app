<?php
session_start();
require 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User ID is not set in the session.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize inputs
    $goal_name = trim($_POST['goal_name']);
    $target_amount = trim($_POST['target_amount']);
    $due_date = trim($_POST['due_date']);
    $user_id = $_SESSION['user_id'];

    // Ensure all inputs are provided
    if (empty($goal_name) || empty($target_amount) || empty($due_date)) {
        echo "Please fill in all required fields.";
    } else {
        // Ensure the target amount is a valid positive number
        if (!is_numeric($target_amount) || $target_amount <= 0) {
            echo "Please enter a valid target amount.";
        } else {
            // Format target amount
            $target_amount = number_format((float)$target_amount, 2, '.', '');
            
            try {
                // Insert the savings goal into the database
                $stmt = $pdo->prepare("INSERT INTO savings_goals (user_id, goal_name, target_amount, due_date) 
                                       VALUES (:user_id, :goal_name, :target_amount, :due_date)");
                $result = $stmt->execute([
                    'user_id' => $user_id,
                    'goal_name' => $goal_name,
                    'target_amount' => $target_amount,
                    'due_date' => $due_date
                ]);

                if ($result) {
                    echo "Savings goal successfully added!";
                    header('Location: dashboard.php');
                    exit;
                } else {
                    echo "Error: Unable to add savings goal.";
                    var_dump($stmt->errorInfo());
                }
            } catch (PDOException $e) {
                echo "Error adding savings goal: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Savings Goal</title>
</head>
<body>
    <h2>Add Savings Goal</h2>
    <form method="POST" action="add_savings_goal.php">
        <label for="goal_name">Goal Name:</label>
        <input type="text" name="goal_name" id="goal_name" required><br>

        <label for="target_amount">Target Amount:</label>
        <input type="number" name="target_amount" id="target_amount" step="0.01" required><br>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" id="due_date" required><br>

        <button type="submit">Add Goal</button>
    </form>
</body>
</html>
