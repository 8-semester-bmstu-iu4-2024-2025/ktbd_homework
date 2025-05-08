<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['empl_job'])) {
    header(header: "Location: login.php");
    exit;
} else {
    if (strtolower($_SESSION['empl_job']) == 'admin') {
        include("admin.php");
    } else {
        include("employee.php");
    }
}
?>