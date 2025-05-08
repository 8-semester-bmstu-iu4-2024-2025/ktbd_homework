<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
$oracle_connection = ora_connect();
// Добавление дефекта
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_defect'])) {
    $str = "INSERT INTO defects (dfct_name, dfct_type, dfct_desc, dfct_date) 
            VALUES (:name, :dfct_type, :df_desc, TO_DATE(:d_date, 'DD.MM.YY'))";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":dfct_type", $_POST['dfct_type'], -1);
    oci_bind_by_name($sql, ":df_desc", $_POST['description'], -1);
    oci_bind_by_name($sql, ":d_date", $_POST['d_date'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление дефекта
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM defects WHERE dfct_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение списка дефектов
$str = "SELECT dfct_id, dfct_name, dfct_type, dfct_desc, 
               TO_CHAR(dfct_date, 'DD.MM.YY') as dfct_date 
        FROM defects
	ORDER BY dfct_id";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Дефекты</title>
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

    <h2>Список дефектов</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Описание</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php while ($defect = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($defect['DFCT_ID']); ?></td>
                <td><?php echo htmlspecialchars($defect['DFCT_NAME']); ?></td>
                <td><?php echo htmlspecialchars($defect['DFCT_TYPE']); ?></td>
                <td><?php echo htmlspecialchars($defect['DFCT_DESC']); ?></td>
                <td><?php echo htmlspecialchars($defect['DFCT_DATE']); ?></td>
                <td>
                    <a href="?delete=<?php echo $defect['DFCT_ID']; ?>"
                        onclick="return confirm('Удалить дефект?')">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Добавить дефект</h2>
    <form method="post">
        <div class="form-group">
            <label>Название: <input type="text" name="name" required></label>
        </div>
        <div class="form-group">
            <label>Тип:
                <select name="dfct_type" required>
                    <option value="Def1">Def1</option>
                    <option value="Def2">Def2</option>
                    <option value="Def3">Def3</option>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Описание: <input type="text" name="description"></label>
        </div>
        <div class="form-group">
            <label>Дата:
                <input type="text" name="d_date" required placeholder="ДД.ММ.ГГ" pattern="\d{2}\.\d{2}\.\d{2}"
                    title="Введите дату в формате ДД.ММ.ГГ">
            </label>
        </div>
        <input type="submit" name="add_defect" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>