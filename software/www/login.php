<?php
if (session_id()) {session_destroy();}
session_start();
require_once('oracle.php');


if (isset($_POST['login'])) {
    $sql = "SELECT empl_id, empl_name, empl_job FROM employeess 
            WHERE empl_name = :name AND empl_pass = :pass";
    
    $stid = ora_query($sql, array(
        ':name' => $_POST['username'],
        ':pass' => $_POST['password']
    ));
    
    if (OCIFetchInto($stid, $row, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $_SESSION['user_id'] = $row['EMPL_ID'];
        $_SESSION['username'] = $row['EMPL_NAME'];
        $_SESSION['role'] = $row['EMPL_JOB'];
        header("Location: products.php");
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<html>
<head>
    <title>Авторизация</title>
    <style type="text/css">
        body { font-family: Arial; }
        .login-form { width: 300px; margin: 50px auto; }
        .form-group { margin-bottom: 10px; }
        input { padding: 3px; }
		.footer-bumper {
        border-top: 2px solid #4285f4;
        margin-top: 20px;
        padding-top: 10px;
        color: #666;
        text-align: center;
        font-size: 12px;
    }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="post">
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
Система управления производством © <?php echo date('Y'); ?>
</div>
</body>
</html>