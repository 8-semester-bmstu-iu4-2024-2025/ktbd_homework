<?php

require_once('init.php');
check_auth();



function display_navigation() {
    echo '<div style="background:#eee; padding:10px; margin-bottom:20px;">';
    echo '<a href="products.php">Продукция</a> | ';
    echo '<a href="employeess.php">Сотрудники</a> | ';
    echo '<a href="equipment.php">Оборудование</a> | ';
    echo '<a href="defect.php">Дефекты</a> | ';
    echo '<a href="components.php">Компоненты</a> | ';
    echo '<a href="rejections.php">Брак</a> | ';
    echo '<a href="documents.php">Документы</a> | ';
    echo '<a href="report.php">Отчет</a> | ';
    echo '<a href="logout.php">Выход</a>';
    echo '</div>';
}

display_navigation();

if (strtolower($_SESSION['role']) == 'admin') {
    echo "<h1>Панель администратора</h1>";
} 
else {
    echo "<h1>Панель работника</h1>";
}
?>
<html>
<head>

<style type="text/css">
    .footer-bumper {
        border-top: 2px solid #4285f4;
        margin-top: 20px;
        padding-top: 10px;
        color: #666;
        text-align: center;
        font-size: 12px;
    }
    /* Стили для чередования строк таблиц */
    .data-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    .data-table th, .data-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .data-table th {
        background-color: #f2f2f2;
    }
    .data-table tr:nth-child(even) {
        background-color: #add8e6;
    }
    .data-table tr:nth-child(odd) {
        background-color: #ffffff;
    }
</style>
</head>
<body>
<div class="footer-bumper">
</div>
</body>
</html>