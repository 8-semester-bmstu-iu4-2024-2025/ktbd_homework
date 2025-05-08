<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: index.php");
}
require("oracle.php");
require('FPDF/fpdf.php');

$oracle_connection = ora_connect();

// Генерация PDF
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['product_id'])) {
    ob_start();
    // Очистка буфера
    while (ob_get_level()) {
        ob_end_clean();
    }
    $str = "SELECT p.*, eq.eqpt_name, d.docs_name 
            FROM products p
            LEFT JOIN equipment eq ON p.prds_eqpt_id = eq.eqpt_id
            LEFT JOIN documents d ON p.prds_docs_id = d.docs_id
            WHERE p.prds_id = :id";
    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":id", $_GET['product_id'], -1);
    oci_execute($sql, OCI_COMMIT_ON_SUCCESS);
    $product = oci_fetch_array($sql, OCI_BOTH);
    if ($product == null) {
        die("Изделие не найдено.");
    }

    // Генерация PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('images/map.png', 0, 0, 210, 200);
    $pdf->SetFont('Arial', 'B', 16);

    $leftMargin = 37.5;
    // Данные изделия
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 45, 'Izdelie:', 0, 0);
    $pdf->Cell(0, 45, $product['PRDS_NAME'], 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(50, 10, 'Kolichectvo:', 0, 0);
    $pdf->Cell(0, 10, $product['PRDS_QUANTITY'], 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(50, 47, 'Type TP:', 0, 0);
    $pdf->Cell(0, 47, $product['PRDS_TP_TYPE'], 0, 1);

    // Этапы

    $pdf->SetFont('Arial', 'B', 14);
    $stages = array(
        'Podgotovka komponentov',
        'Cborka (' . (isset($product['PRDS_TP_TYPE']) ? $product['PRDS_TP_TYPE'] : ' ') . ')',
        'Testirovanie',
        'Ypakovka'
    );
    foreach ($stages as $stage) {
        $pdf->SetX(10);
        $pdf->Cell(0, 19, $stage, 0, 1);
    }

    $pdf->Output('marshrut.pdf', 'D');
    ob_end_flush(); // Освобождаем буфер
}

// Получение списка продуктов
$str = "SELECT prds_id, prds_name FROM products";
$sql = oci_parse($oracle_connection, $str);
oci_execute($sql, OCI_COMMIT_ON_SUCCESS);

ora_disconnect();
?>

<html>

<head>
    <title>Маршрутная карта</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        select,
        input[type="submit"] {
            padding: 5px;
        }
    </style>
</head>

<body>
    <?php require('index.php'); ?>

    <h2>Выберите изделие</h2>
    <form method="get">
        <select name="product_id" required>
            <?php while ($prod = oci_fetch_array($sql, OCI_BOTH)) { ?>
                <option value="<?php echo $prod['PRDS_ID']; ?>">
                    <?php echo htmlspecialchars($prod['PRDS_NAME']); ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Сформировать PDF">
    </form>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>