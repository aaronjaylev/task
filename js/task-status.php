<?php

include "../config.php";

$db->UpdateRows('Tasks', array('Status' => $_POST['Status']), array('TaskID' => $_POST['TaskID']));

echo "done";

?>