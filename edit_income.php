<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $description = trim($_POST['description']);
    $amount = trim($_POST['amount']);
    $date = trim($_POST['date']);

    // Отпечатване на данните от формата за проверка
    echo "<pre>";
    print_r($_POST);  // Това ще покаже всички данни, които се изпращат от формата
    echo "</pre>";

    if ($description && $amount && $date) {
        try {
            // Актуализиране на записа в базата данни
            $stmt = $pdo->prepare("UPDATE incomes SET description = ?, amount = ?, date = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$description, $amount, $date, $id, $_SESSION['user_id']]);

            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            echo "Error editing income: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all fields.";
    }
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM incomes WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$income = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Income</title>
</head>
<body>
    <h2>Edit Income</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $income['id']; ?>">
        <input type="text" name="description" value="<?php echo $income['description']; ?>" required>
        <input type="text" name="amount" value="<?php echo $income['amount']; ?>" required>
        <input type="date" name="date" value="<?php echo $income['date']; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
