<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
$oracle_connection = ora_connect();
// Добавление 
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_material'])) {
    $str = "INSERT INTO materials (mat_quant, mat_type, mat_name, mat_date) 
            VALUES (:mat_quant, :mat_type, :name, TO_DATE(:mat_date, 'DD-MM-YY'))";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":mat_quant", $_POST['mat_quant'], -1);
    oci_bind_by_name($sql, ":mat_type", $_POST['mat_type'], -1);
    oci_bind_by_name($sql, ":mat_date", $_POST['mat_date'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление 
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM materials WHERE mat_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение списка компонентов
$str = "SELECT mat_id, mat_name, mat_type, mat_quant, 
               TO_CHAR(mat_date, 'DD.MM.YY') as mat_date 
        FROM materials
	ORDER BY mat_id";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Материалы</title>
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

    <h2>Список материалов</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php while ($material = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($material['MAT_ID']); ?></td>
                <td><?php echo htmlspecialchars($material['MAT_NAME']); ?></td>
                <td><?php echo htmlspecialchars($material['MAT_TYPE']); ?></td>
                <td><?php echo htmlspecialchars($material['MAT_QUANT']); ?></td>
                <td><?php echo htmlspecialchars($material['MAT_DATE']); ?></td>
                <td>
                    <a href="?delete=<?php echo $material['MAT_ID']; ?>"
                        onclick="return confirm('Удалить материал?')">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Добавить материал</h2>
    <form method="post">
        <div class="form-group">
            <label>Название: <input type="text" name="name" required></label>
        </div>
        <div class="form-group">
            <label>Количество: <input type="number" name="mat_quant" required></label>
        </div>
        <div class="form-group">
            <label>Тип:
                <select name="mat_type">
                    <option value="Solder">Solder</option>
                    <option value="Flux">Flux</option>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Дата:
                <input type="text" name="mat_date" required placeholder="ДД.ММ.ГГ" pattern="\d{2}\.\d{2}\.\d{2}"
                    title="Введите дату в формате ДД.ММ.ГГ">
            </label>
        </div>
        <input type="submit" name="add_material" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>