<?php
function ora_connect()
{
    static $oracle_connection;
    if (!$oracle_connection) {
        $oracle_connection = oci_connect('system', 'oracle_password', 'host.docker.internal:1521/FREE');
        if (!$oracle_connection) {
            $e = oci_error();
            die("Ошибка подключения: " . $e['message']);
        }
    }
    return $oracle_connection;
}

function ora_disconnect()
{
    $oracle_connection = ora_connect();
    if ($oracle_connection) {
        oci_close($oracle_connection);
    }
}
?>