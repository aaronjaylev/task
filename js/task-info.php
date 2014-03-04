<?php

include "../config.php";

$TaskID = $_GET['TaskID'];
if ($TaskID != 'Add') {
	$info = $db->SelectRow('Tasks', array('TaskID' => $TaskID));
	if ($info['DueDate'] == '0000-00-00' || $info['DueDate'] == '1970-01-01') {
		$info['DueDate'] = '';
	}
} else {
	$info['Title'] = '';
	$info['Description'] = '';
	$info['DueDate'] = '';
	$info['Priority'] = $db->enum_default_value('Tasks', 'Priority');
}

$values = $db->enum_select('Tasks', 'Priority');
$info['PriorityValues'] = $values;

echo json_encode($info);

?>