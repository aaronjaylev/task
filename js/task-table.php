<?php

include "../config.php";

$results = $db->SelectRows('Tasks', array('UserID' => $UserID));

if (mysql_num_rows($results) == 0) {
	echo '<p><i>No tasks.  Add your first one now</i></p>';
} else {
	echo '<div class="panel panel-default">';
	echo '<table class="table">';
	echo '<tr><th>Title</th><th>Priority</th><th>Due Date</th><th class="text-center">Completed</th></tr>' . "\n";
	while ($info = mysql_fetch_assoc($results)) {
		$Title = ($info['Title'] == '' ? '<i>Untitled</i>' : $info['Title']);
		$DueDate = ($info['DueDate'] == '0000-00-00' || $info['DueDate'] == '1970-01-01' ? '' : date('D M j, Y', strtotime($info['DueDate'])));
		$Completed = ($info['Status'] == 'Completed' ? 1 : 0);
		echo '<tr><td' . ($Completed ? ' class="completed-task"' : '') . '><a href="#" onClick="ShowTask(\'' . $info['TaskID'] . '\',\'Completed\');">' . $Title . '</a></td>';
		echo '<td' . ($Completed ? ' class="completed-task"' : '') . '>' . $info['Priority'] . '</td>';
		echo '<td' . ($Completed ? ' class="completed-task"' : '') . '>' . $DueDate . '</td>';
		echo '<td class="text-center"><input type="checkbox" onClick="TaskStatus(\'' . $info['TaskID'] . "','" . ($Completed ? 'Active' : 'Completed') . '\');"' . ($Completed ? ' checked' : '') . '></td></tr>' . "\n";
	}
	echo '</table></div>';
}

?>