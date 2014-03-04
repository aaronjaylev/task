<?php

include "class.SageTask.php";

$SageTask = new SageTask();

// $register = $SageTask->Register('aaronjaylev@gmail.com', 'Aaron Jay');

$tasks = $SageTask->GetTaskList('aaronjaylev@gmail.com', 'jay');

echo '<pre><br>Tasks Are: ' . print_r($tasks, 1) . '<br>done<br></pre>';


?>