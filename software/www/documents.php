<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
$oracle_connection = ora_connect();
// Добавление документа
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_document'])) {
    $str = "INSERT INTO documents (docs_name, docs_type, docs_date, docs_auth) 
            VALUES (:name, :doc_type, TO_DATE(:doc_date, 'DD.MM.YY'), :auth)";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":doc_type", $_POST['doc_type'], -1);
    oci_bind_by_name($sql, ":doc_date", $_POST['doc_date'], -1);
    oci_bind_by_name($sql, ":auth", $_POST['auth'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление документа
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM documents WHERE docs_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение списка документов
$str = "SELECT docs_id, docs_name, docs_type, docs_auth, 
               TO_CHAR(docs_date, 'DD.MM.YY') as docs_date 
        FROM documents
	";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>

<head>
    <title>Документы</title>
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

    <h2>Список документов</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        <?php while ($document = oci_fetch_array($sql, OCI_BOTH)) { ?>
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
        <?php } ?>
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
                <input type="text" name="doc_date" required placeholder="ДД.ММ.ГГ" pattern="\d{2}\.\d{2}\.\d{2}"
                    title="Введите дату в формате ДД.ММ.ГГ">
            </label>
        </div>
        <input type="submit" name="add_document" value="Добавить">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>