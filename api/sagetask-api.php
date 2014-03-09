<?php

/**
* SageTask.com
*
* SageTask.com is a Website, API, and SDK for Creating and Managing simple tasks.
*
* This code is visible on http://www.sagetask.com.  It is a "one page" website
* and uses JQuery, JQueryUI, Twitter Bootstrap to format the pages.  Connection
* to the MySQL database is done through an API which can also be called directly.
* I hope you enjoy viewing, using and learning from this code.  You may use it for 
* your own projects if you give me credit by leaving this license notification 
* in your files.  Happy Coding.  Aaron Jay
*
* @package SageTask
* @author Aaron Jay Lev <aaronjaylev@gmail.com>
* @copyright Copyright (c) 2014, Aaron Jay Lev
* @link http://www.sagetask.com
* @example http://www.sagetask.com/
* @license http://www.apache.org/licenses/LICENSE-2.0
*
* Copyright 2014 Aaron Jay Lev
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/


$ErrorMsg = '';

if (! isset($PostData)) {
	$PostData = file_get_contents("php://input");
}

if ($PostData == '') {
	$ErrorMsg = 'PostData was blank';
} else {
	$json = json_decode($PostData, 1);
	if ($json === false) {
		$ErrorMsg = 'could not decode json';
	}
}

function MailSetup() {
   include_once $_SERVER['DOCUMENT_ROOT'] . "/class.phpmailer5.php";
   include_once $_SERVER['DOCUMENT_ROOT'] . "/class.smtp.php";

   $mail = new PHPMailer();  // create a new object
   $mail->IsSMTP(); // enable SMTP
   $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
   $mail->SMTPAuth = true;  // authentication enabled
   $mail->SMTPSecure = 'ssl';
   $mail->Host = 'mail.sagetask.com';
   $mail->Port = 465; 
   $mail->Username = 'smtp@sagetask.com';  
   $mail->Password = 'sagetasksmtp';      
   
   return $mail;
}  

function Login(&$db, &$json, &$ErrorMsg) {
	if ($json['Email'] == '') {
		$ErrorMsg = 'Email was blank';
		return false;
	} else if ($json['Password'] == '') {
		$ErrorMsg = 'Password was blank';
		return false;
	} else {
		$UserInfo = $db->SelectRow('Users', array('Email' => $json['Email'],
			'Password' => $json['Password']));
		if ($UserInfo === false) {
			$ErrorMsg = 'Invalid Login';
			return false;
		} else {
			return $UserInfo;
		}
	}
}

function Register($Email, $Password) {
	$mail = MailSetup(); // include_functions.php

	$mail->AddAddress($Email);
	$mail->From = "aaronjay@sagetask.com";
	$mail->FromName = "Sage Task";
	$mail->Subject = "Sage Task Registration";
	
	$mail->Body = "This is the Sage Task Registration.<br />
<br />
Email: $Email<br />
Password: <b>$Password</b><br />
<br />
Thank you,<br />
<br />
Sage Task";

	$mail->IsHTML(1);

	if (!$mail->Send()) {
		return "There has been a mail error: " . $mail->ErrorInfo;
	} else {
		return "";
	}
}

function Forgot($Email, $Password) {
	$mail = MailSetup(); // include_functions.php

	$mail->AddAddress($Email);
	$mail->From = "aaronjay@sagetask.com";
	$mail->FromName = "Sage Task";
	$mail->Subject = "Sage Task Forgot Password";
	
	$mail->Body = "Thank you for visiting SageTask.com.  Someone, probably you, requested to have the password reset on the website.  The new password is below.<br />
<br />
Email: $Email<br />
Password: <b>$Password</b><br />
<br />
Thank you,<br />
<br />
Sage Task";

	$mail->IsHTML(1);

	if (!$mail->Send()) {
		return "There has been a mail error: " . $mail->ErrorInfo;
	} else {
		return "";
	}
}

function getTableData(&$db, $UserID, $SortBy) {
	$sort = 'TaskID';
	if ($SortBy == 'Title') {
		$sort = 'Title';
	} else if ($SortBy == 'DueDate') {
		$sort = 'DueDate';
	} else if ($SortBy == 'Completed') {
		$sort = 'Status';
	}
	$results = $db->SelectRows('Tasks', array('UserID' => $UserID), 0, $sort);
	$Data = array();
	while ($info = mysql_fetch_assoc($results)) {
		unset($info['UserID']);
		if ($info['DueDate'] == '0000-00-00' || $info['DueDate'] == '1970-01-01') {
			$info['DueDate'] = '';
		} else {
			$info['DueDate'] = date('m/d/Y', strtotime($info['DueDate']));
		}
		$Data[] = $info;
	}
	return $Data;
}



if ($ErrorMsg == '') {

	include "../config.php";
	$Data = '';
	if ($json['Action'] == 'Register') {
		if ($json['Email'] == '') {
			$ErrorMsg = 'Email was blank';
		} else {
			$UserInfo = $db->SelectRow('Users', array('Email' => $json['Email']));
			if ($UserInfo !== false) {
				$ErrorMsg = 'Email is already registered.  Please Login.';
			} else {
				$Password = mt_rand(1000, 9999);
				$UserID = $db->InsertRow('Users', array('Email' => $json['Email'],
					'Name' => $json['Name'],
					'Password' => $Password));
				$ErrorMsg = Register($json['Email'], $Password);
				if ($ErrorMsg == '') {
					$Data = 'Email Sent';
				}
			}
		}
	} else if ($json['Action'] == 'Forgot') {
		if ($json['Email'] == '') {
			$ErrorMsg = 'Email was blank';
		} else {
			$UserInfo = $db->SelectRow('Users', array('Email' => $json['Email']));
			if ($UserInfo === false) {
				$ErrorMsg = 'Email not found in the database.';
			} else {
				$Password = mt_rand(1000, 9999);
				$db->UpdateRows('Users', array('Password' => $Password),
					array('Email' => $json['Email']));
				$ErrorMsg = Forgot($json['Email'], $Password);
				if ($ErrorMsg == '') {
					$Data = 'Password Sent';
				}
			}
		}
	} else if ($json['Action'] == 'SignIn') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$Data = array('Message' => 'Success', 'Name' => $UserInfo['Name']);
		}
	} else if ($json['Action'] == 'GetTaskList') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$Data =	getTableData($db, $UserInfo['UserID'], $UserInfo['SortBy']);
		}
	} else if ($json['Action'] == 'Sort') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$db->UpdateRows('Users', array('SortBy' => $json['SortBy']), array('UserID' => $UserInfo['UserID'])); // Save value in the database
			$Data =	getTableData($db, $UserInfo['UserID'], $json['SortBy']);
		}
	} else if ($json['Action'] == 'TaskInfo') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$TaskID = $json['TaskID'];
			if ($TaskID != 'Add') {
				$Data = $db->SelectRow('Tasks', array('TaskID' => $TaskID));
				if ($Data === false) {
					$ErrorMsg = 'Task Not Found';
				} else if ($Data['DueDate'] == '0000-00-00' || $Data['DueDate'] == '1970-01-01') {
					$Data['DueDate'] = '';
				} else {
					$Data['DueDate'] = date('m/d/Y', strtotime($Data['DueDate']));
				}
			} else {
				$Data['Title'] = '';
				$Data['Description'] = '';
				$Data['DueDate'] = '';
				$Data['Priority'] = $db->enum_default_value('Tasks', 'Priority');
			}

			$values = $db->enum_select('Tasks', 'Priority');
			$Data['PriorityValues'] = $values;
		}
	} else if ($json['Action'] == 'TaskSave') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$TaskID = $json['TaskID'];
			$DueDate = date('Y-m-d', strtotime($json['DueDate']));

			$a = array(
				'UserID' => $UserInfo['UserID'],
				'Title' => $json['Title'],
				'Description' => $json['Description'],
				'DueDate' => $DueDate,
				'Priority' => $json['Priority']
			);

			if ($TaskID == 'Add') {
				$db->InsertRow('Tasks', $a);
				$Data['InfoMsg'] = 'Task Added';
			} else {
				$db->UpdateRows('Tasks', $a, array('TaskID' => $TaskID));
				$Data['InfoMsg'] = 'Task ' . $TaskID . ' Updated';
			}
		}
	} else if ($json['Action'] == 'TaskStatus') {
		$UserInfo = Login($db, $json, $ErrorMsg);
		if ($ErrorMsg == '') {
			$TaskID = $json['TaskID'];
			$TaskInfo = $db->SelectRow('Tasks', array('TaskID' => $TaskID));
			if ($TaskInfo === false) {
				$ErrorMsg = 'Task Not Found in the database';
			} else {
				$db->UpdateRows('Tasks', array('Status' => $json['Status']), array('TaskID' => $TaskID));
				$Data['InfoMsg'] = 'Task ' . $TaskID . ' Updated';
			}
		}	
	} else {
		$ErrorMsg = 'Unknown Action: ' . $json['Action'];
	}
}

$a = array();
$a['ErrorMsg'] = $ErrorMsg;
if (isset($Data)) {
	$a['Data'] = $Data;
}

echo json_encode($a);


?>