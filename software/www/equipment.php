<?php
require_once('init.php');
check_auth();

if (isset($_POST['add_equipment'])) {
    $sql = "INSERT INTO equipment (eqpt_name, eqpt_type) VALUES (:name, :type)";
    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':type' => $_POST['type']
    ));
    ora_query("COMMIT");
    header("Location: equipment.php");
    exit;
}

if (isset($_GET['delete'])) {
    $sql = "DELETE FROM equipment WHERE eqpt_id = :id";
    ora_query($sql, array(':id' => (int) $_GET['delete']));
    ora_query("COMMIT");
    header("Location: equipment.php");
    exit;
}

$sql = "SELECT eqpt_id, eqpt_name, eqpt_type FROM equipment";
$stid = ora_query($sql);
$equipment = ora_fetch_all($stid);
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
        <?php foreach ($equipment as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['EQPT_ID']); ?></td>
                <td><?php echo htmlspecialchars($item['EQPT_NAME']); ?></td>
                <td><?php echo htmlspecialchars($item['EQPT_TYPE']); ?></td>
                <td>
                    <a href="?delete=<?php echo $item['EQPT_ID']; ?>"
                        onclick="return confirm('Удалить оборудование?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Добавить оборудование</h2>
    <form method="post">
        Название: <input type="text" name="name"><br>
        Тип:
        <select name="type">
            <option value="Основное">Основное</option>
            <option value="Вспомогательное">Вспомогательное</option>
            <option value="Контрольное">Контрольное</option>
        </select><br>
        <input type="submit" name="add_equipment" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © <?php echo date('Y'); ?>
    </div>
</body>

</html>