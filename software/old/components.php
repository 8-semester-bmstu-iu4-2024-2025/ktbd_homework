<?php
require_once('init.php');
check_auth();

// Добавление компонента
if (isset($_POST['add_component'])) {
    $sql = "INSERT INTO components (comp_name, comp_type, comp_date) 
            VALUES (:name, :co_type, TO_DATE(:co_date, 'DD-MM-YY'))";
    
    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':co_type' => $_POST['co_type'],
        ':co_date' => $_POST['co_date']
    ));
	ora_query("COMMIT");
	header("Location: components.php");
	exit;
}

// Удаление компонента
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM components WHERE comp_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
	ora_query("COMMIT");
	header("Location: components.php");
	exit;
}

// Получение списка компонентов
$sql = "SELECT comp_id, comp_name, comp_type, 
               TO_CHAR(comp_date, 'DD.MM.YY') as comp_date 
        FROM components
	ORDER BY comp_id";
$stid = ora_query($sql);
$components = ora_fetch_all($stid);
?>

<html>
<head>
    <title>Компоненты</title>
    <style type="text/css">
        body { font-family: Arial; margin 20px; }
		.form-group {margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php require('index.php'); ?>
    
    <h2>Список компонентов</h2>
    <table class = "data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($components as $component): ?>
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
        <?php endforeach; ?>
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
            <input type="text" name="co_date" required
                   placeholder="ДД.ММ.ГГ"
                   pattern="\d{2}\.\d{2}\.\d{2}"
                   title="Введите дату в формате ДД.ММ.ГГ">
        </label>
    </div>
    <input type="submit" name="add_component" value="Добавить">
</form>
	<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>