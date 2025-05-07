<?php
// init.php - единственный файл, где стартует сессия
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!session_id()) {
    session_start();
}

require_once('oracle.php'); // Подключение БД

// Проверка авторизации
function check_auth()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

?>