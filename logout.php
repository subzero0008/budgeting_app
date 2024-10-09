<?php
session_start();
session_destroy(); // Унищожава сесията
header('Location: login.php'); // Пренасочва към страницата за вход
exit;
?>
