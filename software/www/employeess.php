<?php
require_once('init.php');
check_auth();

// Добавление
if (isset($_POST['add_employee'])) {
    $sql = "INSERT INTO employeess (empl_surn, empl_name, empl_job, empl_pass) 
            VALUES (:surname, :name, :job, :pass)";
    ora_query($sql, array(
        ':surname' => $_POST['surname'],
        ':name' => $_POST['name'],
        ':job' => $_POST['job'],
        ':pass' => $_POST['password']
    ));
    ora_query("COMMIT");
    header("Location: employeess.php");
    exit;
}

// Удаление
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM employeess WHERE empl_id = :id";
    ora_query($sql, array(':id' => (int) $_GET['delete']));
    ora_query("COMMIT");
    header("Location: employeess.php");
    exit;
}

// Получение данных
$sql = "SELECT empl_id, empl_surn, empl_name, empl_job FROM employeess";
$stid = ora_query($sql);
$employees = ora_fetch_all($stid);
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
            <?php if (strtolower($_SESSION['role']) == 'admin')
                echo "<th>Действия</th>"; ?>
        </tr>
        <?php foreach ($employees as $emp): ?>
            <tr>
                <td><?php echo htmlspecialchars($emp['EMPL_ID']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_SURN']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_NAME']); ?></td>
                <td><?php echo htmlspecialchars($emp['EMPL_JOB']); ?></td>
                <?php if (strtolower($_SESSION['role']) == 'admin'): ?>
                    <td>
                        <a href="?delete=<?php echo $emp['EMPL_ID']; ?>"
                            onclick="return confirm('Удалить сотрудника?')">Удалить</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (strtolower($_SESSION['role']) == 'admin'): ?>
        <h2>Добавить сотрудника</h2>
        <form method="post">
            Фамилия: <input type="text" name="surname"><br>
            Имя: <input type="text" name="name"><br>
            Должность:
            <select name="job">
                <option value="Admin">Администратор</option>
                <option value="Rab">Работник</option>
            </select><br>
            Пароль: <input type="text" name="password"><br>
            <input type="submit" name="add_employee" value="Добавить">
        </form>
    <?php endif; ?>
    <div class="footer-bumper">
        Система управления производством © <?php echo date('Y'); ?>
    </div>
</body>

</html>