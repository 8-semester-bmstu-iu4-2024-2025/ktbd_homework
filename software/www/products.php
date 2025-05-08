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
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['add_product'])) {

    $str = "INSERT INTO products (prds_name, prds_stat, prds_empl_id, prds_eqpt_id, prds_docs_id, prds_quantity, prds_tp_type) 
            VALUES (:name, :status, :empl_id, :eqpt_id, :docs_id, :quantity, :tp_type)";

    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":name", $_POST['name'], -1);
    oci_bind_by_name($sql, ":status", $_POST['status'], -1);
    oci_bind_by_name($sql, ":empl_id", $_POST['empl_id'], -1);
    oci_bind_by_name($sql, ":eqpt_id", $_POST['eqpt_id'], -1);
    oci_bind_by_name($sql, ":docs_id", $_POST['docs_id'], -1);
    oci_bind_by_name($sql, ":quantity", $_POST['quantity'], -1);
    oci_bind_by_name($sql, ":tp_type", $_POST['tp_type'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Удаление
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['delete'])) {
    $str = "DELETE FROM products WHERE prds_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['delete'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
}

// Получение данных
$str = "SELECT p.prds_id, p.prds_name, p.prds_stat, p.prds_quantity, p.prds_tp_type,
               e.empl_name, eq.eqpt_name, d.docs_name, 
               df.dfct_name as defect_name
        FROM products p
        LEFT JOIN employees e ON p.prds_empl_id = e.empl_id
        LEFT JOIN equipment eq ON p.prds_eqpt_id = eq.eqpt_id
        LEFT JOIN documents d ON p.prds_docs_id = d.docs_id
        LEFT JOIN defects df ON p.prds_dfct_id = df.dfct_id
        ORDER BY p.prds_id";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_DEFAULT);
ora_disconnect();
?>

<html>
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
        <?php while ($product = oci_fetch_array($sql, OCI_BOTH)) { ?>
            <tr>
                <td><?= htmlspecialchars($product['PRDS_ID']) ?></td>
                <td><?= htmlspecialchars($product['PRDS_NAME']) ?></td>
                <td><?= htmlspecialchars($product['PRDS_STAT']) ?></td>
                <td><?= htmlspecialchars($product['PRDS_QUANTITY']) ?></td>
                <td><?= htmlspecialchars($product['PRDS_TP_TYPE']) ?></td>
                <td><?= htmlspecialchars($product['EMPL_NAME']) ?></td>
                <td><?= htmlspecialchars(isset($product['EQPT_NAME']) ? $product['EQPT_NAME'] : '—') ?></td>
                <td><?= htmlspecialchars(isset($product['DOCS_NAME']) ? $product['DOCS_NAME'] : '—') ?></td>
                <td><?= htmlspecialchars(isset($product['DEFECT_NAME']) ? $product['DEFECT_NAME'] : '—') ?></td>
                <td><a href="?delete=<?= $product['PRDS_ID'] ?>" onclick="return confirm('Удалить продукцию?')">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Добавить продукцию</h2>
    <form method="post">
        <div class="form-group">
            <label>Название: <input type="text" name="name" required></label>
        </div>
        <div class="form-group">
            <label>Статус:
                <select name="status" required>
                    <option value="Active">В производстве</option>
                    <option value="Done">Готово</option>
                    <option value="Checking">На проверке</option>
                    <option value="Defected">Брак</option>
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
            <label>Ответственный:
                <select name="empl_id">
                    <option value="">-- Не выбрано --</option>
                    <?php
                    $oracle_connection = ora_connect();
                    $str = "SELECT empl_id, empl_name FROM employees";
                    $sql = oci_parse($oracle_connection, $str);
                    oci_execute($sql, OCI_DEFAULT);
                    ora_disconnect();
                    while ($item = oci_fetch_array($sql, OCI_BOTH)) { ?>
                        <option value="<?= $item['EMPL_ID'] ?>"><?= htmlspecialchars($item['EMPL_NAME']) ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Оборудование:
                <select name="eqpt_id">
                    <option value="">-- Не выбрано --</option>
                    <?php
                    $oracle_connection = ora_connect();
                    $str = "SELECT eqpt_id, eqpt_name FROM equipment";
                    $sql = oci_parse($oracle_connection, $str);
                    oci_execute($sql, OCI_DEFAULT);
                    ora_disconnect();
                    while ($item = oci_fetch_array($sql, OCI_BOTH)) { ?>
                        <option value="<?= $item['EQPT_ID'] ?>"><?= htmlspecialchars($item['EQPT_NAME']) ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Документ:
                <select name="docs_id">
                    <option value="">-- Не выбрано --</option>
                    <?php
                    $oracle_connection = ora_connect();
                    $str = "SELECT docs_id, docs_name FROM documents";
                    $sql = oci_parse($oracle_connection, $str);
                    oci_execute($sql, OCI_DEFAULT);
                    ora_disconnect();
                    while ($doc = oci_fetch_array($sql, OCI_BOTH)) { ?>
                        <option value="<?= $doc['DOCS_ID'] ?>"><?= htmlspecialchars($doc['DOCS_NAME']) ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
        <input type="submit" name="add_product" value="Добавить">
    </form>

    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>