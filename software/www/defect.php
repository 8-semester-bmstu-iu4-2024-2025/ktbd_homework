<?php
require_once('init.php');
check_auth();

// Добавление дефекта
if (isset($_POST['add_defect'])) {
    $sql = "INSERT INTO defect (dfct_name, dfct_type, dfct_desc, dfct_date) 
            VALUES (:name, :dfct_type, :df_desc, TO_DATE(:d_date, 'DD.MM.YY'))";

    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':dfct_type' => $_POST['dfct_type'],
        ':df_desc' => $_POST['description'],
        ':d_date' => $_POST['d_date']
    ));
	ora_query("COMMIT");
	header("Location: defect.php");
	exit;
}

// Удаление дефекта
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM defect WHERE dfct_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
	ora_query("COMMIT");
	header("Location: defect.php");
	exit;
}

// Получение списка дефектов
$sql = "SELECT dfct_id, dfct_name, dfct_type, dfct_desc, 
               TO_CHAR(dfct_date, 'DD.MM.YY') as dfct_date 
        FROM defect
	ORDER BY dfct_id";
$stid = ora_query($sql);
$defects = ora_fetch_all($stid);
?>

<html>
<head>
    <title>Дефекты</title>
    <style type="text/css">
        body { font-family: Arial; margin 20px; }
		.form-group {margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php require('index.php'); ?>
    
    <h2>Список дефектов</h2>
    <table class = "data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Описание</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($defects as $defect): ?>
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
        <?php endforeach; ?>
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
            <input type="text" name="d_date" required
                   placeholder="ДД.ММ.ГГ"
                   pattern="\d{2}\.\d{2}\.\d{2}"
                   title="Введите дату в формате ДД.ММ.ГГ">
        </label>
    </div>
        <input type="submit" name="add_defect" value="Добавить">
    </form>
	<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>