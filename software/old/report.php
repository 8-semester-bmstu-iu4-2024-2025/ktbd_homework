<?php
ob_start();

require_once('init.php');
require('fpdf.php');
check_auth();

// Получение списка продуктов
$sql_products = "SELECT prds_id, prds_name FROM products";
$stid_products = ora_query($sql_products);
$products = ora_fetch_all($stid_products);

// Генерация PDF
if (isset($_GET['product_id'])) {
    // Очистка буфера
    while (ob_get_level()) {
        ob_end_clean();
    }

    $product_id = (int)$_GET['product_id'];
    $sql = "SELECT p.*, eq.eqpt_name, d.docs_name 
            FROM products p
            LEFT JOIN equipment eq ON p.prds_eqpt_id = eq.eqpt_id
            LEFT JOIN documents d ON p.prds_docs_id = d.docs_id
            WHERE p.prds_id = :id";
    $stid = ora_query($sql, array(':id' => $product_id));
    $result = ora_fetch_all($stid);
    $product = isset($result[0]) ? $result[0] : array();

    if (empty($product)) {
        die("Изделие не найдено.");
    }

    // Генерация PDF
    $pdf = new FPDF();
    $pdf->AddPage();
	$pdf->Image('a.png', 0, 0, 210, 200);
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
        '1. Podgotovka komponentov',
        '2. Cborka (' . (isset($product['PRDS_TP_TYPE']) ? $product['PRDS_TP_TYPE'] : '') . ')',
        '3. Testirovanie',
        '4. Ypakovka'
    );
    foreach ($stages as $stage) {
		    $pdf->SetX(10);
        $pdf->Cell(0, 19, $stage, 0, 1);
    }

    $pdf->Output('marshrut.pdf', 'D');
    exit;
}
ob_end_flush(); // Освобождаем буфер
?>

<html>
<head>
    <title>Маршрутная карта</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        select, input[type="submit"] { padding: 5px; }
    </style>
</head>
<body>
    <?php require('index.php'); ?>
    
    <h2>Выберите изделие</h2>
    <form method="get">
        <select name="product_id" required>
            <?php foreach ($products as $prod) { ?>
                <option value="<?php echo $prod['PRDS_ID']; ?>">
                    <?php echo htmlspecialchars($prod['PRDS_NAME']); ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Сформировать PDF">
    </form>
	<div class="footer-bumper">
    Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>