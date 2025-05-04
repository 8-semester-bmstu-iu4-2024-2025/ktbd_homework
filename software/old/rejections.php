<?php
require_once('init.php');
check_auth();

// Добавление брака
if (isset($_POST['add_rejection'])) {

    
    $sql = "INSERT INTO rejections (rejc_type, rejc_date, rejc_dfct_id, rejc_comp_id) 
            VALUES (:rejc_type, TO_DATE(:r_date, 'DD.MM.YY'), :defect_id, :component_id)";
    
    $params = array(
        ':rejc_type' => $_POST['rejc_type'],
        ':r_date' => $_POST['r_date'],
        ':defect_id' => (int)$_POST['defect_id']
    );
    
    // Обработка компонента (может быть NULL)
    if (!empty($_POST['component_id'])) {
        $params[':component_id'] = (int)$_POST['component_id'];
    } else {
        $sql = str_replace(", :component_id", "", $sql);
        $sql = str_replace(", rejc_comp_id", "", $sql);
	unset($params[':component_id']);
    }
   //echo "SQL: $sql <br>";
//print_r($params);
    $result = ora_query($sql, $params);
    if (!$result) {
        $error = ocierror();
        die("Ошибка при добавлении: " . $error['message']);
    }
    
    ora_query("COMMIT");
    header("Location: rejections.php");
    exit;
}

// Удаление брака
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM rejections WHERE rejc_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
	ora_query("COMMIT");
	header("Location: rejections.php");
	exit;
}

// Получение списка брака с информацией о дефектах и компонентах
$sql = "SELECT r.rejc_id, r.rejc_type, TO_CHAR(r.rejc_date, 'DD.MM.YY') as rejc_date,
               d.dfct_name, c.comp_name
        FROM rejections r
        LEFT JOIN defect d ON r.rejc_dfct_id = d.dfct_id
        LEFT JOIN components c ON r.rejc_comp_id = c.comp_id
		ORDER BY r.rejc_id";
$stid = ora_query($sql);
$rejections = ora_fetch_all($stid);
?>

<html>
<head>
    <title>Брак</title>
    <style type="text/css">
        body { font-family: Arial; margin 20px; }
		.form-group {margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php require('index.php'); ?>
    
    <h2>Список брака</h2>
    <table class = "data-table">
        <tr>
            <th>ID</th>
            <th>Тип</th>
            <th>Дата</th>
            <th>Дефект</th>
            <th>Компонент</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($rejections as $rejection): ?>
        <tr>
            <td><?php echo htmlspecialchars($rejection['REJC_ID']); ?></td>
            <td><?php echo htmlspecialchars($rejection['REJC_TYPE']); ?></td>
            <td><?php echo htmlspecialchars($rejection['REJC_DATE']); ?></td>
            <td><?php echo htmlspecialchars($rejection['DFCT_NAME']); ?></td>
            <td><?php echo htmlspecialchars($rejection['COMP_NAME']); ?></td>
            <td>
                <a href="?delete=<?php echo $rejection['REJC_ID']; ?>" 
                   onclick="return confirm('Удалить запись о браке?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<h2>Добавить брак</h2>
<form method="post">
    <div class="form-group">
        <label>Тип: 
            <select name="rejc_type" required>
                <option value="Critical">Critical</option>
                <option value="Medium">Medium</option>
                <option value="Good">Good</option>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>Дата: 
            <input type="text" name="r_date" required
                   placeholder="ДД.ММ.ГГ"
                   pattern="\d{2}\.\d{2}\.\d{2}"
                   title="Введите дату в формате ДД.ММ.ГГ">
        </label>
    </div>
    <div class="form-group">
        <label>Дефект: 
            <select name="defect_id" required>
                <?php 
                $defects = ora_fetch_all(ora_query("SELECT dfct_id, dfct_name FROM defect"));
                foreach ($defects as $defect): ?>
                <option value="<?= $defect['DFCT_ID'] ?>"><?= htmlspecialchars($defect['DFCT_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="form-group">
        <label>Компонент: 
            <select name="component_id">
                <option value="">-- Не выбрано --</option>
                <?php 
                $components = ora_fetch_all(ora_query("SELECT comp_id, comp_name FROM components"));
                foreach ($components as $comp): ?>
                <option value="<?= $comp['COMP_ID'] ?>"><?= htmlspecialchars($comp['COMP_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <input type="submit" name="add_rejection" value="Добавить">
</form>
	<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>