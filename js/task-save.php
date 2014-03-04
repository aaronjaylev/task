<?php

include "../config.php";

$DueDate = $_POST['DueDate'];
$DueDate = date('Y-m-d', strtotime($DueDate));

$a = array(
	'UserID' => $UserID,
	'Title' => $_POST['Title'],
	'Description' => $_POST['Description'],
	'DueDate' => $DueDate,
	'Priority' => $_POST['Priority']
);

if ($_POST['TaskID'] == 'Add') {
	$db->InsertRow('Tasks', $a);
} else {
	$db->UpdateRows('Tasks', $a, array('TaskID' => $_POST['TaskID']));
}

echo "done";

?>