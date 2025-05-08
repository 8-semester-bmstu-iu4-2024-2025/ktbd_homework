<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
$oracle_connection = ora_connect();
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_equipment'])) {
    $str = "INSERT INTO equipment (eqpt_name, eqpt_type) VALUES (:name, :type)";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":type", $_POST['type'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM equipment WHERE eqpt_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

$str = "SELECT eqpt_id, eqpt_name, eqpt_type FROM equipment";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Оборудование</title>
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

    <h2>Список оборудования</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Действия</th>
        </tr>
        <?php while ($item = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($item['EQPT_ID']); ?></td>
                <td><?php echo htmlspecialchars($item['EQPT_NAME']); ?></td>
                <td><?php echo htmlspecialchars($item['EQPT_TYPE']); ?></td>
                <td>
                    <a href="?delete=<?php echo $item['EQPT_ID']; ?>"
                        onclick="return confirm('Удалить оборудование?')">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Добавить оборудование</h2>
    <form method="post">
        Название: <input type="text" name="name"><br>
        Тип:
        <select name="type">
            <option value="Main">Основное</option>
            <option value="Help">Вспомогательное</option>
            <option value="Control">Контрольное</option>
        </select><br>
        <input type="submit" name="add_equipment" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>