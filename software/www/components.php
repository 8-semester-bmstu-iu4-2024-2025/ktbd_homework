<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
$oracle_connection = ora_connect();
// Добавление компонента
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_component'])) {
    $str = "INSERT INTO components (comp_name, comp_type, comp_date) 
            VALUES (:name, :co_type, TO_DATE(:co_date, 'DD-MM-YY'))";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":co_type", $_POST['co_type'], -1);
    oci_bind_by_name($sql, ":co_date", $_POST['co_date'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление компонента
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM components WHERE comp_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение списка компонентов
$str = "SELECT comp_id, comp_name, comp_type, 
               TO_CHAR(comp_date, 'DD.MM.YY') as comp_date 
        FROM components
	ORDER BY comp_id";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Компоненты</title>
    <style type="text/css">
        body {
            font-family: Arial;
            margin 20px;
        }

        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php require('index.php'); ?>

    <h2>Список компонентов</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php
        while ($component = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($component['COMP_ID']); ?></td>
                <td><?php echo htmlspecialchars($component['COMP_NAME']); ?></td>
                <td><?php echo htmlspecialchars($component['COMP_TYPE']); ?></td>
                <td><?php echo htmlspecialchars($component['COMP_DATE']); ?></td>
                <td>
                    <a href="?delete=<?php echo $component['COMP_ID']; ?>"
                        onclick="return confirm('Удалить компонент?')">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Добавить компонент</h2>
    <form method="post">
        <div class="form-group">
            <label>Название: <input type="text" name="name" required></label>
        </div>
        <div class="form-group">
            <label>Тип:
                <select name="co_type">
                    <option value="Resistor">Resistor</option>
                    <option value="Capasitor">Capasitor</option>
                    <option value="Transistor">Transistor</option>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Дата:
                <input type="text" name="co_date" required placeholder="ДД.ММ.ГГ" pattern="\d{2}\.\d{2}\.\d{2}"
                    title="Введите дату в формате ДД.ММ.ГГ">
            </label>
        </div>
        <input type="submit" name="add_component" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>