<?php 
require 'db.php'; // Include your database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve user incomes
$stmt = $pdo->prepare("SELECT * FROM incomes WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve user expenses
$stmt = $pdo->prepare("SELECT expenses.*, categories.name AS category_name FROM expenses LEFT JOIN categories ON expenses.category_id = categories.id WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve categories for the dropdown
$stmt = $pdo->prepare("SELECT * FROM categories"); // Без условието WHERE
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Handle adding income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_income'])) {
    $description = trim($_POST['income_description']);
    $amount = trim($_POST['income_amount']);
    $date = trim($_POST['income_date']);
    $goalId = isset($_POST['saving_goal_id']) ? $_POST['saving_goal_id'] : null; // ID на избраната спестовна цел

    if ($description && $amount && $date) {
        try {
            // Вмъкване на дохода
            $stmt = $pdo->prepare('INSERT INTO incomes (user_id, description, amount, date) VALUES (:user_id, :description, :amount, :date)');
            $stmt->execute(['user_id' => $_SESSION['user_id'], 'description' => $description, 'amount' => $amount, 'date' => $date]);

            // Актуализиране на amount_saved за избраната спестовна цел
            if ($goalId) {
                $stmt = $pdo->prepare('UPDATE savings_goals SET amount_saved = amount_saved + :amount WHERE id = :goal_id AND user_id = :user_id');
                $stmt->execute(['amount' => $amount, 'goal_id' => $goalId, 'user_id' => $_SESSION['user_id']]);
            }
            
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error adding income: " . $e->getMessage();
        }
    }
}



// Handle adding expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $description = trim($_POST['expense_description']);
    $amount = trim($_POST['expense_amount']);
    $date = trim($_POST['expense_date']);
    $categoryId = $_POST['category_id'];

    if ($description && $amount && $date) {
        try {
            // Insert the expense
            $stmt = $pdo->prepare('INSERT INTO expenses (user_id, description, amount, date, category_id) VALUES (:user_id, :description, :amount, :date, :category_id)');
            $stmt->execute(['user_id' => $_SESSION['user_id'], 'description' => $description, 'amount' => $amount, 'date' => $date, 'category_id' => $categoryId]);

            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error adding expense: " . $e->getMessage();
        }
    }
}

// Handle deleting income
if (isset($_GET['delete_income'])) {
    $incomeId = $_GET['delete_income'];
    $stmt = $pdo->prepare('DELETE FROM incomes WHERE id = :id AND user_id = :user_id');
    $stmt->execute(['id' => $incomeId, 'user_id' => $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit;
}

// Handle deleting expense
if (isset($_GET['delete_expense'])) {
    $expenseId = $_GET['delete_expense'];
    $stmt = $pdo->prepare('DELETE FROM expenses WHERE id = :id AND user_id = :user_id');
    $stmt->execute(['id' => $expenseId, 'user_id' => $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit;
}

// Handle adding and managing saving goals
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_goal'])) {
    $goalName = trim($_POST['goal_name']);
    $targetAmount = trim($_POST['target_amount']);
    $dueDate = trim($_POST['due_date']);

    if ($goalName && $targetAmount && $dueDate) {
        try {
            $stmt = $pdo->prepare('INSERT INTO savings_goals (user_id, goal_name, target_amount, due_date) VALUES (:user_id, :goal_name, :target_amount, :due_date)');
            $stmt->execute(['user_id' => $_SESSION['user_id'], 'goal_name' => $goalName, 'target_amount' => $targetAmount, 'due_date' => $dueDate]);

            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error adding goal: " . $e->getMessage();
        }
    }
}

// Handle editing income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_income'])) {
    $incomeId = $_POST['income_id'];
    $description = trim($_POST['income_description']);
    $amount = trim($_POST['income_amount']);
    $date = trim($_POST['income_date']);
    
    if ($description && $amount && $date) {
        try {
            $stmt = $pdo->prepare('UPDATE incomes SET description = :description, amount = :amount, date = :date WHERE id = :income_id AND user_id = :user_id');
            $stmt->execute(['description' => $description, 'amount' => $amount, 'date' => $date, 'income_id' => $incomeId, 'user_id' => $_SESSION['user_id']]);

            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error editing income: " . $e->getMessage();
        }
    }
}

// Handle editing expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_expense'])) {
    $expenseId = $_POST['expense_id'];
    $description = trim($_POST['expense_description']);
    $amount = trim($_POST['expense_amount']);
    $date = trim($_POST['expense_date']);
    $categoryId = $_POST['category_id'];

    if ($description && $amount && $date) {
        try {
            $stmt = $pdo->prepare('UPDATE expenses SET description = :description, amount = :amount, date = :date, category_id = :category_id WHERE id = :expense_id AND user_id = :user_id');
            $stmt->execute(['description' => $description, 'amount' => $amount, 'date' => $date, 'category_id' => $categoryId, 'expense_id' => $expenseId, 'user_id' => $_SESSION['user_id']]);

            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error editing expense: " . $e->getMessage();
        }
    }
}

// Handle editing a saving goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_goal'])) {
    $goalId = $_POST['goal_id'];
    $goalName = trim($_POST['goal_description']); // Updated
    $targetAmount = trim($_POST['goal_target']); // Updated
    $dueDate = trim($_POST['goal_deadline']); // Updated

    if ($goalName && $targetAmount && $dueDate) {
        try {
            $stmt = $pdo->prepare('UPDATE savings_goals SET goal_name = :goal_name, target_amount = :target_amount, due_date = :due_date WHERE id = :goal_id AND user_id = :user_id');
            $stmt->execute(['goal_name' => $goalName, 'target_amount' => $targetAmount, 'due_date' => $dueDate, 'goal_id' => $goalId, 'user_id' => $_SESSION['user_id']]);

            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error editing goal: " . $e->getMessage();
        }
    }
}


