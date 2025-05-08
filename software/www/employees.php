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
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_employee'])) {
    $str = "INSERT INTO employees (empl_surn, empl_name, empl_job, empl_pass) 
            VALUES (:surname, :name, :job, :pass)";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":surname", $_POST['surname'], -1);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":job", $_POST['job'], -1);
    oci_bind_by_name($sql, ":pass", $_POST['password'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM employees WHERE empl_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение данных
$str = "SELECT empl_id, empl_surn, empl_name, empl_job FROM employees";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Сотрудники</title>
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

    <h2>Список сотрудников</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Должность</th>
            <?php if (strtolower($_SESSION['empl_job']) == 'admin')
                echo "<th>Действия</th>"; ?>
        </tr>
        <?php while ($emp = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($emp['EMPL_ID']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_SURN']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_NAME']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_JOB']); ?></td>
                <?php if (strtolower($_SESSION['empl_job']) == 'admin'): ?>
                    <td>
                        <a href="?delete=<?php echo $emp['EMPL_ID']; ?>"
                            onclick="return confirm('Удалить сотрудника?')">Удалить</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php } ?>
    </table>

    <?php if (strtolower($_SESSION['empl_job']) == 'admin'): ?>
        <h2>Добавить сотрудника</h2>
        <form method="post">
            Фамилия: <input type="text" name="surname"><br>
            Имя: <input type="text" name="name"><br>
            Должность:
            <select name="job">
                <option value="Admin">Администратор</option>
                <option value="Rab">Работник</option>
            </select><br>
            Пароль: <input type="password" name="password"><br>
            <input type="submit" name="add_employee" value="Добавить">
        </form>
    <?php endif; ?>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>