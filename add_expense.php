<?php
session_start();
require 'database.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Insert into the database
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, date) VALUES (:user_id, :description, :amount, :date)");
    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'description' => $description,
        'amount' => $amount,
        'date' => $date
    ]);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Add Expense</h2>
    <form method="POST">
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <input type="date" name="date" required>
        <button type="submit">Add</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