// Handle deleting a saving goal
if (isset($_GET['delete_goal'])) {
    $goalId = $_GET['delete_goal'];
    $stmt = $pdo->prepare('DELETE FROM savings_goals WHERE id = :id AND user_id = :user_id');
    $stmt->execute(['id' => $goalId, 'user_id' => $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit;
}

// Calculate total income and total expenses
$totalIncome = array_sum(array_column($incomes, 'amount'));
$totalExpenses = array_sum(array_column($expenses, 'amount'));

// Calculate balance
$balance = $totalIncome - $totalExpenses;

// Retrieve user savings goals
$query = "SELECT * FROM savings_goals WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $_SESSION['user_id']]); // Use $_SESSION['user_id'] instead of $userId
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize savings progress
$savingsProgress = [];
if ($goals) {
    foreach ($goals as $goal) {
        // Calculate progress based on balance and target amount
        $progress = $goal['target_amount'] > 0 ? ($balance / $goal['target_amount']) * 100 : 0;
        $savingsProgress[$goal['id']] = round($progress, 2); // Round to 2 decimal places
    }
}


// Fetch saving goals for selection in income form
$stmt = $pdo->prepare("SELECT * FROM savings_goals WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$savingGoalsForSelection = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a8c0ff, #3f2b96);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h2, h3, h4 {
            margin: 0 0 10px;
        }

        p, span {
            margin: 5px 0;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .record {
            margin-bottom: 10px;
        }

        .dashboard-title {
            text-align: center;
            margin: 20px 0;
            font-size: 45px; /* Increased size */
            font-weight: bold;
            color: #FAF3E0; /* Light cream */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Text shadow */
        }

        /* Dashboard Container */
        .dashboard-container {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }

        /* Form Container for Incomes & Expenses */
        .form-container {
            flex: 0 0 65%; /* Make it wider */
            margin-right: 20px; /* Space between Incomes/Expenses and Saving Goals */
        }

        .income-expense-forms {
            display: flex;
            justify-content: space-between; /* Horizontally align Incomes & Expenses */
        }

        .income-form, .expense-form {
            width: 48%; /* Make each form take half of the container */
            padding: 15px;
            background: #e8f0fe; /* Light blue for window */
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Saving Goals, Summary, Goal Progress */
        .summary-goals-container {
            flex: 0 0 30%; /* Narrower */
            display: flex;
            flex-direction: column;
        }

        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #e8f0fe; /* Light blue for window */
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .summary, .goal-progress {
            margin-top: 20px;
            padding: 20px;
            background: #e8f0fe; /* Light blue for window */
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Button Styles */
        .btn {
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px; /* Spacing above buttons */
        }

        .btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Logout Button */
        .logout-button {
            display: block;
            padding: 10px;
            background-color: #e74c3c; /* Red background */
            color: white;
            text-align: center;
            border-radius: 5px;
            width: 200px;
            text-decoration: none;
            position: fixed; /* Fixed position */
            bottom: 10px; /* 10px from the bottom */
            left: 10px; /* 10px from the left */
        }

        footer {
            position: fixed; /* Position at the bottom of the page */
            bottom: 0; /* Max down */
            right: 0; /* Max right */
            padding: 10px; /* Padding, if you want */
            background-color: transparent; /* Transparent background */
        }

        footer p {
            color: #FAF3E0; /* Shade of white (light cream color) */
            margin: 0; /* Remove margins */
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: white;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2 class="dashboard-title">Dashboard</h2>

    <div class="dashboard-container">
        <div class="form-container">
            <div class="form-section">
                <h3>Your Incomes & Expenses:</h3>

                <div class="income-expense-forms">
                    <div class="income-form">
                        <h4>Add Income</h4>
                        <form method="POST">
                            <input type="text" name="income_description" placeholder="Description" required>
                            <input type="number" name="income_amount" placeholder="Amount" required>
                            <input type="date" name="income_date" required>
                            <button type="submit" name="add_income" class="btn">Add Income</button>
                        </form>
                    </div>

                    <div class="expense-form">
                        <h4>Add Expense</h4>
                        <form method="POST">
                            <input type="text" name="expense_description" placeholder="Description" required>
                            <input type="number" name="expense_amount" placeholder="Amount" required>
                            <input type="date" name="expense_date" required>
                            <select name="category_id" required>
                                <?php if (!empty($categories) && is_array($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <button type="submit" name="add_expense" class="btn">Add Expense</button>
                        </form>
                    </div>
                </div>

                <!-- Display Incomes -->
                <?php if (!empty($incomes) && is_array($incomes)): ?>
                    <?php foreach ($incomes as $income): ?>
                        <div class="record">
                            <span><?= htmlspecialchars($income['description']) ?>: <?= htmlspecialchars($income['amount']) ?> on <?= htmlspecialchars($income['date']) ?></span>
                            <a href="#" onclick="openEditIncome(<?= $income['id'] ?>, '<?= htmlspecialchars($income['description']) ?>', <?= $income['amount'] ?>, '<?= $income['date'] ?>')">Edit</a>
                            <a href="?delete_income=<?= $income['id'] ?>">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Display Expenses -->
                <?php if (!empty($expenses) && is_array($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                        <div class="record">
                            <span><?= htmlspecialchars($expense['description']) ?>: <?= htmlspecialchars($expense['amount']) ?> on <?= htmlspecialchars($expense['date']) ?> (<?= htmlspecialchars($expense['category_name']) ?>)</span>
                            <a href="#" onclick="openEditExpense(<?= $expense['id'] ?>, '<?= htmlspecialchars($expense['description']) ?>', <?= $expense['amount'] ?>, '<?= $expense['date'] ?>')">Edit</a>
                            <a href="?delete_expense=<?= $expense['id'] ?>">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="summary">
                <h3>Summary:</h3>
                <p>Total Income: <?= htmlspecialchars($totalIncome) ?></p>
                <p>Total Expense: <?= htmlspecialchars($totalExpenses) ?></p>
                <p>Balance: <?= htmlspecialchars($balance) ?></p>
            </div>
        </div>

        <div class="summary-goals-container">
            <div class="form-section">
                <h3>Saving Goals:</h3>
                <form method="POST">
                    <input type="text" name="goal_name" placeholder="Goal Name" required>
                    <input type="number" name="target_amount" placeholder="Target Amount" required>
                    <input type="date" name="due_date" required>
                    <button type="submit" name="add_goal" class="btn">Add Goal</button>
                </form>

                <?php if (!empty($goals) && is_array($goals)): ?>
                    <?php foreach ($goals as $goal): ?>
                        <div class="record">
                            <span><?= htmlspecialchars($goal['goal_name']) ?>: <?= htmlspecialchars($goal['amount_saved']) ?> / <?= htmlspecialchars($goal['target_amount']) ?> (<?= htmlspecialchars($goal['due_date']) ?>)</span>
                            <a href="#" onclick="openEditGoal(<?= $goal['id'] ?>, '<?= htmlspecialchars($goal['goal_name']) ?>', <?= $goal['target_amount'] ?>, <?= $goal['amount_saved'] ?>, '<?= $goal['due_date'] ?>')">Edit</a>
                            <a href="?delete_goal=<?= $goal['id'] ?>">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="goal-progress">
            <h3>Goal Progress:</h3>
<?php if (!empty($goals) && is_array($goals)): ?>
    <?php foreach ($goals as $goal): ?>
        <?php
        // Calculate progress based on balance and target amount
        $progress = $goal['target_amount'] > 0 ? ($balance / $goal['target_amount']) * 100 : 0;
        ?>
        <p>
            <?= htmlspecialchars($goal['goal_name']) ?>: 
            <?= htmlspecialchars($balance) ?> / <?= htmlspecialchars($goal['target_amount']) ?> 
            (<?= number_format(round($progress, 2), 2) ?>%)
        </p>
    <?php endforeach; ?>
<?php else: ?>
    <p>No goals found.</p>
<?php endif; ?>



            </div>
        </div>
    </div>

    <a href="logout.php" class="logout-button">Logout</a>

    <footer>
        <p>&copy; Yulian Yuriev 2024</p>
    </footer>

    < <div id="editIncomeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditIncome()">&times;</span>
            <h3>Edit Income</h3>
            <form method="POST">
                <input type="hidden" name="income_id" id="income_id">
                <input type="text" name="income_description" id="edit_income_description" required>
                <input type="number" name="income_amount" id="edit_income_amount" required>
                <input type="date" name="income_date" id="edit_income_date" required>
                <button type="submit" name="edit_income" class="btn">Update Income</button>
            </form>
        </div>
    </div>

    <div id="editExpenseModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditExpense()">&times;</span>
            <h3>Edit Expense</h3>
            <form method="POST">
                <input type="hidden" name="expense_id" id="expense_id">
                <input type="text" name="expense_description" id="edit_expense_description" required>
                <input type="number" name="expense_amount" id="edit_expense_amount" required>
                <input type="date" name="expense_date" id="edit_expense_date" required>
                <select name="category_id" id="edit_category_id" required>
                    <?php if (!empty($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" name="edit_expense" class="btn">Update Expense</button>
            </form>
        </div>
    </div>

    <div id="editGoalModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditGoal()">&times;</span>
            <h3>Edit Saving Goal</h3>
            <form method="POST">
                <input type="hidden" name="goal_id" id="goal_id">
                <input type="text" name="goal_description" id="edit_goal_description" required>
                <input type="number" name="goal_target" id="edit_goal_target" required>
                <input type="date" name="goal_deadline" id="edit_goal_deadline" required>
                <button type="submit" name="edit_goal" class="btn">Update Goal</button>
            </form>
        </div>
    </div>


    <script>
        function openEditIncome(id, description, amount, date) {
            document.getElementById('income_id').value = id;
            document.getElementById('edit_income_description').value = description;
            document.getElementById('edit_income_amount').value = amount;
            document.getElementById('edit_income_date').value = date;
            document.getElementById('editIncomeModal').style.display = "block";
        }

        function closeEditIncome() {
            document.getElementById('editIncomeModal').style.display = "none";
        }

        function openEditExpense(id, description, amount, date) {
            document.getElementById('expense_id').value = id;
            document.getElementById('edit_expense_description').value = description;
            document.getElementById('edit_expense_amount').value = amount;
            document.getElementById('edit_expense_date').value = date;
            document.getElementById('editExpenseModal').style.display = "block";
        }

        function closeEditExpense() {
            document.getElementById('editExpenseModal').style.display = "none";
        }

        function openEditGoal(id, description, target, deadline) {
            document.getElementById('goal_id').value = id;
            document.getElementById('edit_goal_description').value = description;
            document.getElementById('edit_goal_target').value = target;
            document.getElementById('edit_goal_deadline').value = deadline;
            document.getElementById('editGoalModal').style.display = "block";
        }

        function closeEditGoal() {
            document.getElementById('editGoalModal').style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeEditIncome();
                closeEditExpense();
                closeEditGoal();
            }
        }
    </script>
</body>
</html>