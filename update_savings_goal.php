<?php
// update_savings_goal.php

session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $goal_id = $_POST['goal_id'];
    $amount_saved = $_POST['amount_saved'];

    // Update the amount saved for the goal
    $stmt = $pdo->prepare("UPDATE savings_goals SET amount_saved = amount_saved + :amount_saved WHERE id = :goal_id AND user_id = :user_id");
    $stmt->execute([
        'amount_saved' => $amount_saved,
        'goal_id' => $goal_id,
        'user_id' => $_SESSION['user_id']
    ]);

    header('Location: dashboard.php');
}
?>
