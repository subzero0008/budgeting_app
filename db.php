<?php
$host = 'fdb1029.awardspace.net'; // Хост на сървъра за базата данни
$db = '4536422_budgetapp'; // Името на новата база данни
$user = '4536422_budgetapp'; // Потребителското име за MySQL
$pass = 'Shield123'; // Паролата за новата база данни

try {
    // Създаване на нова PDO връзка към базата данни
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Настройка на PDO за хвърляне на изключения при грешка
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Успешно съобщение за връзка (Премахнато, за да няма изход)
} catch (PDOException $e) {
    // Извеждане на съобщение при грешка
    echo "Connection failed: " . $e->getMessage();
    exit(); // Добавено, за да се прекрати скриптът при неуспешна връзка
}
?>
