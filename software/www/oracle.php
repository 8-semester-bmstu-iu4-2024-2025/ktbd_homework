<?php
// oracle.php
if (!defined('ORACLE_PHP_LOADED')) {
    define('ORACLE_PHP_LOADED', true);
    function ora_connect()
    {
        static $conn;
        if (!$conn) {
            $conn = oci_connect('hr', 'hr', 'orcl');
            if (!$conn) {
                $e = oci_error();
                die("Ошибка подключения: " . $e['message']);
            }
        }
        return $conn;
    }

    function ora_query($sql, $params = array())
    {
        $conn = ora_connect();
        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            die("Ошибка запроса: " . $e['message']);
        }

        foreach ($params as $key => $val) {
            oci_bind_by_name($stid, $key, $params[$key], -1);
        }

        if (!oci_execute($stid, OCI_DEFAULT)) {
            $e = oci_error($stid);
            die("Ошибка выполнения: " . $e['message']);
        }

        return $stid;
    }

    function ora_fetch_all($stid)
    {
        $results = array();
        while (oci_fetch_row($stid, $row, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $results[] = $row;
        }
        return $results;
    }

    function ora_disconnect()
    {
        $conn = ora_connect();
        if ($conn) {
            oci_close($conn);
        }
    }

    register_shutdown_function('ora_disconnect');
}
?>
<html>

<head>
</head>

<body>
</body>

</html>