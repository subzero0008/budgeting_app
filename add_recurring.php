<?php
session_start();
require 'db.php'; // Including the database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are set and not empty
    if (isset($_POST['type'], $_POST['description'], $_POST['amount'], $_POST['frequency'], $_POST['start_date'])) {
        
        $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
        $type = $_POST['type']; // Income or Expense
        $description = $_POST['description']; // Description of the income/expense
        $amount = $_POST['amount']; // Amount of the income/expense
        $frequency = $_POST['frequency']; // Frequency (daily, weekly, monthly, yearly)
        $start_date = $_POST['start_date']; // Start date of the recurring income/expense
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null; // Optional end date

        // Prepare SQL statement to insert the recurring income/expense
        $stmt = $pdo->prepare("INSERT INTO recurring (user_id, type, description, amount, frequency, start_date, end_date) 
                               VALUES (:user_id, :type, :description, :amount, :frequency, :start_date, :end_date)");
        // Execute the query with parameters
        $stmt->execute([
            'user_id' => $user_id,
            'type' => $type,
            'description' => $description,
            'amount' => $amount,
            'frequency' => $frequency,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        // Provide feedback to the user
        echo "Recurring income/expense added successfully!";
    } else {
        echo "All fields except 'end date' are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recurring Income/Expense</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS for styling -->
</head>
<body>
    <h2>Add Recurring Income or Expense</h2>
    
    <!-- Form for adding recurring income or expense -->
    <form action="add_recurring.php" method="POST">
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <br>

        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required>
        <br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01" required>
        <br>

        <label for="frequency">Frequency:</label>
        <select name="frequency" id="frequency" required>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
        <br>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>
        <br>

        <label for="end_date">End Date (Optional):</label>
        <input type="date" name="end_date" id="end_date">
        <br>

        <input type="submit" value="Add Recurring">
    </form>

    <!-- Link to go back to the dashboard -->
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
