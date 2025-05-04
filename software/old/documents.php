<?php
require_once('init.php');
check_auth();

// Добавление документа
if (isset($_POST['add_document'])) {
    $sql = "INSERT INTO documents (docs_name, docs_type, docs_date, docs_auth) 
            VALUES (:name, :doc_type, TO_DATE(:doc_date, 'DD.MM.YY'), :auth)";
    
    ora_query($sql, array(
        ':name' => $_POST['name'],
        ':doc_type' => $_POST['doc_type'],
        ':doc_date' => $_POST['doc_date'],
        ':auth' => $_POST['auth']
    ));
	ora_query("COMMIT");
	header("Location: documents.php");
	exit;
}

// Удаление документа
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM documents WHERE docs_id = :id";
    ora_query($sql, array(':id' => (int)$_GET['delete']));
	ora_query("COMMIT");
	header("Location: documents.php");
	exit;
}

// Получение списка документов
$sql = "SELECT docs_id, docs_name, docs_type, docs_auth, 
               TO_CHAR(docs_date, 'DD.MM.YY') as docs_date 
        FROM documents
	";
$stid = ora_query($sql);
$documents = ora_fetch_all($stid);
?>

<html>
<head>
    <title>Документы</title>
    <style type="text/css">
        body { font-family: Arial; margin 20px; }
		.form-group {margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php require('index.php'); ?>
    
    <h2>Список документов</h2>
    <table class = "data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($documents as $document): ?>
        <tr>
            <td><?php echo htmlspecialchars($document['DOCS_ID']); ?></td>
            <td><?php echo htmlspecialchars($document['DOCS_NAME']); ?></td>
            <td><?php echo htmlspecialchars($document['DOCS_TYPE']); ?></td>
            <td><?php echo htmlspecialchars($document['DOCS_AUTH']); ?></td>
            <td><?php echo htmlspecialchars($document['DOCS_DATE']); ?></td>
            <td>
                <a href="?delete=<?php echo $document['DOCS_ID']; ?>" 
                   onclick="return confirm('Удалить документ?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Добавить документ</h2>
    <form method="post">
        <div class="form-group">
            <label>Название: <input type="text" name="name" required></label>
        </div>
        <div class="form-group">
        <label>Тип: 
            <select name="doc_type">
                <option value="Manual">Manual</option>
                <option value="Report">Report</option>
                <option value="Specification">Specification</option>
            </select>
        </label>
    </div>
        <div class="form-group">
            <label>Автор: <input type="text" name="auth" required></label>
        </div>
        <div class="form-group">
        <label>Дата: 
            <input type="text" name="doc_date" required
                   placeholder="ДД.ММ.ГГ"
                   pattern="\d{2}\.\d{2}\.\d{2}"
                   title="Введите дату в формате ДД.ММ.ГГ">
        </label>
    </div>
        <input type="submit" name="add_document" value="Добавить">
    </form>
	<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>