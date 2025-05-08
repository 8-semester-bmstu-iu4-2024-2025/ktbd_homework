<?php
if (!session_id()) {
    session_start();
}
session_unset();
session_destroy();

require_once('oracle.php');

if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['username']) && isset($_POST['password'])) {
    $oracle_connection = ora_connect();
    $str = "SELECT empl_id, empl_name, empl_job FROM employees
            WHERE empl_name = :username AND empl_pass = :pass";

    $sql = oci_parse($oracle_connection, $str);
    oci_bind_by_name($sql, ":username", $_POST['username'], -1);
    oci_bind_by_name($sql, ":pass", $_POST['password'], -1);
    oci_execute($sql, OCI_DEFAULT);
    $emp = oci_fetch_array($sql, OCI_BOTH);
    if (!$emp) {
        $error = "Неверный логин или пароль";
        ora_disconnect();
    } else {
        session_start();
        oci_fetch($sql);
        ora_disconnect();
        $_SESSION['empl_id'] = $emp['empl_id'];
        $_SESSION['empl_name'] = $emp['empl_name'];
        $_SESSION['empl_job'] = $emp['EMPL_JOB'];
        header(header: "Location: index.php");
        exit;
    }
}
?>

<html>

<body>
    <div class="login-form">
        <?php if (isset($error))
            echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                Логин: <input type="text" name="username">
            </div>
            <div class="form-group">
                Пароль: <input type="password" name="password">
            </div>
            <input type="submit" name="login" value="Войти">
        </form>
    </div>
    <div class="footer-bumper">
        Система управления производством © 2025
    </div>
</body>

</html>