<?php

include_once "class.EvoSQL.php";

$db_host = "localhost";
$db_name = "sagetask_database";
$db_user = "sagetask_user";
$db_pass = "sagetask_password";

$UserID = 1;

$db = new EvoSQL();

$db->Connect($db_host, $db_name, $db_user, $db_pass);

?>
