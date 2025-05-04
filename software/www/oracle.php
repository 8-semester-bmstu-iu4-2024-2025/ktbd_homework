<?php
// oracle.php
if (!defined('ORACLE_PHP_LOADED')) {
	define('ORACLE_PHP_LOADED', true);

	function ora_connect() {
		static $conn;
		if (!$conn) {
		$conn = OCILogon('hr', 'hr', 'orcl');
		if (!$conn) {
			$e = OCIError();
			die("Ошибка подключения: " . $e['message']);
		}
	}
return $conn;
}

function ora_query($sql, $params = array()) {
    $conn = ora_connect();
    $stid = OCIParse($conn, $sql);
    if (!$stid) {
        $e = OCIError($conn);
        die("Ошибка запроса: " . $e['message']);
    }
    
    foreach ($params as $key => $val) {
        OCIBindByName($stid, $key, $params[$key], -1);
    }
    
    if (!OCIExecute($stid, OCI_DEFAULT)) {
        $e = OCIError($stid);
        die("Ошибка выполнения: " . $e['message']);
    }
    
    return $stid;
}


function ora_fetch_all($stid) {
    $results = array();
    while (OCIFetchInto($stid, $row, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $results[] = $row;
    }
    return $results;
}

function ora_disconnect() {
    $conn = ora_connect();
    if ($conn) {
		OCILogoff($conn);
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