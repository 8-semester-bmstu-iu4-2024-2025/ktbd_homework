<?php
require_once('init.php');
check_auth();

// Добавление
if (isset($_POST['add_product'])) {
    $sql = "INSERT INTO products (prds_name, prds_stat, prds_empl_id, prds_eqpt_id, prds_docs_id, prds_rejc_id, prds_quantity, prds_tp_type) 
            VALUES (:name, :status, :empl_id, :eqpt_id, :docs_id, :rejc_id, :quantity, :tp_type)";
    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':status' => $_POST['status'],
        ':empl_id' => $_SESSION['user_id'],
        ':eqpt_id' => !empty($_POST['eqpt_id']) ? $_POST['eqpt_id'] : null,
        ':docs_id' => !empty($_POST['docs_id']) ? $_POST['docs_id'] : null,
        ':rejc_id' => !empty($_POST['rejc_id']) ? $_POST['rejc_id'] : null,
		':quantity' => $_POST['quantity'],
		':tp_type' => $_POST['tp_type'],
    ));
	ora_query("COMMIT");
	header("Location: products.php");
	exit;
}

// Удаление
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM products WHERE prds_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
	ora_query("COMMIT");

	header("Location: products.php");
	exit;
}

// Получение данных
$sql = "SELECT p.prds_id, p.prds_name, p.prds_stat, p.prds_quantity, p.prds_tp_type,
               e.empl_name, eq.eqpt_name, d.docs_name, 
               r.rejc_type, df.dfct_name as defect_name
        FROM products p
        LEFT JOIN employeess e ON p.prds_empl_id = e.empl_id
        LEFT JOIN equipment eq ON p.prds_eqpt_id = eq.eqpt_id
        LEFT JOIN documents d ON p.prds_docs_id = d.docs_id
        LEFT JOIN rejections r ON p.prds_rejc_id = r.rejc_id
        LEFT JOIN defect df ON r.rejc_dfct_id = df.dfct_id
        ORDER BY p.prds_id";

$stid = ora_query($sql);
$products = ora_fetch_all($stid);
?>

<html>
<head>
    <title>Продукция</title>
    <style type="text/css">
        body { font-family: Arial; margin: 20px; }
        .form-group { margin-bottom: 10px; }
        .data-table { width: 100%; }
        .data-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <?php require_once('index.php'); ?>
    
    <h2>Список продукции</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Статус</th>
			<th>Количество</th>
			<th>Тип ТП</th>
            <th>Ответственный</th>
            <th>Оборудование</th>
            <th>Документ</th>
            <th>Брак (тип/дефект)</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['PRDS_ID']) ?></td>
            <td><?= htmlspecialchars($product['PRDS_NAME']) ?></td>
            <td><?= htmlspecialchars($product['PRDS_STAT']) ?></td>
			<td><?= htmlspecialchars($product['PRDS_QUANTITY']) ?></td>
			<td><?= htmlspecialchars($product['PRDS_TP_TYPE']) ?></td>
            <td><?= htmlspecialchars($product['EMPL_NAME']) ?></td>
<td><?= htmlspecialchars(isset($product['EQPT_NAME']) ? $product['EQPT_NAME'] : '—') ?></td>
<td><?= htmlspecialchars(isset($product['DOCS_NAME']) ? $product['DOCS_NAME'] : '—') ?></td>
            <td>
                <?php if (!empty($product['REJC_TYPE'])): ?>
                    <?= htmlspecialchars($product['REJC_TYPE']) ?>
                    <?= !empty($product['DEFECT_NAME']) ? "({$product['DEFECT_NAME']})" : '' ?>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
            <td>
                <a href="?delete=<?= $product['PRDS_ID'] ?>" 
                   onclick="return confirm('Удалить продукцию?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<h2>Добавить продукцию</h2>
<form method="post">
    <div class="form-group">
        <label>Название: <input type="text" name="name" required></label>
    </div>
    <div class="form-group">
        <label>Статус: 
            <select name="status" required>
                <option value="В производстве">В производстве</option>
                <option value="Готово">Готово</option>
                <option value="На проверке">На проверке</option>
                <option value="Брак">Брак</option>
            </select>
        </label>
    </div>
	
	<div class="form-group">
    <label>Количество: 
        <input type="number" name="quantity" required min="1">
    </label>
</div>
<div class="form-group">
    <label>Тип ТП: 
        <select name="tp_type" required>
            <option value="Rychnoy">Ручной</option>
            <option value="Polyavtomaticheskiy">Полуавтоматический</option>
            <option value="Avtomaticheskiy">Автоматический</option>
        </select>
    </label>
</div>

	
    <div class="form-group">
        <label>Оборудование: 
            <select name="eqpt_id">
                <option value="">-- Не выбрано --</option>
                <?php 
                $eqpt = ora_fetch_all(ora_query("SELECT eqpt_id, eqpt_name FROM equipment"));
                foreach ($eqpt as $item): ?>
                <option value="<?= $item['EQPT_ID'] ?>"><?= htmlspecialchars($item['EQPT_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="form-group">
        <label>Документ: 
            <select name="docs_id">
                <option value="">-- Не выбрано --</option>
                <?php 
                $docs = ora_fetch_all(ora_query("SELECT docs_id, docs_name FROM documents"));
                foreach ($docs as $doc): ?>
                <option value="<?= $doc['DOCS_ID'] ?>"><?= htmlspecialchars($doc['DOCS_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="form-group">
        <label>Брак: 
            <select name="rejc_id">
                <option value="">-- Не выбрано --</option>
                <?php 
                $rej = ora_fetch_all(ora_query("SELECT r.rejc_id, r.rejc_type, d.dfct_name 
                                              FROM rejections r LEFT JOIN defect d ON r.rejc_dfct_id = d.dfct_id"));
                foreach ($rej as $rj): ?>
                <option value="<?= $rj['REJC_ID'] ?>">
                    <?= htmlspecialchars($rj['REJC_TYPE']) ?> (<?= htmlspecialchars($rj['DFCT_NAME']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <input type="submit" name="add_product" value="Добавить">
</form>
	
<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>