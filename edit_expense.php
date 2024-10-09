<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $stmt = $pdo->prepare("UPDATE expenses SET description = ?, amount = ?, date = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$description, $amount, $date, $id, $_SESSION['user_id']]);

    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$expense = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
</head>
<body>
    <h2>Edit Expense</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
        <input type="text" name="description" value="<?php echo $expense['description']; ?>" required>
        <input type="text" name="amount" value="<?php echo $expense['amount']; ?>" required>
        <input type="date" name="date" value="<?php echo $expense['date']; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
